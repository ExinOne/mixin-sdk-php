<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-28
 * Time: 下午8:25
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\MixinSDK;
use ExinOne\MixinSDK\Utils\Transaction\BigInteger;
use ExinOne\MixinSDK\Utils\Transaction\Input;
use ExinOne\MixinSDK\Utils\Transaction\Output;
use PHPUnit\Framework\TestCase;

use ExinOne\MixinSDK\Traits\MixinSDKTrait;

/**
 * 类基础功能测试
 *
 * Class BaseTest
 *
 * @package ExinOne\MixinSDK\Tests\Feature
 */
final class BaseTest extends TestCase
{
    use MixinSDKTrait;

    public function test_it_can_get_MixinSDK_object_success1()
    {
        // 获取对象的基础测试
        $mixinSDK = new MixinSDK();
        $this->assertContainsOnlyInstancesOf(MixinSDK::class, [$mixinSDK]);
    }

    // 测试new 出来的对象瞎 set config 会不会有问题
    public function test_it_can_get_MixinSDK_object_and_set_config_success0()
    {
        $config   = [
            'mixin_id'      => 1,
            'client_id'     => 1,
            'client_secret' => 1,
            'pin'           => 1,
            'pin_token'     => 1,
            'session_id'    => 1,
            'session_key'   => 1,
        ];
        $mixinSDK = new MixinSDK($config);
        self::assertArraySubset(['default' => $config,], $mixinSDK->config);

        $config2  = [
            'mixin_id'      => 2,
            'client_id'     => 2,
            'client_secret' => 2,
            'pin'           => 2,
            'pin_token'     => 2,
            'session_id'    => 2,
            'session_key'   => 2,
        ];
        $mixinSDK = new MixinSDK($config2);
        self::assertArraySubset($config2, $mixinSDK->getConfig('default'));

        $config3 = [
            'mixin_id'      => 3,
            'client_id'     => 3,
            'client_secret' => 3,
            'pin'           => 3,
            'pin_token'     => 3,
            'session_id'    => 3,
            'session_key'   => 3,
        ];
        $mixinSDK->setConfig('balabala', $config3);
        self::assertArraySubset([
            'default'  => $config2,
            'balabala' => $config3,
        ], $mixinSDK->config);
        self::assertArraySubset($config3, $mixinSDK->getConfig('balabala'));

        $this->assertContainsOnlyInstancesOf(MixinSDK::class, [$mixinSDK]);
    }

    //直接new对象时候是否有正确加载配置项
    public function test_it_can_get_MixinSDK_object_success_and_default_config_success0()
    {
        $mixinSDK = new MixinSDK();
        $mixinSDK->setConfig('default', [
            'mixin_id'      => 3,
            'client_id'     => 3,
            'client_secret' => 3,
            'pin'           => 3,
            'pin_token'     => 3,
            'session_id'    => 3,
            'session_key'   => 3,
        ]);

        $allConfig = $mixinSDK->getConfig();

        $this->assertArrayHasKey('default', $allConfig);
        $this->assertArrayHasKey('mixin_id', $allConfig['default']);
        $this->assertArrayHasKey('client_id', $allConfig['default']);
        $this->assertArrayHasKey('client_secret', $allConfig['default']);
        $this->assertArrayHasKey('pin', $allConfig['default']);
        $this->assertArrayHasKey('pin_token', $allConfig['default']);
        $this->assertArrayHasKey('session_id', $allConfig['default']);
        $this->assertArrayHasKey('session_key', $allConfig['default']);
        self::assertNotEmpty($allConfig['default']['mixin_id']);
        self::assertNotEmpty($allConfig['default']['client_id']);
        self::assertNotEmpty($allConfig['default']['client_secret']);
        self::assertNotEmpty($allConfig['default']['pin']);
        self::assertNotEmpty($allConfig['default']['pin_token']);
        self::assertNotEmpty($allConfig['default']['session_id']);
        self::assertNotEmpty($allConfig['default']['session_key']);
    }

    public function test_it_config_set_config_with_facades_class_success0()
    {
        $mixinSDK = new MixinSDK();

        $config = [
            'mixin_id'      => 3,
            'client_id'     => 3,
            'client_secret' => 3,
            'pin'           => 3,
            'pin_token'     => 3,
            'session_id'    => 3,
            'session_key'   => 3,
        ];

        $mixinSDK->setConfig('exinone', $config);
        self::assertArraySubset($config, $mixinSDK->getConfig('exinone'));
    }

    public function test_it_can_use_right_config()
    {
        $mixinSDK = new MixinSDK();
        // 使用use set config 的时候是否会正确的获取config
        $config = [
            'mixin_id'      => 4,
            'client_id'     => 4,
            'client_secret' => 4,
            'pin'           => 4,
            'pin_token'     => 4,
            'session_id'    => 4,
            'session_key'   => 4,
        ];
        $mixinSDK->use('user', $config);
        self::assertArraySubset($config, $mixinSDK->getConfig('user'));
        self::assertAttributeEquals('user', 'useConfigName', $mixinSDK->get());
    }

    // 测试有多个配置文件的时候是否可以正确运转
    public function test_it_can_use_before_config()
    {
        $mixinSDK = new MixinSDK();

        $user     = [
            'mixin_id'      => 4,
            'client_id'     => 4,
            'client_secret' => 4,
            'pin'           => 4,
            'pin_token'     => 4,
            'session_id'    => 4,
            'session_key'   => 4,
        ];
        $balabala = [
            'mixin_id'      => 5,
            'client_id'     => 5,
            'client_secret' => 5,
            'pin'           => 5,
            'pin_token'     => 5,
            'session_id'    => 5,
            'session_key'   => 5,
        ];
        $mixinSDK->setConfig('user', $user);
        $mixinSDK->setConfig('balabala', $balabala);

        $mixinContainer = $mixinSDK->use('user')->user();
        self::assertArraySubset($user, $mixinContainer->getDetailClass()->getConfig());

        $mixinContainer = $mixinSDK->use('balabala')->user();
        self::assertArraySubset($balabala, $mixinContainer->getDetailClass()->getConfig());
    }

    public function test_it_can_get_pay_url()
    {
        $mixinSDK = new MixinSDK(require 'testKeys.php');
        $a        = $mixinSDK->getPayUrl('ccc', 'asd', 'ddd', 'ee');
        dd($a);
    }

    public function test_it_can_build_raw()
    {
        $input0  = new Input("c6d0c7282624429b8e0dd9d19b6592fa", 0);
        $input1  = new Input("c6d0c7282624429b8e0dd9d19b6592fa", 1);
        $output0 = new Output(new BigInteger("0.000001"), ["c6d0c7282624429b8e0dd9d19b6592fa"],
            "c6d0c7282624429b8e0dd9d19b6592fa", "c6d0c7282624429b8e0dd9d19b6592fa");

        $rawString = $this->buildRaw("c6d0c7282624429b8e0dd9d19b6592fa", [$input0, $input1,], [$output0,], "xx");

        dump($rawString);
        self::assertNotEmpty($rawString);
    }
}
