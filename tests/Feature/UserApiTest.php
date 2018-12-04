<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-28
 * Time: 下午9:54
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\MixinSDK;
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
    protected $mixinSDK;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixinSDK = new MixinSDK([
                'mixin_id'      => '7000101633',
                'client_id'     => '982afd4e-92dd-4430-98cf-d308442ea04d',
                'client_secret' => 'd014902f7f1097e55ad3da1f995e6dd467e4d78aa6ae602d953d35393daed58f',
                'pin'           => '874489',
                'pin_token'     => 'aly/VB9UTQx4pUBCGiENxohns49xcKmGhS9rRBoRBO+BlXo6ts3sBInTUMrpt8ksOPHhIFmNVf/s6dzQYi8ed0LzraxtjirItJprm/wKbjA3ec+J7WWtFlGT/H55iwdx7WA6b2DgA2AHpvTVws6OUbSuYKdE2HJUfNWaSaI4UGE=',
                'session_id'    => '70f74a4a-a46a-4c54-9174-b0e1135f4aa0',
                'private_key'   => <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQCp3JLKtRychlbqyrahZJyVyt7UhyE0V0SsMbKoPGV8dVL+qJGh
QGW/dD3vDskDmevyhOie6CcS1eOvkTS1hab2gVBQwWgZb8YmAxFryb5IZrksZk9u
5eTabdkjalTKHq4C90PEGG622dPARy//7JQTsVNNQYJeXwOWW4oShj1WhwIDAQAB
AoGAakqeiL5AgyoFZbMoCWJeIdXrDm7otkoNrPsEYwY4M2NvZe+yAYe8o8tnnhpQ
azJ8VSwaLKX1HXI+ofRpWVWjHgOasRcnKQxiIhG/LrHSVh6W6XpOPuibBotCZaP0
uueCv/r09Eym3urtV76SALdkfZA+A77aCTB73aKFJFtoCAECQQDmGL8zYqd5w5vd
yOnrvRb2XeMHq/3BQMT7kd6/ZLg7MwDJikpuN7yAwV66S9Yvw9BnFungKNGlzpXq
ZIGztyyHAkEAvPvjF/0hCaDrIMS3JVk/naUVZofRbsnRakhC6ut7i61mcg9sy6qm
43npuOBsDEJOH0dJmAWdSZlkFJelXm0GAQJAScPROBX+fsi45UcNxuddvymmKMV4
mkW7YLMI5+7QKRpWvEW7Ss5Pfi9/wNWjGrj5zLLJ03UCkNdDtFr4QbcNbQJAYx5l
pF5SJp+s0tn6CO+/aup7x/PyR344hNrzpgzuFntS4P3wHP4bW/HEQQAMC333RXZ5
Re+j6Ec4c4h55oWeAQJARzVUlVVTvohhw3cIhejfsyIbdbPk48UDTIlnSGNORlyY
vcFMTWHvd8ZzDXxQzd2MZIH6TVBvPohY6LOOQPtDdw==
-----END RSA PRIVATE KEY-----
EOF
                ,  //import your private_key
            ]
        );
    }

    // ReadProfile API
    public function test_it_can_read_now_user_profile_success0()
    {
        $profile = $this->mixinSDK->user()->readProfile();
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
        $profile   = $this->mixinSDK->user()->updateProfile($full_name);
        self::assertEquals($full_name, $profile['full_name']);

        $full_name     = 'walawalaha';
        $avatar_base64 = base64_encode(file_get_contents('/home/kurisucode/exin.cc/public/logo-96.png'));
        $profile       = $this->mixinSDK->user()->updateProfile($full_name, $avatar_base64);
        self::assertEquals($full_name, $profile['full_name']);

        $full_name     = 'xcvsmixinap';
        $avatar_base64 = base64_encode(file_get_contents('/home/kurisu/Pictures/v2-8b02c7a7bfc60323551a0b1e81089980_r.jpg'));
        $profile       = $this->mixinSDK->user()->updateProfile($full_name, $avatar_base64);
        self::assertEquals($full_name, $profile['full_name']);
    }

    // UpdatePreferences API
    public function test_it_can_update_now_user_proferences_success0()
    {
        $profile                     = $this->mixinSDK->user()->readProfile();
        $receive_message_source0     = $profile['receive_message_source'];
        $accept_conversation_source0 = $profile['accept_conversation_source'];

        $profile                     = $this->mixinSDK->user()->updatePreferences('EVERYBODY', 'EVERYBODY');
        $receive_message_source1     = $profile['receive_message_source'];
        $accept_conversation_source1 = $profile['accept_conversation_source'];
        self::assertEquals('EVERYBODY', $receive_message_source1);
        self::assertEquals('EVERYBODY', $accept_conversation_source1);

        $profile                     = $this->mixinSDK->user()->updatePreferences('CONTACTS', 'CONTACTS');
        $receive_message_source2     = $profile['receive_message_source'];
        $accept_conversation_source2 = $profile['accept_conversation_source'];
        self::assertEquals('CONTACTS', $receive_message_source2);
        self::assertEquals('CONTACTS', $accept_conversation_source2);

        $this->mixinSDK->user()->updatePreferences($receive_message_source0, $accept_conversation_source0);

    }

    // RotateQRCode API
    public function test_it_can_rotate_QRcode_success0()
    {
        $profile0 = $this->mixinSDK->user()->rotateQRCode();
        $profile1 = $this->mixinSDK->user()->rotateQRCode();
        self::assertNotEmpty($profile0['code_id'], $profile1['code_id']);
    }

    // ReadFriends API
    public function test_it_can_read_friends_success0()
    {
        $profile = $this->mixinSDK->user()->readFriends();
        self::assertFalse(false);
    }
}
