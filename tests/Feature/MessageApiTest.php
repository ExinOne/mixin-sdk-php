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

    public function test_it_can_send_message()
    {
        $ids = [
            '17d1c125-aada-46b0-897d-3cb2a29eb011',
        ];
        $a = $this->mixinSDK->message()->sendBatchMessage($ids,'cccc');
        dd($a);
    }
}
