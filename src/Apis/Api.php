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

    protected $base_uri;

    protected $expire = 200;

    protected $aud = null;

    protected $is_return_access_token = false;

    protected $http_async = false;

    protected $http_on_resolve = null;

    protected $http_on_reject = null;

    /**
     * @var Client
     */
    protected $http_client;

    /**
     * Api constructor.
     *
     * @param        $config
     * @param string $base_uri
     * @param int    $timeout
     */
    public function __construct($config, string $base_uri = null, int $timeout = null)
    {
        $this->packageConfig = require(__DIR__.'/../../config/config.php');

        $this->base_uri = $base_uri ?: $this->packageConfig['base_uri'];
        if ($timeout) {
            $this->timeout = $timeout;
        }
        $this->config      = $config;
        $this->http_client = new Client([
            'base_uri' => $this->base_uri,
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

        $auth_token = $this->getToken(strtoupper($method), $url, $body, $this->expire, 'FULL', $this->aud);

        // headers
        $headers = array_merge([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$auth_token,
        ], $customizeHeaders);

        if ($this->is_return_access_token) {
            return [
                'content'       => [],
                'customize_res' => [],
                'auth_token'    => $auth_token,
                'promise'       => null,
            ];
        }

        // 发起请求
        $method  = strtolower($method);
        $timeout = $this->timeout;

        if (! $this->http_async) {
            $response = $this->http_client->$method($url, compact('headers', 'body', 'timeout'));
            $content  = json_decode($response->getBody()->getContents(), true);
            $_headers = $response->getHeaders();
            $promise  = null;
        } else {
            $promise  = $this->http_client
                ->requestAsync($method, $url, compact('headers', 'body', 'timeout'))
                ->then($this->http_on_resolve, $this->http_on_reject);
            $content  = [];
            $_headers = [];
        }

        // 获取内容
        return [
            'content'       => $content,
            'headers'       => $_headers,
            'customize_res' => $customizeRes,
            'auth_token'    => $auth_token,
            'promise'       => $promise,
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
        $wsClient->addRequestHeader('Authorization', 'Bearer '.$this->getToken('GET', '/', ''));
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

        if (empty($response)) {
            $wsClient->disconnect();
        }

        return [
            'content'       => json_decode(gzdecode($response), true),
            'headers'       => [],
            'customize_res' => [],
            'auth_token'    => null,
            'promise'       => null,
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
     * @param string $aud
     */
    public function setAud(string $aud): void
    {
        $this->aud = $aud;
    }

    /**
     * @param bool $is_return_access_token
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
     * @param Client|null   $http_client
     * @param \Closure|null $on_resolve
     * @param \Closure|null $on_reject
     * @return void
     */
    public function setHttpAsync(Client $http_client = null, \Closure $on_resolve = null, \Closure $on_reject = null)
    {
        if ($http_client) {
            $this->http_async      = true;
            $this->http_client     = $http_client;
            $this->http_on_resolve = $on_resolve;
            $this->http_on_reject  = $on_reject;
        } else {
            $this->http_async = false;
        }
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->http_client;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['config'];
    }
}
