<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-23
 * Time: 下午4:59
 */

namespace ExinOne\MixinSDK\Apis;

use Base64Url\Base64Url;
use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;

class Pin extends Api
{
    /**
     * @param string|null $old_pin 六位数字PIN码，或者base64url编码的TIP私钥
     * @param string      $pin     六位数字PIN码，或者base64url编码的TIP私钥
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function updatePin($old_pin, string $pin): array
    {
        if ($old_pin === null) {
            $old_pin = $this->config['pin'];
        }

        if (strlen($old_pin) > 6) {
            throw new InvalidInputFieldException('TIP pin does not support update');
        } elseif ($old_pin == '') {
            $old_pin = '';
        } else {
            $old_pin = $this->encryptPin($old_pin);
        }
        $body = [
            'old_pin' => $old_pin,
            'pin'     => $this->encryptPin($this->getFormattedPublicKey($pin)),
        ];

        return $this->res($body);
    }

    protected function getFormattedPublicKey(string $pin): string
    {
        if (strlen($pin) > 6) {
            // 参考 https://developers.mixin.one/zh-CN/docs/api/pin/tip#%E4%B8%8A%E4%BC%A0%E7%A7%81%E9%92%A5%E5%88%B0%E6%9C%8D%E5%8A%A1%E5%99%A8
            // "J"是64位大端序无符号整数
            $counter          = pack("J", 1);
            $counter_hex      = bin2hex($counter);
            $public_base64url = self::getPublicFromEd25519KeyPair($pin);
            $public_bin       = Base64Url::decode($public_base64url);
            $public_hex       = bin2hex($public_bin);
            $pin              = hex2bin($public_hex.$counter_hex);
        }

        return $pin;
    }

    /**
     * @param $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function verifyPin(string $pin): array
    {
        if (strlen($pin) > 6) {
            $timestamp = str_replace('.', '', sprintf("%0.9f", microtime(true)));

            $_timestamp = sprintf("%032d", $timestamp);

            $body = [
                'pin_base64' => $this->encryptPin(MixinSDKTrait::signWithEd25519($pin, 'TIP:VERIFY:'.$_timestamp)),
                'timestamp'  => (integer)$timestamp,
            ];
        } else {
            $body = [
                'pin' => $this->encryptPin($pin),
            ];
        }

        return $this->res($body);
    }
}
