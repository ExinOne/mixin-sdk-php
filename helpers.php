<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-23
 * Time: 下午6:17
 */

use ExinOne\MixinSDK\Exceptions\NotSupportTIPPINException;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;

if (! function_exists('camel2Underline')) {
    /**
     * @param $string
     *
     * @return string
     */
    function camel2Underline($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1'.'_'.'$2', $string));
    }
}

if (! function_exists('delEmptyItemInArray')) {
    /**
     * @param array $array
     *
     * @return array
     */
    function delEmptyItemInArray(array $array): array
    {
        array_walk($array, function ($value, $key) use (&$array) {
            if (empty($value)) {
                unset($array[$key]);
            }
        });

        return $array;
    }
}

if (! function_exists('assertTIPPIN')) {
    /**
     * @param string $pin
     * @param string $err_msg
     * @throws NotSupportTIPPINException
     */
    function assertTIPPIN(string $pin, string $err_msg)
    {
        if (MixinSDKTrait::isTIPPin($pin)) {
            throw new NotSupportTIPPINException($err_msg);
        }
    }
}