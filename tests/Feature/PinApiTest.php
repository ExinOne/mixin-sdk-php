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

class PinApiTest extends TestCase
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
