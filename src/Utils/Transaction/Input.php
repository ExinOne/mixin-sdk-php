<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

class Input
{
    use InternalHelper;
    
    /** @var string */
    public $hash;

    /** @var int */
    public $index;

    /** @var bytes */
    public $genesis;

    /** @var DepositData */
    public $deposit;

    /** @var MintData */
    public $mint;

    public function __construct(string $hash, int $index)
    {
        $this->hash  = $hash;
        $this->index = $index;
    }

    public function encode()
    {
        $ret = $this->encodeMapLen(5);  // 一共有5个字段
        $ret .= $this->encodeString('Hash').$this->encodeBytes(hex2bin($this->hash));  // 序列化Hash, 先转为32长度的bytes
        $ret .= $this->encodeString('Index').$this->encodeInt($this->index);
        if ($this->genesis == null) {
            $ret .= $this->encodeString('Genesis').$this->encodeNil($this->genesis);
        }
        if ($this->deposit == null) {
            $ret .= $this->encodeString('Deposit').$this->encodeNil($this->deposit);
        }
        if ($this->mint == null) {
            $ret .= $this->encodeString('Mint').$this->encodeNil($this->mint);
        }

        return $ret;
    }
}