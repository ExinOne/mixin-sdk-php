<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-29
 * Time: 下午3:10
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\MixinSDK;
use PHPUnit\Framework\TestCase;


class MessageApiTest extends TestCase
{
    protected $mixinSDK;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixinSDK = new MixinSDK(require './testKeys.php');
    }

    public function test_it_can_send_text()
    {
        $id  = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $res = $this->mixinSDK->message()->sendText($id, 'sdk send test');
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_send_contact()
    {
        $id  = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $res = $this->mixinSDK->message()->sendContact($id, $id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_send_app_button_group()
    {
        $id  = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $res = $this->mixinSDK->message()->sendAppButtonGroup($id, [
            [
                'label'  => 'hello',
                'color'  => '#ABABAB',
                'action' => 'https://mixin.one'
            ]
        ]);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_send_app_card()
    {
        $id  = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $res = $this->mixinSDK->message()->sendAppCard($id, [
                'icon_url'    => 'https://mixin.one/assets/98b586edb270556d1972112bd7985e9e.png',
                'title'       => 'Mixin',
                'description' => 'A free and lightning fast peer-to-peer transactional network for digital assets.',
                'action'      => 'https://mixin.one'
            ]
        );
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_send_batch_message()
    {
        $ids = ['17d1c125-aada-46b0-897d-3cb2a29eb011'];
        $res = $this->mixinSDK->message()->sendBatchMessage($ids, 'A free and lightning fast peer-to-peer transactional network for digital assets.');
        $res2 = $this->mixinSDK->message()->sendBatchMessage($ids, ['A free and lightning fast peer-to-peer transactional network for digital assets.']);
        dump($res,$res2);
        self::assertInternalType('array', $res);
    }
}
