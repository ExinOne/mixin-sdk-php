<?php

declare(strict_types=1);


namespace ExinOne\MixinSDK\Utils\Transaction;

use Exception;

trait InternalHelper
{
    function prettyString(array $obj): string
    {
        $ret = '';
        foreach ($obj as $v) {
            if (is_array($v)) {
                $ret .= '['.$this->prettyString($v).'] ';
            } else {
                $ret .= $v.' ';
            }
        }

        return $ret;
    }

    function prettyPrint(array $obj)
    {
        echo $this->prettyString($obj)."\n";
    }

    function echoBin(string $a)
    {
        $ret = [];
        $len = strlen($a);
        for ($i = 0; $i < $len; $i++) {
            $ret[] = ord($a[$i]);
        }
        echo implode(' ', $ret)."\n";

        return $ret;
    }

    function assertArray(array $a1, array $a2): bool
    {
        foreach ($a1 as $key => $v) {
            if ($v != $a2[$key]) {
                return false;
            }
        }

        return true;
    }

    function encodeNil($data): string
    {
        if ($data == null) {
            return pack('C', 0xc0);
        }

        return '';
    }

    function encodeMapLen(int $data): string
    {
        if ($data < 16) {
            return pack('C', 0x80 | $data);
        } elseif ($data < 65536) {
            return pack('C2n', 0xde, $data >> 8, $data);
        }
    }

    function encodeString(string $data): string
    {
        $len = strlen($data);
        if ($len < 32) {
            return pack('C', 0xa0 | $len).$data;
        } elseif ($len < 256) {
            return pack('C', 0xd9).'';
        }
    }

    function encodeInt(int $data): string
    {
        if ($data < 32) {
            return pack('C', $data);
        }
    }

    function encodeBytes(string $data): string
    {
        $len = strlen($data);
        if ($len < 256) {
            return pack('C2', 0xc4, $len).$data;
        }
    }

    function encodeArray(array $data): string
    {
        $ret = '';
        $len = count($data);
        if ($len < 16) {
            $ret .= pack('C', 0x90 | $len);
        }
        for ($i = 0; $i < $len; $i++) {
            $ret .= $this->encodeObject($data[$i]);
        }

        return $ret;
    }

    function encodeObject($data): string
    {
        $ret = '';
        if ($data instanceof Input) {
            $ret = $data->encode();
        } elseif ($data instanceof InternalOutput) {
            $ret = $data->encode();
        } elseif (is_string($data)) {
            $ret = $this->encodeBytes(hex2bin($data));
        }

        return $ret;
    }

    function encodeExt($obj): string
    {
        if (!$obj instanceof BigInteger) {
            throw new Exception('error type');
        }
        $data = $obj->encode();

        $type_id = 0;  // go 代码中写死的
        $len     = strlen($data);
        if ($len == 1) {
            return pack('C2', 0xd4, $type_id).$data;
        } elseif ($len == 2) {
            return pack('C2', 0xd5, $type_id).$data;
        } elseif ($len == 4) {
            return pack('C2', 0xd6, $type_id).$data;
        } elseif ($len == 8) {
            return pack('C2', 0xd7, $type_id).$data;
        } elseif ($len == 16) {
            return pack('C2', 0xd8, $type_id).$data;
        }

        if ($len < 256) {
            return pack('C3', 0xc7, $len, $type_id).$data;
        } elseif ($len < 65536) {
            return pack('C2nC', 0xc8, $len >> 8, $len, $type_id).$data;  // 没有测试
        }

        return pack('C2nNJC', 0xc9, $len >> 24, $len >> 16, $len >> 8, $len, $type_id).$data;  // 没有测试
    }
}