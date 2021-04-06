<?php

declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;


class InternalOutput
{
    use InternalHelper;

    /** @var int */
    public $type = "0";

    /** @var BigInteger */
    public $amount;

    /** @var string[] */
    public $keys;

    /** @var string */
    public $script;

    /** @var string */
    public $mask;

    public function __construct(BigInteger $amount, array $keys, string $script, string $mask, int $type = 0)
    {
        $this->type   = $type;
        $this->amount = $amount;
        $this->keys   = $keys;
        $this->script = $script;
        $this->mask   = $mask;
    }

    public function encode(): string
    {
        $ret = $this->encodeMapLen(5);  // 一共有5个字段, 对应的Withdrawal 被msgpack标记为不传递
        $ret .= $this->encodeString('Type').$this->encodeInt($this->type);
        $ret .= $this->encodeString('Amount').$this->encodeExt($this->amount);
        $ret .= $this->encodeString('Keys').$this->encodeArray($this->keys);
        $ret .= $this->encodeString('Script').$this->encodeBytes(hex2bin($this->script));
        $ret .= $this->encodeString('Mask').$this->encodeBytes(hex2bin($this->mask));

        return $ret;
    }
}