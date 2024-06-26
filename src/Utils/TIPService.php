<?php

namespace ExinOne\MixinSDK\Utils;

use Base64Url\Base64Url;
use ExinOne\MixinSDK\Exceptions\EncodeFailException;
use ExinOne\MixinSDK\Exceptions\EncodeNotYetImplementedException;
use ExinOne\MixinSDK\Exceptions\InternalErrorException;
use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;
use ExinOne\MixinSDK\Utils\TransactionV5\Encoder;
use ParagonIE\Sodium\Core\Ed25519;
use ParagonIE_Sodium_Core32_Ed25519 as Ed25519_32;

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

    public static function signWithMixinEd25519(string $bin_pair, string $bin_to_sign): string
    {
        $message = $bin_to_sign;
        $sk      = $bin_pair;
        # crypto_hash_sha512(az, sk, 32);
        $az = hash('sha512', Ed25519::substr($sk, 0, 32), true);
        // $az = $bin_pair;
        //
        // # az[0] &= 248;
        // # az[31] &= 63;
        // # az[31] |= 64;
        $az[0]  = Ed25519::intToChr(Ed25519::chrToInt($az[0]) & 248);
        $az[31] = Ed25519::intToChr((Ed25519::chrToInt($az[31]) & 63) | 64);
        // $az = $bin_pair;

        # crypto_hash_sha512_init(&hs);
        # crypto_hash_sha512_update(&hs, az + 32, 32);
        # crypto_hash_sha512_update(&hs, m, mlen);
        # crypto_hash_sha512_final(&hs, nonce);
        $hs = hash_init('sha512');
        hash_update($hs, Ed25519::substr($az, 32, 32));
        hash_update($hs, $message);
        $nonceHash = hash_final($hs, true);

        # memmove(sig + 32, sk + 32, 32);
        $pk = Ed25519::substr($sk, 32, 32);

        # sc_reduce(nonce);
        # ge_scalarmult_base(&R, nonce);
        # ge_p3_tobytes(sig, &R);
        $nonce = Ed25519::sc_reduce($nonceHash).Ed25519::substr($nonceHash, 32);
        $sig   = Ed25519::ge_p3_tobytes(
            Ed25519::ge_scalarmult_base($nonce)
        );

        # crypto_hash_sha512_init(&hs);
        # crypto_hash_sha512_update(&hs, sig, 64);
        # crypto_hash_sha512_update(&hs, m, mlen);
        # crypto_hash_sha512_final(&hs, hram);
        $hs = hash_init('sha512');
        hash_update($hs, Ed25519::substr($sig, 0, 32));
        hash_update($hs, Ed25519::substr($pk, 0, 32));
        hash_update($hs, $message);
        $hramHash = hash_final($hs, true);

        # sc_reduce(hram);
        # sc_muladd(sig + 32, hram, az, nonce);
        $hram = Ed25519::sc_reduce($hramHash);

        $sigAfter = Ed25519::sc_muladd($hram, substr($bin_pair, 0, 32), $nonce);
        $sig      = Ed25519::substr($sig, 0, 32).Ed25519::substr($sigAfter, 0, 32);

        try {
            \ParagonIE_Sodium_Compat::memzero($az);
        } catch (\SodiumException $ex) {
            $az = null;
        }
        return $sig;
    }

    public static function getBytesWithClamping(string $bin_key): string
    {
        if (strlen($bin_key) !== 32) {
            throw new InternalErrorException('Invalid key length');
        }

        $bin_key = str_pad($bin_key, 64, "\0", STR_PAD_RIGHT);

        $bin_key[0]  = Ed25519::intToChr(Ed25519::chrToInt($bin_key[0]) & 248);
        $bin_key[31] = Ed25519::intToChr((Ed25519::chrToInt($bin_key[31]) & 63) | 64);

        return Ed25519::sc_reduce($bin_key);
    }

    /**
     * 目前极端情况下可能计算出错误的结果
     * @param string $bin_seed
     * @param bool   $use_32_bits 使用32位计算，速度会非常慢，但可能计算出正确的结果
     * @return string
     * @throws \SodiumException
     */
    public static function getPublicKeyFromEd25519KeyPair(string $bin_seed, bool $use_32_bits = false): string
    {
        if ($use_32_bits) {
            return Ed25519_32::ge_p3_tobytes(
                Ed25519_32::ge_scalarmult_base($bin_seed)
            );
        }
        return Ed25519::ge_p3_tobytes(
            Ed25519::ge_scalarmult_base($bin_seed)
        );
    }

    public static function getSecretKeyFromSeed(string $seed): string
    {
        return sodium_crypto_sign_secretkey(sodium_crypto_sign_seed_keypair($seed));
    }

    public static function getSpendKeyFromEd25519KeyPair(string $key_pair): string
    {
        $secret_key = sodium_crypto_sign_secretkey(Base64Url::decode($key_pair));

        return bin2hex(substr($secret_key,0,32));
    }

    /**
     * 对Safe主网交易进行签名
     * @param array  $transaction
     * @param array  $views
     * @param string $spent_key
     * @param bool   $use_32_bits
     * @return string
     * @throws InternalErrorException
     * @throws InvalidInputFieldException
     * @throws EncodeFailException
     * @throws EncodeNotYetImplementedException
     * @throws \SodiumException
     */
    public static function safeSignTransaction(array $transaction, array $views, string $spent_key, bool $use_32_bits, int $k = 0): string
    {
        $inputs = $transaction['inputs'] ?? [];
        if (! $inputs) {
            throw new InvalidInputFieldException('MISSING_INPUTS');
        }
        if (count($inputs) !== count($views)) {
            throw new InvalidInputFieldException('INPUTS_AND_VIEWS_NOT_MATCH');
        }

        $raw = (new Encoder())->encodeTransaction($transaction);
        $msg = (new Blake3())->hash(hex2bin($raw));

        $raw_key = substr(Base64Url::decode($spent_key), 0, 32);

        $spent_y = hash("sha512", $raw_key, true);

        $y = TIPService::getBytesWithClamping(substr($spent_y, 0, 32));

        $signatures_map = [];
        foreach ($inputs as $i => $input) {
            if (! ($views[$i] ?? null)) {
                throw new InvalidInputFieldException('MISSING_VIEW');
            }

            $seed = hex2bin($views[$i]);

            // $seed = sodium_crypto_core_ristretto255_scalar_add($seed, $y);
            $seed = \ParagonIE_Sodium_Core_Ed25519::scalar_add($seed, $y);

            $pub = TIPService::getPublicKeyFromEd25519KeyPair($seed, $use_32_bits);

            $new_key = $seed.$pub;

            $sig = bin2hex(TIPService::signWithMixinEd25519($new_key, hex2bin($msg)));

            $signatures_map[$i] = [$k => $sig]; // for 1/1 bot transaction
        }

        $transaction['signatures_map'] = $signatures_map;

        return (new Encoder())->encodeTransaction($transaction);
    }
}