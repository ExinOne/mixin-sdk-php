<?php

declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

use ExinOne\MixinSDK\MixinSDK;

class Output
{
    use InternalHelper;

    /**
     * @var array
     *
     * []string
     */
    public $receivers;

    /**
     * @var int
     */
    public $threshold;

    /**
     * @var float
     */
    public $amount;

    public function __construct(array $receivers, int $threshold, float $amount)
    {
        $this->receivers = $receivers;
        $this->threshold = $threshold;
        $this->amount    = $amount;
    }



}