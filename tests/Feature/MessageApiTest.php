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
    protected $main_uuid;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixinSDK = new MixinSDK(require './testKeys.php');
        $this->main_uuid = 'f0e25d0c-fc7b-4fcc-a9f0-760ee7b61ee3';
    }

    public function test_it_can_send_text()
    {
        $id  = $this->main_uuid;
        $res = $this->mixinSDK->message()->sendText($id, 'sdk send test');
        dump($res);
        self::assertIsArray($res);
    }

    public function test_it_can_send_contact()
    {
        $id  = $this->main_uuid;
        $res = $this->mixinSDK->message()->sendContact($id, $id);
        dump($res);
        self::assertIsArray($res);
    }

    public function test_it_can_send_app_button_group()
    {
        $id  = $this->main_uuid;
        $res = $this->mixinSDK->message()->sendAppButtonGroup($id, [
            [
                'label'  => 'hello',
                'color'  => '#ABABAB',
                'action' => 'https://mixin.one'
            ]
        ]);
        dump($res);
        self::assertIsArray($res);
    }

    public function test_it_can_send_app_card()
    {
        $id  = $this->main_uuid;
        $res = $this->mixinSDK->message()->sendAppCard($id, [
                'icon_url'    => 'https://mixin.one/assets/98b586edb270556d1972112bd7985e9e.png',
                'title'       => 'Mixin',
                'description' => 'A free and lightning fast peer-to-peer transactional network for digital assets.',
                'action'      => 'https://mixin.one'
            ]
        );
        dump($res);
        self::assertIsArray($res);
    }

    public function test_it_can_send_batch_message()
    {
        $ids = [$this->main_uuid];
        $res = $this->mixinSDK->message()->sendBatchMessage($ids, 'A free and lightning fast peer-to-peer transactional network for digital assets.', true);
        $res2 = $this->mixinSDK->message()->sendBatchMessage($ids, ['A free and lightning fast peer-to-peer transactional network for digital assets.']);
        dump($res,$res2);
        self::assertIsArray($res);
    }

    public function test_send_batch_message_with_bath_type(){
        $ids = [$this->main_uuid, $this->main_uuid];
        $test_button_group = json_encode([
            [
                'label'  => 'test label 1',
                'color'  => '#6777FF',
                'action' => 'https://www.google.com',
            ],
            [
                'label'  => 'test label 2',
                'color'  => '#6777FF',
                'action' => 'https://www.github.com',
            ]

        ]);
        $res = $this->mixinSDK->message()->sendBatchMessage($ids, ['batch type send test websocket', $test_button_group],false, ['PLAIN_TEXT', 'APP_BUTTON_GROUP']);
        $res2 = $this->mixinSDK->message()->sendBatchMessage($ids, ['batch type send test http', $test_button_group],true, ['PLAIN_TEXT', 'APP_BUTTON_GROUP']);

        dump(['res' => $res, 'res2' => $res2]);
        self::assertIsArray($res);

    }
}
