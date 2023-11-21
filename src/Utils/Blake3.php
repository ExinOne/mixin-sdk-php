<?php

namespace ExinOne\MixinSDK\Utils;

/*
PHP implementation of BLAKE3

https://github.com/BLAKE3-team/BLAKE3-specs/blob/master/blake3.pdf

https://github.com/BLAKE3-team/BLAKE3

It supports HASH, KEYED and DERIVE modes with XOF output

There is a python version https://github.com/oconnor663/bao

which is 2.5x slower than this implementation in generating the hash

This implementation have been checked with the test vectors provided

https://raw.githubusercontent.com/BLAKE3-team/BLAKE3/master/test_vectors/test_vectors.json

By default, XOF output are 32 bytes

Examples of use:

HASH MODE
		$b2 = new BLAKE3();
		$hash = $b2->hash($h,$xof_length);

KEYED HASH

		$b2 = new BLAKE3($key);
		$keyed_hash = $b2->hash($h,$xof_length);

DERIVE KEY
		$b2 = new BLAKE3();
		$derive_key = $b2->derivekey($context_key,$context,$xof_length);


@denobisipsis 2021
*/

class Blake3
{
    const MSG_SCHEDULE = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
        [2, 6, 3, 10, 7, 0, 4, 13, 1, 11, 12, 5, 9, 14, 15, 8],
        [3, 4, 10, 12, 13, 2, 7, 14, 6, 5, 9, 0, 11, 15, 8, 1],
        [10, 7, 12, 9, 14, 3, 13, 15, 4, 0, 11, 2, 5, 8, 1, 6],
        [12, 13, 9, 11, 15, 10, 14, 8, 7, 2, 5, 3, 0, 1, 6, 4],
        [9, 14, 11, 5, 8, 12, 15, 1, 13, 3, 0, 10, 2, 6, 4, 7],
        [11, 15, 5, 0, 1, 9, 8, 6, 14, 10, 2, 12, 3, 4, 7, 13],
    ];

    const IV = [
        0x6a09e667, 0xbb67ae85,
        0x3c6ef372, 0xa54ff53a,
        0x510e527f, 0x9b05688c,
        0x1f83d9ab, 0x5be0cd19
    ];

    const BLOCK_SIZE 	= 64;
    const HEX_BLOCK_SIZE	= 128;
    const CHUNK_SIZE 	= 1024;
    const KEY_SIZE 		= 32;
    const HASH_SIZE 	= 32;
    const PARENT_SIZE 	= 2 * 32;
    const WORD_BITS 	= 32;
    const WORD_BYTES 	= 4;
    const WORD_MAX 		= 2**32 - 1;
    const HEADER_SIZE 	= 8;

    # domain flags
    const CHUNK_START 		= 1 << 0;
    const CHUNK_END 		= 1 << 1;
    const ROOT 			= 1 << 3;
    const PARENT 			= 1 << 2;
    const KEYED_HASH 		= 1 << 4;
    const DERIVE_KEY 		= 1 << 5;
    const DERIVE_KEY_MATERIAL 	= 1 << 6;

    const PACKING = "V*";

    function __construct($key="")
    {
        $this->cv    = [];
        $this->state = [];
        $this->key   = "";
        $this->flag			= 0;
        $this->flagkey    		= false;
        $this->derive_key 		= false;
        $this->derive_key_material 	= false;

        if ($key)
        {
            $key  = substr($key,0,self::BLOCK_SIZE);
            $size = strlen($key);

            if ($size<self::BLOCK_SIZE)
                $key .= str_repeat("\x0",self::BLOCK_SIZE-strlen($key));

            $key  = array_values(unpack(self::PACKING,$key));
            $this->cv      = $key;
            $this->flagkey = true;
        }
        else    $this->cv      = self::IV;
    }

    function derivekey($context_key="",$context="",$xof_length)
    {
        $this->state     = self::IV;

        $size		 = strlen($context);
        if ($size<self::BLOCK_SIZE)
            $context.= str_repeat("\0",self::BLOCK_SIZE-$size);

        $context_words = array_values(unpack(self::PACKING,$context));
        self::chacha($context_words,0,$size,43);

        $this->cv = array_slice($this->state,0,8);
        $this->derive_key_material = true;

        $derive_key       = self::hash($context_key,$xof_length);
        $derive_key_words = array_values(unpack(self::PACKING,$derive_key));

        $this->cv 	  = $derive_key_words;

        return $derive_key;
    }

    function Right_Roll($a, $n)
    {
        $lp   = ($a >> $n)           	      & self::WORD_MAX;
        $rp   = ($a << (self::WORD_BITS- $n)) & self::WORD_MAX;

        return ($lp & ((1 << (self::WORD_BITS - $n)) - 1))| $rp;
    }

    function G(&$v, $a, $b, $c, $d,$m1,$m2)
    {
        $f = $v[$a];
        $g = $v[$b];
        $h = $v[$c];
        $i = $v[$d];

        $f += $g + $m1;
        $i  = self::Right_Roll($i^$f,16);
        $h += $i;
        $g  = self::Right_Roll($g^$h,12);

        $f += $g + $m2;
        $i  = self::Right_Roll($i^$f,8);
        $h += $i;
        $g  = self::Right_Roll($g^$h,7);

        $v[$a] = $f & self::WORD_MAX;
        $v[$b] = $g & self::WORD_MAX;
        $v[$c] = $h & self::WORD_MAX;
        $v[$d] = $i & self::WORD_MAX;
    }

    function chacha($chunk_words,$counter,$size,$flag,$is_xof=false,$block_over=false)
    {
        $v = $this->state;

        for ($k=0;$k<4;$k++) $v[$k+8] = self::IV[$k];

        $v[12]   = $counter & self::WORD_MAX;
        $v[13]   = ($counter >> self::WORD_BITS) & self::WORD_MAX;
        $v[14]   = $size;
        $v[15]   = $flag;

        for ($r=0;$r<7;$r++)
        {
            $sr = self::MSG_SCHEDULE[$r];

            self::G($v,  0,  4,  8, 12, $chunk_words[$sr[0]], $chunk_words[$sr[1]]);
            self::G($v,  1,  5,  9, 13, $chunk_words[$sr[2]], $chunk_words[$sr[3]]);
            self::G($v,  2,  6, 10, 14, $chunk_words[$sr[4]], $chunk_words[$sr[5]]);
            self::G($v,  3,  7, 11, 15, $chunk_words[$sr[6]], $chunk_words[$sr[7]]);
            self::G($v,  0,  5, 10, 15, $chunk_words[$sr[8]], $chunk_words[$sr[9]]);
            self::G($v,  1,  6, 11, 12, $chunk_words[$sr[10]],$chunk_words[$sr[11]]);
            self::G($v,  2,  7,  8, 13, $chunk_words[$sr[12]],$chunk_words[$sr[13]]);
            self::G($v,  3,  4,  9, 14, $chunk_words[$sr[14]],$chunk_words[$sr[15]]);
        }

        for ($i=0;$i<8;$i++)
            $v[$i] ^= $v[$i+8];

        if ($is_xof)
        {
            for ($i=0;$i<8;$i++)
                $v[$i+8] ^= $this->cv[$i];
            if (!$block_over)
                $this->cv  = array_slice($v,0,8);
        }

        $this->state = $v;
    }

    function setflags($start)
    {
        $this->flag = $start;

        if ($this->flagkey)
            $this->flag   |= self::KEYED_HASH;

        if ($this->derive_key)
            $this->flag   |= self::DERIVE_KEY;

        if ($this->derive_key_material)
            $this->flag   |= self::DERIVE_KEY_MATERIAL;
    }

    function nodetree($block, $is_xof = false)
    {
        $size    	 = self::BLOCK_SIZE;
        $this->state     = $this->cv;
        $chunk_words     = array_values(unpack(self::PACKING,$block));

        // for XOF output

        if ($is_xof)
        {
            $this->last_cv	 	= $this->cv;
            $this->last_state 	= $this->state;
            $this->last_chunk 	= $chunk_words;
            $this->last_size 	= $size;
        }

        self::chacha($chunk_words,0,$size,$this->flag,$is_xof);

        // last_v for generating the first xof digest

        if ($is_xof)
            $this->last_v = $this->state;

        return pack(self::PACKING,...array_slice($this->state,0,8));
    }

    function nodebytes($block, $is_root = false)
    {
        $hashes 	= "";
        $counter	= 0;
        $chunks 	= str_split($block,self::CHUNK_SIZE);

        foreach ($chunks as $chunk)
        {
            $this->state = $this->cv;

            if (strlen($chunk) > self::BLOCK_SIZE)
            {
                $size    = self::BLOCK_SIZE;

                if (strlen($chunk) < self::CHUNK_SIZE)
                {
                    $size = strlen($chunk) % self::BLOCK_SIZE;

                    if (!$size)
                        $size = self::BLOCK_SIZE;

                    $npad	 = ceil(strlen($chunk)/self::BLOCK_SIZE) * self::BLOCK_SIZE;
                    $chunk  .= str_repeat("\x0",$npad-strlen($chunk));
                }

                $chunk_words = array_chunk(array_values(unpack(self::PACKING,$chunk)),16);

                self::setflags(self::CHUNK_START);
                self::chacha($chunk_words[0],$counter,self::BLOCK_SIZE,$this->flag, true, !$is_root);
                self::setflags(0);

                for ($k=1;$k<sizeof($chunk_words)-1;$k++)
                    self::chacha($chunk_words[$k],$counter,self::BLOCK_SIZE,$this->flag, true, !$is_root);

                if ($is_root)
                {
                    self::setflags(self::CHUNK_END|self::ROOT);
                    $counter = 0;
                }
                else     self::setflags(self::CHUNK_END);

                $chunk_words = $chunk_words[$k];
            }
            else
            {
                $size    	 = strlen($chunk);
                $chunk  	.= str_repeat("\x0",self::BLOCK_SIZE-strlen($chunk));
                $chunk_words     = array_values(unpack(self::PACKING,$chunk));

                $flag    = self::CHUNK_START | self::CHUNK_END;

                if ($is_root)
                {
                    $flag   |= self::ROOT;
                    $counter = 0;
                }

                self::setflags($flag);
            }

            // for XOF output

            $this->last_cv	 	= $this->cv;
            $this->last_state 	= $this->state;

            self::chacha($chunk_words,$counter,$size,$this->flag, true, !$is_root);

            $hashes .= pack(self::PACKING,...array_slice($this->state,0,8));

            $counter++;
        }

        // last_v for generating the first xof digest

        $this->last_chunk 	= $chunk_words;
        $this->last_size 	= $size;
        $this->last_v 		= $this->state;

        return $hashes;
    }

    function XOF_output($hash, $XOF_digest_length)
    {
        // Output bytes. By default 32

        $cycles 	= ceil($XOF_digest_length/self::BLOCK_SIZE);
        $XofHash	= $hash;
        $XofHash       .= pack(self::PACKING,...array_slice($this->last_v,8));

        for ($k=1;$k<$cycles;$k++)
        {
            $this->cv 	= $this->last_cv;
            $this->state	= $this->last_state;
            self::chacha($this->last_chunk,$k,$this->last_size,$this->flag,true);
            $XofHash       .= pack(self::PACKING,...$this->state);
        }

        // final xof bytes

        $last_bytes = self::BLOCK_SIZE-($XOF_digest_length % self::BLOCK_SIZE);

        if ($last_bytes!=self::BLOCK_SIZE)
            $XofHash = substr($XofHash,0,-$last_bytes);

        return bin2hex($XofHash);
    }

    function hash($block, $XOF_digest_length = 32)
    {
        if (strlen($block) <= self::CHUNK_SIZE)
            $is_root = true;
        else    $is_root = false;

        $tree = str_split(self::nodebytes($block, $is_root),self::BLOCK_SIZE);
        /*
        This is the reverse tree. It makes a reduction from left to right in pairs

        First it computes all the hashes from input data, then make the tree reduction of hashes
        till there is only one pair

        If there is an odd number of hashes, it pass the last hash without processing it
        till there is a parent
        */
        if (sizeof($tree)>1)
        {
            self::setflags(self::PARENT);

            while (sizeof($tree)>1)
            {
                $chaining = "";
                foreach ($tree as $pair)
                {
                    if (strlen($pair) < self::BLOCK_SIZE)
                        $chaining.= $pair;
                    else    $chaining.= self::nodetree($pair);
                }
                $tree = str_split($chaining,self::BLOCK_SIZE);
            }
        }

        if (strlen($tree[0]) > self::BLOCK_SIZE/2)
        {
            $flag    = self::CHUNK_START | self::CHUNK_END | self::ROOT;
            self::setflags(++$flag);
            $hash = self::nodetree($tree[0], $is_xof = true);
        }
        else    $hash = $tree[0];

        return self::XOF_output($hash,$XOF_digest_length);
    }
}