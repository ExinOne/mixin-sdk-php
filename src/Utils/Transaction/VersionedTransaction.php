<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

class VersionedTransaction extends SignedTransaction
{
    /** @var SignedGenesisHackTransaction */
    public $bad_genesis;

    /** @var SignedTransaction */
    public $signed_transaction;

    public function __construct(SignedTransaction $v)
    {
        $this->version            = $v->version;
        $this->asset              = $v->asset;
        $this->inputs             = $v->inputs;
        $this->outputs            = $v->outputs;
        $this->extra              = $v->extra;
        $this->signatures         = $v->signatures;
        $this->signed_transaction = $v;
    }

    public function Marshal(): string
    {
        switch ($this->version) {
            case 0:
                return $this->CompressMsgpackMarshalPanic($this->bad_genesis);
            case 0x01:
                return $this->CompressMsgpackMarshalPanic($this->signed_transaction);
        }

        return '';
    }

    public function CompressMsgpackMarshalPanic($v): string
    {
        $payload = '';
        if ($v instanceof SignedTransaction) {
            $payload = $v->encode();
        }
        // TODO: 调用 php-zstd 进行压缩, go中使用的是gozstd, 在github.com/MixinNetwork/mixin/common/msgpack.go的方法
        // CompressMsgpackMarshalPanic 中
        return $payload;
    }
}