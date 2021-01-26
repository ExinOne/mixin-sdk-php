<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;

/**
 * Class TransactionHelper
 *
 * @package ExinOne\MixinSDK\Utils
 */
class Helper
{
    /**
     * @param  array  $input_object
     *
     * @return string
     * @throws InvalidInputFieldException
     */
    public static function buildTransaction(array $input_object): string
    {
        $tx = Transaction::NewTransaction($input_object['asset']);
        // fill up inputObject
        foreach ($input_object['inputs'] as $v) {
            if (!empty($input->genesis)) {
                throw new InvalidInputFieldException("invalid input with Genesis, it's not needed in this function");
            }
            if (!empty($input->deposit)) {
                throw new InvalidInputFieldException("invalid input with Deposit, it's not needed in this function");
            }
            if (!empty($input->mint)) {
                throw new InvalidInputFieldException("invalid input with Mint, it's not needed in this function");
            }

            $tx->AddInput($v);
        }

        // fill up outputObject
        foreach ($input_object['outputs'] as $v) {
            if (strlen($v->mask) > 0) {
                $tx->AddOutput($v);
            }
        }

        // 16进制解码为bytes
        $extra     = hex2bin($input_object['extra']);
        $tx->extra = $extra;

        $signed = $tx->AsLatestVersion();

        return bin2hex($signed->Marshal());
    }
}