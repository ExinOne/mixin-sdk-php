<?php

namespace ExinOne\MixinSDK;

use ExinOne\MixinSDK\Exceptions\ClassNotFoundException;
use ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException;
use ExinOne\MixinSDK\Exceptions\NotFoundConfigException;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;
use ExinOne\MixinSDK\Utils\TIPService;

/**
 * @method  Container user()
 * @method  Container pin()
 * @method  Container network()
 * @method  Container wallet()
 * @method  Container message()
 *
 * @see \ExinOne\MixinSDK\MixinSDK
 */
class MixinSDK
{
    /**
     */
    public $config;

    public $base_uri;

    public $timeout;

    protected $useConfigName = 'default';

    /**
     * @var bool
     */
    public $raw = false;

    public $iterator = null;

    /**
     * MixinSDK constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->useConfigName = 'default';
        if (! empty($config)) {
            if (is_array($config['keys'] ?? 'e')) {
                $this->config = $config['keys'];
            } else {
                $this->config[$this->useConfigName] = $config;
            }
            $this->base_uri = $config['base_uri'] ?? null;
            $this->timeout  = $config['timeout'] ?? null;
        }

    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return \ExinOne\MixinSDK\Container
     * @throws \ExinOne\MixinSDK\Exceptions\ClassNotFoundException
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function __call($name, $arguments)
    {
        $useConfigName       = $this->useConfigName;
        $this->useConfigName = 'default';

        $name  = ucfirst($name);
        $class = __NAMESPACE__.'\\Apis\\'.$name;

        // 作为包的入口， 根据 $name 返回相应的实例
        if (class_exists($class)) {
            return (new Container())
                ->setDetailClass(new $class($this->config[$useConfigName], $this->base_uri, $this->timeout));
        } else {
            throw new ClassNotFoundException("class \"$name\" not found, pleace check className");
        }
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        throw new \Exception('please use MixinClient Facade class');
    }

    /**
     * @param string      $name
     * @param array       $config
     * @param string|null $base_uri
     * @param int|null    $timeout
     * @return $this
     */
    public function use(string $name, array $config = [], string $base_uri = null, int $timeout = null)
    {
        $this->useConfigName = $name;
        if (! empty($config)) {
            $this->config[$name] = $config;
        }
        if ($base_uri) {
            $this->base_uri = $base_uri;
        }
        if ($timeout) {
            $this->timeout = $timeout;
        }

        return $this;
    }

    /**
     * @param string      $name
     * @param array       $config
     * @param string|null $base_uri
     * @param int|null    $timeout
     * @return $this
     */
    public function setConfig(string $name, array $config, string $base_uri = null, int $timeout = null)
    {
        $this->config[$name] = $config;
        if ($base_uri) {
            $this->base_uri = $base_uri;
        }
        if ($timeout) {
            $this->timeout = $timeout;
        }
        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \ExinOne\MixinSDK\Exceptions\NotFoundConfigException
     */
    public function getConfig(string $name = '')
    {
        if ($name != '') {
            if (in_array($name, array_keys($this->config))) {
                return $this->config[$name];
            }
            throw new NotFoundConfigException('config not found');
        }

        return $this->config;
    }

    /**
     * 获取该单例实例 句柄
     *
     * @return $this
     */
    public function get()
    {
        return $this;
    }

    /**
     * @param $client_id
     * @param $scope
     *
     * @return string
     */
    public function getOauthUrl($client_id, string $scope)
    {
        return "https://mixin.one/oauth/authorize?client_id=$client_id&scope=$scope&response_type=code";
    }

    /**
     * @param      $asset_id
     * @param      $amount
     * @param      $trace_id
     * @param      $memo
     * @param null $client_id
     *
     * @return string
     */
    public function getPayUrl($asset_id, $amount, $trace_id, $memo, $client_id = null)
    {
        if (empty($client_id)) {
            $client_id = $this->config[$this->useConfigName]['client_id'];
        }

        return "https://mixin.one/pay?recipient={$client_id}&asset={$asset_id}&amount={$amount}&trace={$trace_id}&memo={$memo}";
    }

    public static function createEd25519PrivateKey(): string
    {
        return TIPService::createEd25519PrivateKey();
    }
}
