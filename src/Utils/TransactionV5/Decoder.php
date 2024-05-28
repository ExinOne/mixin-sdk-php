<?php

namespace ExinOne\MixinSDK\Utils\TransactionV5;

use Brick\Math\BigInteger;
use Exception;

class Decoder
{
    private $buf;

    const TxVersionCommonEncoding = 2;
    const TxVersionBlake3Hash     = 3;
    const TxVersionReferences     = 4;
    const TxVersionHashSignature  = 5;
    const MinimumEncodingVersion  = 0x1;
    const MaximumEncodingInt      = 0xFFFF;

    const AggregatedSignaturePrefix      = 0xFF01;
    const AggregatedSignatureSparseMask  = 0x01;
    const AggregatedSignatureOrdinayMask = 0x00;

    private static $magic = "\x77\x77";
    private static $null  = "\x00\x00";

    public function decodeTransaction($buf)
    {
        $this->buf = hex2bin($buf);

        $b     = $this->readBytes(4);
        $txVer = $this->checkTxVersion($b);
        if ($txVer < self::TxVersionCommonEncoding) {
            throw new Exception("invalid version ".bin2hex($b));
        }

        $tx = [
            'version'              => $txVer,
            'inputs'               => [],
            'outputs'              => [],
            'extra'                => '',
        ];

        $tx['asset'] = bin2hex($this->readBytes(32));
        $il          = $this->readInt();
        for (; $il > 0; $il -= 1) {
            $tx['inputs'][] = $this->readInput();
        }

        $ol = $this->readInt();
        for (; $ol > 0; $ol -= 1) {
            $tx['outputs'][] = $this->readOutput($txVer);
        }

        if ($tx['version'] >= self::TxVersionReferences) {
            $rl = $this->readInt();
            for (; $rl > 0; $rl -= 1) {
                $tx['references'][] = $this->readBytes(32);
            }
            $el = $this->readUint32();
            if ($el > 0) {
                $tx['extra'] = bin2hex($this->readBytes($el));
            }
        } else {
            $tx['extra'] = bin2hex($this->readBytes());
        }

        $sl = $this->readInt();
        if ($sl == self::MaximumEncodingInt) {
            $prefix = $this->readInt();
            switch ($prefix) {
                case self::AggregatedSignaturePrefix:
                    $tx['aggregated_signature'] = $this->readAggregatedSignature();
                    break;
                default:
                    throw new Exception("invalid prefix ".$prefix);
            }
        } else {
            for (; $sl > 0; $sl -= 1) {
                $tx['signatures'][] = $this->readSignatures();
            }
        }

        $es = ord($this->readByte());
        if ($es != 0) {
            throw new Exception("unexpected ending ".$es);
        }
        return $tx;
    }

    public function readInput()
    {
        $in = [
            'hash' => bin2hex($this->readBytes(32)),
        ];

        $in['index'] = $this->readInt();

        $genesis = $this->readBytes();
        if ($genesis) {
            $in['genesis'] = $this->readBytes();
        }

        if ($this->readMagic()) {
            $d             = [
                'chain'       => bin2hex($this->readBytes(32)),
                'asset'       => bin2hex($this->readBytes()),
                'transaction' => bin2hex($this->readBytes()),
                'index'       => $this->readUint64(),
                'amount'      => $this->readDecimal(),
            ];
            $in['deposit'] = $d;
        }

        if ($this->readMagic()) {
            $m          = [
                'group'  => bin2hex($this->readBytes()),
                'batch'  => $this->readUint64(),
                'amount' => $this->readDecimal(),
            ];
            $in['mint'] = $m;
        }

        return $in;
    }

    public function readOutput($ver)
    {
        $o = [
            'type'   => 0,
            'amount' => 0,
            'keys'   => [],
            'mask'   => '',
            'script' => '',
        ];

        $t = $this->readBytes(2);
        if (ord($t[0]) != 0) {
            throw new Exception("invalid output type ".bin2hex($t));
        }
        $o['type'] = ord($t[1]);


        $o['amount'] = $this->readDecimal();

        $kc = $this->readInt();
        for (; $kc > 0; $kc -= 1) {
            $o['keys'][] = bin2hex($this->readBytes(32));
        }

        $o['mask'] = bin2hex($this->readBytes(32));

        $o['script'] = bin2hex($this->readBytes());

        if ($this->readMagic()) {
            $w               = [
                'address' => $this->readBytes(),
                'tag'     => $this->readBytes(),
            ];
            $o['withdrawal'] = $w;
        }

        return $o;
    }

    public function readSignatures()
    {
        $sc = $this->readInt();
        $sm = [];

        for ($i = 0; $i < $sc; $i++) {
            $si      = $this->readUint16();
            $sm[$si] = $this->readBytes(64);
        }

        if (count($sm) != $sc) {
            throw new Exception("signatures count ".$sc." ".count($sm));
        }
        return $sm;
    }

    private function readBytes($length = null)
    {
        if ($length === null) {
            $length = $this->readInt();
        }
        $result    = substr($this->buf, 0, $length);
        $this->buf = substr($this->buf, $length);
        return $result;
    }

    private function readInt()
    {
        $b = $this->readBytes(2);
        $d = unpack("n", $b)[1];
        if ($d > self::MaximumEncodingInt) {
            throw new Exception("large int ".$d);
        }
        return $d;
    }

    private function readUint16()
    {
        $b = $this->readBytes(2);
        return unpack("n", $b)[1];
    }

    private function readUint32()
    {
        $b = $this->readBytes(4);
        return unpack("N", $b)[1];
    }

    private function readUint64()
    {
        $b = $this->readBytes(8);
        return unpack("J", $b)[1];
    }

    private function readInteger()
    {
        $il = $this->readInt();
        return $this->readBytes($il);
    }

    private function readDecimal()
    {
        $bytes = $this->readInteger();
        return (string)BigInteger::fromBytes($bytes, false)->dividedBy('1e8');
    }

    private function readByte()
    {
        return $this->readBytes(1);
    }

    private function readMagic()
    {
        $b = $this->readBytes(2);
        if ($b === self::$magic) {
            return true;
        }
        if ($b === self::$null) {
            return false;
        }
        throw new Exception("malformed ".bin2hex($b));
    }

    private function readAggregatedSignature()
    {
        $js = [
            'signature' => $this->readBytes(64),
            'signers'   => [],
        ];

        $typ = ord($this->readByte());
        switch ($typ) {
            case self::AggregatedSignatureSparseMask:
                $l = $this->readInt();
                for (; $l > 0; $l--) {
                    $js['signers'][] = $this->readInt();
                }
                break;
            case self::AggregatedSignatureOrdinayMask:
                $masks = $this->readBytes();
                foreach (str_split($masks) as $i => $ctr) {
                    for ($j = 0; $j < 8; $j++) {
                        $k = 1 << $j;
                        if ((ord($ctr) & $k) == $k) {
                            $js['signers'][] = $i * 8 + $j;
                        }
                    }
                }
                break;
            default:
                throw new Exception("invalid mask type ".$typ);
        }
        return $js;
    }

    private function checkTxVersion($val)
    {
        foreach ([self::TxVersionCommonEncoding, self::TxVersionBlake3Hash, self::TxVersionReferences, self::TxVersionHashSignature] as $version) {
            if (substr($val, 0, 4) === self::$magic."\x00".chr($version)) {
                return $version;
            }
        }
        return 0;
    }
}