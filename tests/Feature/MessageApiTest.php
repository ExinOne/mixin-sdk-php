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

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixinSDK = new MixinSDK(require 'testKeys.php');
    }

    public function test_it_can_change_user_pin_code()
    {
        $nowPin = $this->mixinSDK->getConfig('default')['pin'];
        $newPin = '111111';
        //$res    = $this->mixinSDK->pin()->updatePin($newPin, $nowPin);
        //dd($res);
        //$res = $this->mixinSDK->pin()->verifyPin($nowPin);
        //dump($res);
        //dump($nowPin, $newPin);
        $res = $this->mixinSDK->pin()->updatePin($nowPin, $newPin);
        //dd($res);
        dump($res);
        $res = $this->mixinSDK->pin()->verifyPin($newPin);
        dump($res);
        $res = $this->mixinSDK->pin()->updatePin($newPin, $nowPin);
        dump($res);

        self::assertFalse(false);
    }
}
