<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-8-16
 * Time: 下午11:08
 */

namespace ExinOne\MixinSDK\Traits;

use ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException;
use Firebase\JWT\JWT;
use phpseclib\Crypt\RSA;
use Ramsey\Uuid\Uuid;

/**
 * Trait MixinSDKTrait
 *
 * @package ExinOne\MixinSDK
 */
trait MixinSDKTrait
{
    /**
     * @param $method
     * @param $uri
     * @param $body
     *
     * @return string
     * @throws \Exception
     */
    public function getToken($method, $uri, $body)
    {
        $token = [
            "uid" => $this->config['client_id'],
            "sid" => $this->config['session_id'],
            "iat" => time(),
            "exp" => time() + 200,
            "jti" => Uuid::uuid4()->toString(),
            "sig" => bin2hex(hash('sha256', $method.$uri.$body, true)),
        ];
        $jwt   = JWT::encode($token, $this->config['private_key'], 'RS512');

        return $jwt;
    }

    /**
     * @return mixed
     */
    public function generateSSLKey()
    {
        $res = openssl_pkey_new([
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        // 获取 private Key
        openssl_pkey_export($res, $pri_key);

        // 获取 public Key
        $pub_key = openssl_pkey_get_details($res)['key'];

        // 生成 session_secret
        $session_secret = str_replace(["-----BEGIN PUBLIC KEY-----\n", "-----END PUBLIC KEY-----", "\n"], '', $pub_key);

        return [$pri_key, $pub_key, $session_secret];
    }

    /**
     * @param $pin
     *
     * @return string
     * @throws LoadPrivateKeyException
     */
    public function encryptPin($pin)
    {
        $private_key = $this->config['private_key'];
        $pin_token   = $this->config['pin_token'];
        $session_id  = $this->config['session_id'];
        $iterator    = empty($this->iterator)
            ? microtime(true) * 100000
            : array_shift($this->iterator);

        //载入私钥
        $rsa = new RSA();
        if (! $rsa->loadKey($private_key)) {
            throw  new LoadPrivateKeyException('local private key error');
        }

        //使用 RSAES-OAEP + MGF1-SHA256 的方式，似乎只有这个 Phpseclib/Phpseclib 库来实现...
        $rsa->setHash("sha256");
        $rsa->setMGFHash("sha256");
        $key_bytes = $rsa->_rsaes_oaep_decrypt(base64_decode($pin_token), $session_id);

        //使用 私钥 加密 pin
        $pin_bytes = $pin.pack("P", time()).pack("P", $iterator);

        return $this->encrypt_openssl($pin_bytes, $key_bytes);
    }

    /**+
     * @param $msg
     * @param $key
     *
     * @return string
     */
    public function encrypt_openssl($msg, $key)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        $encrypted_message = openssl_encrypt($msg, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv.$encrypted_message);
    }

    /**
     * @param $payload
     * @param $key
     *
     * @return string
     */
    public function decrypt_openssl($payload, $key)
    {
        $raw  = base64_decode($payload);
        $iv   = substr($raw, 0, 16);
        $data = substr($raw, 16);

        return openssl_decrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}
