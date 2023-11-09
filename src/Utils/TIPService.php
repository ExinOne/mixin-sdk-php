<?php

namespace ExinOne\MixinSDK\Utils;

use Base64Url\Base64Url;
use ExinOne\MixinSDK\Exceptions\InternalErrorException;

class TIPService
{
    /**
     * 注意得到的私钥是base64url编码的
     * @return string
     * @throws InternalErrorException
     */
    public static function createEd25519PrivateKey(): string
    {
        return Base64Url::encode(sodium_crypto_sign_keypair());
    }

    /**
     * @param string $key_pair 96位的base64url编码的key pair
     * @param string $to_sign  待签名的内容
     * @return string
     * @throws \SodiumException
     */
    public static function signWithEd25519(string $key_pair, string $to_sign): string
    {
        $secret_key = sodium_crypto_sign_secretkey(Base64Url::decode($key_pair));

        return sodium_crypto_sign_detached($to_sign, $secret_key);
    }

    public static function isTIPPin(string $pin): bool
    {
        return strlen($pin) > 6;
    }

    /**
     * 注意得到的公钥是base64url编码的
     * @param string $key_pair 96位的base64url编码的key pair
     * @return string
     * @throws \SodiumException
     */
    public static function getPublicFromEd25519KeyPair(string $key_pair): string
    {
        return Base64Url::encode(sodium_crypto_sign_publickey(Base64Url::decode($key_pair)));
    }
}