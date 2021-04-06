<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

class Transaction
{
    /** @var int */
    public $version = 0x01;

    /** @var string */
    public $asset;

    /** @var Input[] */
    public $inputs = [];

    /** @var InternalOutput[] */
    public $outputs = [];

    /** @var string */
    public $extra;

    public static function NewTransaction(): self
    {
        return new self();
    }

    public function AddInput(Input $input)
    {
        $this->asset = $input[0]['asset'];
        $this->inputs[] = $input;
    }

    public function AddOutput(InternalOutput $output)
    {
        if (is_string($output->amount)) {
            $output->amount = new BigInteger($output->amount);
        } elseif (is_int($output->amount)) {
            $output->amount = new BigInteger((string)($output->amount));
        }

        $this->outputs[] = $output;
    }

    public function AsLatestVersion(): VersionedTransaction
    {
        if ($this->version != 0x01) {
            throw new \Exception("version: {$this->version} is not support");
        }

        return new VersionedTransaction(new SignedTransaction($this));
    }
}