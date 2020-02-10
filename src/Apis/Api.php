<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 下午2:01
 */

namespace ExinOne\MixinSDK\Apis;

use ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;
use GuzzleHttp\Client;
use Wrench\Protocol\Protocol;
use Wrench\Client as WSClient;

class Api
{
    use MixinSDKTrait;

    protected $config;

    protected $packageConfig;

    protected $useFunction;

    protected $endPointUrl;

    protected $endPointMethod;

    protected $iterator;

    protected $timeout = 20;

    protected $expire = 200;

    protected $is_return_access_token = false;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * Api constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->packageConfig = require(__DIR__ . '/../../config/config.php');
        $this->config        = $config;
        $this->httpClient    = new Client([
            'base_uri' => $this->packageConfig['base_uri'],
            'timeout'  => $this->timeout,
            'version'  => 1.3,
        ]);
    }

    /**
     * @param null  $body
     * @param null  $url
     * @param array $customizeHeaders
     * @param array $customizeRes
     *
     * @return array
     * @throws \Exception
     */
    public function res($body = null, $url = null, $customizeHeaders = [], $customizeRes = [])
    {
        // 编辑参数变成约定的格式
        // 请求的方法
        $method = $this->endPointMethod;

        // 请求目标的Url
        $url = empty($url)
            ? $this->endPointUrl
            : $url;

        // body
        $body = empty($body)
            ? null
            : json_encode($body);

        $auth_token = $this->getToken(strtoupper($method), $url, $body, $this->expire);

        // headers
        $headers = array_merge([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $auth_token,
        ], $customizeHeaders);

        if ($this->is_return_access_token) {
            return [
                'content'       => [],
                'customize_res' => [],
                'auth_token'    => $auth_token,
            ];
        }

        // 发起请求
        $method   = strtolower($method);
        $timeout  = $this->timeout;
        $response = $this->httpClient->$method($url, compact('headers', 'body', 'timeout'));

        // 获取内容
        return [
            'content'       => json_decode($response->getBody()->getContents(), true),
            'customize_res' => $customizeRes,
            'auth_token'    => $auth_token,
        ];
    }

    /**
     * @param $message
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function webSocketRes($message)
    {
        $wsClient = new WSClient('wss://blaze.mixin.one/', 'https://google.com');
        $wsClient->addRequestHeader('Authorization', 'Bearer ' . $this->getToken('GET', '/', ''));
        $wsClient->addRequestHeader('protocol', 'Mixin-Blaze-1');

        // 重试操作
        for ($i = 0; $i < 5; $i++) {
            try {
                $wsClient->connect();
                if (is_array($message[0] ?? 'e')) {
                    $messages = $message;
                    foreach ($messages as $v) {
                        $wsClient->sendData(gzencode(json_encode(array_shift($messages))), Protocol::TYPE_BINARY);
                    }
                } else {
                    $wsClient->sendData(gzencode(json_encode($message)), Protocol::TYPE_BINARY);
                }
                $response = $wsClient->receive()[0]->getPayload();
                break;
            } catch (\Throwable $e) {
                $wsClient->disconnect();
            } finally {
                $wsClient->disconnect();
            }
        }

        return [
            'content'       => json_decode(gzdecode($response), true),
            'customize_res' => [],
            'auth_token'    => null,
        ];
    }

    /**
     * @param $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param bool $raw
     */
    public function setRaw(bool $raw): void
    {
        $this->raw = $raw;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * @return mixed
     */
    public function getUseFunction()
    {
        return $this->useFunction;
    }

    /**
     * @param $useFunction
     */
    public function init($useFunction)
    {
        $this->useFunction    = $useFunction;
        $this->endPointUrl    = $this->packageConfig['endpoints'][camel2Underline($this->useFunction)]['url'] ?? null;
        $this->endPointMethod = $this->packageConfig['endpoints'][camel2Underline($this->useFunction)]['method'] ?? null;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @param int $expire
     */
    public function setExpire(int $expire): void
    {
        $this->expire = $expire;
    }

    /**
     * @param int $expire
     */
    public function setReturnAccessToken(bool $is_return_access_token): void
    {
        $this->is_return_access_token = $is_return_access_token;
    }

    /**
     * @param array $iterator
     */
    public function setIterator(array $iterator): void
    {
        $this->iterator = $iterator;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['config'];
    }

    public function __wakeup()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.mixin.one',
            'timeout'  => $this->timeout,
        ]);
    }
}
