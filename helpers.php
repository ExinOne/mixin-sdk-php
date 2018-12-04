<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-23
 * Time: 下午6:17
 */

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
/**
 *
 *
 *
 *
 */
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
