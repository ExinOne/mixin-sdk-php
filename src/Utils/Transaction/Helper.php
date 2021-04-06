<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;
use ExinOne\MixinSDK\MixinSDK;
use phpDocumentor\Reflection\Types\Intersection;

/**
 * Class TransactionHelper
 *
 * @package ExinOne\MixinSDK\Utils
 */
class Helper
{
    /**
     * @param  MixinSDK  $sdk
     * @param  array  $inputs
     * @param  array  $outputs
     * @param  string  $memo
     * @param  string  $hint
     * @return string
     * @throws InvalidInputFieldException
     */
    public static function buildTransaction(
        MixinSDK $sdk,
        array $inputs,
        array $outputs,
        string $memo,
        string $hint
    ): string {
        $tx = Transaction::NewTransaction();

        // fill up inputObject
        foreach ($inputs as $v) {
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

        $internalOutputs = self::convOutput2InternalOutput($sdk, $outputs, $hint);

        // fill up outputObject
        foreach ($internalOutputs as $v) {
            if (strlen($v->mask) > 0) {
                $tx->AddOutput($v);
            }
        }

        // 16进制解码为bytes
        $extra     = hex2bin($memo);
        $tx->extra = $extra;

        $signed = $tx->AsLatestVersion();

        return bin2hex($signed->Marshal());
    }


    public static function convOutput2InternalOutput(MixinSDK $sdk, array $outputs, string $hint): array
    {
        foreach ($outputs as $k => $output) {
            $outputs[$k]['hint'] = $hint;
        }

        $respInternalOutputs = $sdk->wallet()->readBatchOutputs($outputs);

        $internalOutputs = [];
        foreach ($respInternalOutputs as $k => $respInternalOutput) {
            $internalOutputs[] = [
                'amount' => $outputs[$k]->amount,
                'script' => 'fffe0'.(string)($k + 1), // TODO 这里只能支持 10个 output 以内
                'mask'   => $respInternalOutput['mask'],
                'keys'   => $respInternalOutput['keys'],
            ];
        }

        return $internalOutputs;
    }


}