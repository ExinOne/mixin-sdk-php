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

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var \Wrench\Client
     */
    protected $wsClient;

    /**
     * Api constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->packageConfig = require(__DIR__.'/../../config/config.php');
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

        // headers
        $headers = array_merge([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$this->getToken(strtoupper($method), $url, $body),
        ], $customizeHeaders);

        // 发起请求
        $method   = strtolower($method);
        $response = $this->httpClient->$method($url, compact('headers', 'body'));

        // 获取内容
        return [
            'content'       => json_decode($response->getBody()->getContents(), true),
            'customize_res' => $customizeRes,
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
        // TODO 这里需要优化
        // 重试操作
        for ($i = 0; $i < 5; $i++) {
            try {
                $this->wsClient->connect();
                if (is_array($message[0] ?? 'e')) {
                    $messages = $message;
                    foreach ($messages as $v) {
                        $this->wsClient->sendData(gzencode(json_encode(array_shift($messages))), Protocol::TYPE_BINARY);
                    }
                } else {
                    $this->wsClient->sendData(gzencode(json_encode($message)), Protocol::TYPE_BINARY);
                }
                $response = $this->wsClient->receive()[0]->getPayload();
                break;
            } catch (\Error $e) {
                $this->wsClient->disconnect();
            }
        }

        $this->wsClient->disconnect();

        return [
            'content'       => json_decode(gzdecode($response), true),
            'customize_res' => [],
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
