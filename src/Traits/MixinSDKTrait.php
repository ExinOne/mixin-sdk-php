<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-8-16
 * Time: 下午11:08
 */

namespace ExinOne\MixinSDK\Traits;

use Base64Url\Base64Url;
use ExinOne\MixinSDK\Exceptions\InternalErrorException;
use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;
use ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException;
use ExinOne\MixinSDK\Utils\Transaction\Helper;
use ExinOne\MixinSDK\Utils\Transaction\Input;
use ExinOne\MixinSDK\Utils\Transaction\Output;
use Firebase\JWT\JWT;
use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Easy\Build;
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
     * @param        $method
     * @param        $uri
     * @param        $body
     *
     * @param int    $expire
     * @param string $scope
     * @param string $aud
     *
     * @return string
     */
    public function getToken($method, $uri, $body, $expire = 200, $scope = 'FULL', $aud = null)
    {
        $token = [
            "uid" => $this->config['client_id'],
            "sid" => $this->config['session_id'],
            "iat" => time(),
            "exp" => time() + $expire,
            "jti" => Uuid::uuid4()->toString(),
            "sig" => bin2hex(hash('sha256', $method.$uri.$body, true)),
            'scp' => $scope,
        ];

        if ($aud) {
            $token['aud'] = $aud;
        }

        $algorithm = $this->getKeyAlgorithm($this->config['private_key']);
        if ($algorithm === 'Ed25519') {
            $key_raw = Base64Url::decode($this->config['private_key']);
            $jwk     = JWKFactory::createFromValues(
                [
                    "kty" => "OKP",
                    "crv" => "Ed25519",
                    // seed
                    "d"   => Base64Url::encode(substr($key_raw, 0, 32)),
                    // public
                    "x"   => Base64Url::encode(substr($key_raw, 32)),
                ]
            );

            $jws = Build::jws();
            foreach ($token as $key => $value) {
                $jws = $jws->claim($key, $value);
            }
            // ed25519
            $jwt = (string)$jws->alg('EdDSA')->sign($jwk);
        } else {
            $jwt = JWT::encode($token, $this->config['private_key'], $algorithm);
        }

        return $jwt;
    }

    private function getKeyAlgorithm(string $key)
    {
        if (preg_match('/PRIVATE KEY/', $key)) {
            return 'RS512';
        }

        return 'Ed25519';
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
     * @return array
     */
    public function generateEdDSAKey()
    {
        $key     = JWKFactory::createOKPKey('Ed25519');
        $pri_key = Base64Url::encode(Base64Url::decode($key->get('d')).Base64Url::decode($key->get('x')));
        $pub_key = $key->get('x');

        return [$pri_key, $pub_key, $pub_key];
    }

    /**
     * @param string $pin 六位数字，或者是经过pack("J", 1)处理的TIP PIN的公钥，或者是TIP PIN签名的内容
     *
     * @return string
     * @throws LoadPrivateKeyException
     */
    public function encryptPin(string $pin)
    {
        if (! $pin) {
            throw new LoadPrivateKeyException('missing pin');
        }
        $private_key = $this->config['private_key'];
        $pin_token   = $this->config['pin_token'];
        $session_id  = $this->config['session_id'];
        $iterator    = empty($this->iterator)
            ? microtime(true) * 100000
            : array_shift($this->iterator);

        $algorithm = $this->getKeyAlgorithm($private_key);
        if ($algorithm === 'Ed25519') {
            $key_raw   = Base64Url::decode($private_key);
            $public    = Base64Url::decode($pin_token);
            $curve     = sodium_crypto_sign_ed25519_sk_to_curve25519($key_raw);
            $key_bytes = sodium_crypto_scalarmult($curve, $public);
        } else {
            //载入私钥
            $rsa = new RSA();
            if (! $rsa->loadKey($private_key)) {
                throw  new LoadPrivateKeyException('local private key error');
            }

            //使用 RSAES-OAEP + MGF1-SHA256 的方式，似乎只有这个 Phpseclib/Phpseclib 库来实现...
            $rsa->setHash("sha256");
            $rsa->setMGFHash("sha256");
            $key_bytes = $rsa->_rsaes_oaep_decrypt(base64_decode($pin_token), $session_id);
        }

        //使用 私钥 加密 pin
        $pin_bytes = $pin.pack("P", time()).pack("P", $iterator);

        return $this->encrypt_openssl($pin_bytes, $key_bytes);
    }

    public function encryptTIPPin(string $pin, string $action, ...$params)
    {
        if (strlen($pin) > 6) {
            $context = hash_init('sha256');
            hash_update($context, $action);
            foreach ($params as $p) {
                if (! empty($p)) {
                    hash_update($context, (string)$p);
                }
            }
            // true表示返回原始二进制数据
            $hash = hash_final($context, true);
            $sig  = self::signWithEd25519($pin, $hash);
        } else {
            $sig = $pin;
        }
        return $this->encryptPin($sig);
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

    /**
     * @param string $userId
     * @param string $recipientId
     *
     * @return string
     */
    public function uniqueConversationId(string $userId, string $recipientId): string
    {
        [$minId, $maxId] = [$userId, $recipientId];
        if (strcmp($userId, $recipientId) > 0) {
            [$maxId, $minId] = [$userId, $recipientId];
        }
        $sum         = md5($minId.$maxId);
        $replacement = dechex((hexdec($sum[12].$sum[13]) & 0x0f) | 0x30);
        $sum         = substr_replace($sum, $replacement, 12, 2);

        $replacement = dechex((hexdec($sum[16].$sum[17]) & 0x3f) | 0x80);
        $sum         = substr_replace($sum, $replacement, 16, 2);

        return Uuid::fromString($sum)->toString();
    }

    /**
     * @param string   $assetId
     * @param Input[]  $inputs
     * @param Output[] $outputs
     * @param string   $memo
     * @param int      $version
     *
     * @return string
     * @throws InvalidInputFieldException
     */
    public function buildRaw(string $assetId, array $inputs, array $outputs, string $memo, int $version = 0x01): string
    {
        // 大量的字段 和 类型检查
        if (empty($version)) {
            $version = 0x01;
        } else if (empty($assetId)) {
            throw new InvalidInputFieldException("func 'BuildRaw' need assetUuid, but your assetUuid param is empty");
        } else if (count($inputs) === 0) {
            throw new InvalidInputFieldException("func 'BuildRaw' need \$inputs not empty!");
        } else if (count($outputs) === 0) {
            throw new InvalidInputFieldException("func 'BuildRaw' need \$outputs not empty!");
        }

        // 检查 $inputs 和 $outputs 的类型
        foreach ($inputs as $input) {
            if (! $input instanceof Input) {
                throw new InvalidInputFieldException("\$inputs must use 'TransactionInput' object");
            }
        }
        foreach ($outputs as $output) {
            if (! $output instanceof Output) {
                throw new InvalidInputFieldException("\$outputs must use 'TransactionOutput' object");
            }
        }

        $multisigData = [
            'version' => 0x01,
            'asset'   => $assetId,
            'inputs'  => $inputs,
            'outputs' => $outputs,
            'extra'   => bin2hex($memo),
        ];

        return Helper::buildTransaction($multisigData);
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

    public static function formatConfigFromCreateUser(array $info): array
    {
        return [
            'mixin_id'      => $info['identity_number'],
            'client_id'     => $info['user_id'],
            'client_secret' => '',
            'pin'           => '',
            'pin_token'     => $info['pin_token_base64'],
            'session_id'    => $info['session_id'],
            'private_key'   => $info['priKey'],
        ];
    }
}
