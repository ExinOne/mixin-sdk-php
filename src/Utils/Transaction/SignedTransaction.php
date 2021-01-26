<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

class SignedTransaction extends Transaction
{
    use InternalHelper;

    /** @var [][]crypto.Signature */
    public $signatures;

    /** @var Transaction */
    public $transaction;

    public function __construct(Transaction $v)
    {
        $this->version     = $v->version;
        $this->asset       = $v->asset;
        $this->inputs      = $v->inputs;
        $this->outputs     = $v->outputs;
        $this->extra       = $v->extra;
        $this->transaction = $v;
    }

    public function encode(): string
    {
        // 按字段的顺序
        $ret = $this->encodeMapLen(6);
        $ret .= $this->encodeString('Version').$this->encodeInt($this->version);  // 序列化Version字段
        $ret .= $this->encodeString('Asset').$this->encodeBytes(hex2bin($this->asset));  // 序列化Asset, 先转为32长度的bytes
        $ret .= $this->encodeString('Inputs').$this->encodeArray($this->inputs);
        $ret .= $this->encodeString('Outputs').$this->encodeArray($this->outputs);
        $ret .= $this->encodeString('Extra').$this->encodeBytes($this->extra);  // Extra 已经被序列为bytes
        if ($this->signatures == null) {
            $ret .= $this->encodeString('Signatures').$this->encodeNil($this->signatures);
        }

        return $ret;
    }
}