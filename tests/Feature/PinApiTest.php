<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-29
 * Time: 下午3:10
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\MixinSDK;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;
use PHPUnit\Framework\TestCase;

class PinApiTest extends TestCase
{
    protected $mixin_sdk;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixin_sdk = new MixinSDK(require 'test_keys_ed25519.php');
    }

    public function test_it_can_change_user_pin_code()
    {
        $user = $this->mixin_sdk->network()->createUser('test_change_pin', 'Ed25519');
        dump($user);
        $config    = MixinSDKTrait::formatConfigFromCreateUser($user);
        $digit_pin = '114514';
        $res       = $this->mixin_sdk->use('test_change_pin', $config)->pin()->updatePin('', $digit_pin);
        dump($res);
        $res = $this->mixin_sdk->use('test_change_pin', $config)->pin()->verifyPin($digit_pin);
        dump($res);
        $tip_pin = MixinSDKTrait::createEd25519PrivateKey();
        dump($tip_pin);
        $res = $this->mixin_sdk->use('test_change_pin', $config)->pin()->updatePin($digit_pin, $tip_pin);
        dump($res);
        $res = $this->mixin_sdk->use('test_change_pin', $config)->pin()->verifyPin($tip_pin);
        dump($res);
        self::assertEquals($res['user_id'], $config['client_id']);
    }
}
