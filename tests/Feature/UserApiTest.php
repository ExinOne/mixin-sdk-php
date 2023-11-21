<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-28
 * Time: 下午9:54
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\MixinSDK;
use ExinOne\MixinSDK\Utils\MixinService;
use ExinOne\MixinSDK\Utils\TIPService;
use PHPUnit\Framework\TestCase;

/**
 * 以下是已经顺利载入 config 后的 MixinSDK 实例发起的API操作
 *
 * Class UserApiTest
 *
 * @package ExinOne\MixinSDK\Tests\Feature
 */
class UserApiTest extends TestCase
{
    protected $mixin_sdk;

    protected $sub_user_tip_pin;

    protected $sub_user_safe_pin;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixin_sdk         = new MixinSDK(require 'test_keys_ed25519.php');
        $this->sub_user_tip_pin  = TIPService::createEd25519PrivateKey();
        $this->sub_user_safe_pin = TIPService::createEd25519PrivateKey();
    }

    protected function createTIPSubUser()
    {
        $info = $this->mixin_sdk->network()->createUser('test', 'Ed25519');

        $config = MixinService::formatConfigFromCreateUser($info);

        (new MixinSDK($config))
            ->pin()
            ->updatePin('', $this->sub_user_tip_pin);

        $config['pin'] = $this->sub_user_tip_pin;

        return $config;
    }

    // ReadProfile API
    public function test_it_can_read_now_user_profile_success0()
    {
        $profile = $this->mixin_sdk->user()->readProfile();
        self::assertArrayHasKey('user_id', $profile);
        self::assertArrayHasKey('identity_number', $profile);
        self::assertArrayHasKey('full_name', $profile);
        self::assertArrayHasKey('avatar_url', $profile);
        self::assertArrayHasKey('created_at', $profile);
        self::assertArrayHasKey('avatar_url', $profile);
        self::assertArrayHasKey('phone', $profile);
        self::assertArrayHasKey('code_id', $profile);
        self::assertArrayHasKey('code_url', $profile);
        self::assertNotEmpty($profile['user_id']);
        self::assertNotEmpty($profile['identity_number']);
        self::assertNotEmpty($profile['full_name']);
        self::assertNotEmpty($profile['avatar_url']);
        self::assertNotEmpty($profile['created_at']);
        self::assertNotEmpty($profile['avatar_url']);
        self::assertNotEmpty($profile['phone']);
        self::assertNotEmpty($profile['code_id']);
        self::assertNotEmpty($profile['code_url']);
    }

    // UpdateProfile API
    public function test_it_can_update_now_user_profile_and_avatar_success0()
    {
        $full_name = 'helloworld';
        $profile   = $this->mixin_sdk->user()->updateProfile($full_name);
        self::assertEquals($full_name, $profile['full_name']);

        $full_name     = 'walawalaha';
        $avatar_base64 = base64_encode(file_get_contents('/home/kurisucode/exin.cc/public/logo-96.png'));
        $profile       = $this->mixin_sdk->user()->updateProfile($full_name, $avatar_base64);
        self::assertEquals($full_name, $profile['full_name']);

        $full_name     = 'xcvsmixinap';
        $avatar_base64 = base64_encode(file_get_contents('/home/kurisu/Pictures/v2-8b02c7a7bfc60323551a0b1e81089980_r.jpg'));
        $profile       = $this->mixin_sdk->user()->updateProfile($full_name, $avatar_base64);
        self::assertEquals($full_name, $profile['full_name']);
    }

    // UpdatePreferences API
    public function test_it_can_update_now_user_proferences_success0()
    {
        $profile                     = $this->mixin_sdk->user()->readProfile();
        $receive_message_source0     = $profile['receive_message_source'];
        $accept_conversation_source0 = $profile['accept_conversation_source'];

        $profile                     = $this->mixin_sdk->user()->updatePreferences('EVERYBODY', 'EVERYBODY');
        $receive_message_source1     = $profile['receive_message_source'];
        $accept_conversation_source1 = $profile['accept_conversation_source'];
        self::assertEquals('EVERYBODY', $receive_message_source1);
        self::assertEquals('EVERYBODY', $accept_conversation_source1);

        $profile                     = $this->mixin_sdk->user()->updatePreferences('CONTACTS', 'CONTACTS');
        $receive_message_source2     = $profile['receive_message_source'];
        $accept_conversation_source2 = $profile['accept_conversation_source'];
        self::assertEquals('CONTACTS', $receive_message_source2);
        self::assertEquals('CONTACTS', $accept_conversation_source2);

        $this->mixin_sdk->user()->updatePreferences($receive_message_source0, $accept_conversation_source0);

    }

    // RotateQRCode API
    public function test_it_can_rotate_QRcode_success0()
    {
        $profile0 = $this->mixin_sdk->user()->rotateQRCode();
        $profile1 = $this->mixin_sdk->user()->rotateQRCode();
        self::assertNotEmpty($profile0['code_id'], $profile1['code_id']);
    }

    // ReadFriends API
    public function test_it_can_read_friends_success0()
    {
        $profile = $this->mixin_sdk->user()->readFriends();
        self::assertFalse(false);
    }

    public function test_register_safe_mainnet_success0()
    {
        $config = $this->createTIPSubUser();
        dump($config);
        dump($this->sub_user_tip_pin);
        dump($this->sub_user_safe_pin);
        $info   = (new MixinSDK($config))->user()->safeRegister($this->sub_user_safe_pin);
        self::assertArrayHasKey('has_safe', $info);
        self::assertTrue($info['has_safe']);
    }
}
