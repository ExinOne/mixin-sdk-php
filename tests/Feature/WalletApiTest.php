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

class WalletApiTest extends TestCase
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
azJ8VSwaLKX1HXI+ofRpWVWjHgOa1`sRcnKQxiIhG/LrHSVh6W6XpOPuibBotCZaP0
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

    public function test_it_can_create_address_success0()
    {
        $assetId   = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $publicKey = '0xb8d5A3F0e2118B1DcE5c17015F2435D9d6e76668';
        $label     = 'mixinSDK';
        $pin       = $this->mixinSDK->getConfig('default')['pin'];

        $res = $this->mixinSDK->wallet()->createAddress($assetId, $publicKey, $pin, $label);
        dump($res);
        self::assertInternalType('array', $res);

        $assetId      = '6cfe566e-4aad-470b-8c9a-2fd35b49c68d';
        $publicKey    = 'fdb737c5972ad8bf09fcc043d5e7bd08';
        $account_name = 'eoswithmixin';
        $pin          = $this->mixinSDK->getConfig('default')['pin'];

        $res = $this->mixinSDK->wallet()->createAddress($assetId, $publicKey, $pin, $account_name, true);
        dump($res);
        self::assertInternalType('array', $res);
        self::assertEquals('6cfe566e-4aad-470b-8c9a-2fd35b49c68d', $res['asset_id']);
    }

    public function test_it_can_read_addresses_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $res     = $this->mixinSDK->wallet()->readAddresses($assetId);
        dump($res);
        self::assertInternalType('array', $res);
        foreach ($res aS $address) {
            self::assertEquals($assetId, $address['asset_id']);
        }
    }

    public function test_it_can_read_address_success0()
    {
        $addressId = 'c627b5dd-3715-4477-8e84-822237e28d93';
        $res       = $this->mixinSDK->wallet()->readAddress($addressId);
        self::assertInternalType('array', $res);
        self::assertEquals($addressId, $res['address_id']);
    }

    public function test_it_can_delete_address_success0()
    {
        $addressId = 'e5c7523b-be90-4030-9d09-f92e7d06e4dd';
        $pin       = $this->mixinSDK->getConfig('default')['pin'];
        $res       = $this->mixinSDK->wallet()->deleteAddress($addressId, $pin);
        self::assertEmpty($res);
    }

    public function test_it_can_read_user_assets_success0()
    {
        $assets = $this->mixinSDK->wallet()->readAssets();
        dump($assets);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_read_user_asset_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $assets  = $this->mixinSDK->wallet()->readAsset($assetId);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_deposit_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $assets  = $this->mixinSDK->wallet()->deposit($assetId);
        dump($assets);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_withdrawal_success0()
    {
        $addressId = '7c89ac7c-1cd3-47f7-b025-b291cf02f719';
        $amount    = 0.01;
        $memo      = 'sdkfjklsdjfklsjdfkl';
        $pin       = $this->mixinSDK->getConfig('default')['pin'];

        $res = $this->mixinSDK->wallet()->withdrawal($addressId, $amount, $memo, $pin);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_transfer_success0()
    {
        $asset_id    = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $opponent_id = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $amount      = 0.01;
        $pin         = $this->mixinSDK->getConfig('default')['pin'];
        $memo        = 'sdkfjklsdjfklsjdfkl';

        $res = $this->mixinSDK->wallet()->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_verify_payment_success0()
    {
        $asset_id    = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $opponent_id = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $amount      = 0.01;
        $trace_id    = '75432bad-750f-4cbd-98a0-77015a5b20ce';

        $res = $this->mixinSDK->wallet()->verifyPayment($asset_id, $opponent_id, $amount, $trace_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_transfer_success0()
    {
        $trace_id = '75432bad-750f-4cbd-98a0-77015a5b20ce';

        $res = $this->mixinSDK->wallet()->readTransfer($trace_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_asset_fee_success0()
    {
        $asset_id = '965e5c6e-434c-3fa9-b780-c50f43cd955c';

        $res = $this->mixinSDK->wallet()->readAssetFee($asset_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_user_snapshots_success0()
    {
        $assetId = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $limit   = 2;
        $offset  = '2018-11-27T09:58:25.362528Z';
        $order   = 'ASC';

        $networkInfo0 = $this->mixinSDK->wallet()->readUserSnapshots($limit);
        dump($networkInfo0);
        self::assertInternalType('array', $networkInfo0);
        self::assertCount(2, $networkInfo0);

        $networkInfo1 = $this->mixinSDK->wallet()->readUserSnapshots($limit, $offset);
        dump($networkInfo1);
        self::assertInternalType('array', $networkInfo1);
        self::assertCount(2, $networkInfo1);

        $networkInfo2 = $this->mixinSDK->wallet()->readUserSnapshots($limit, $offset, $assetId);
        dump($networkInfo2);
        foreach ($networkInfo2 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo2);
        self::assertCount(2, $networkInfo2);

        $networkInfo3 = $this->mixinSDK->wallet()->readUserSnapshots($limit, $offset, $assetId, $order);
        dump($networkInfo3);
        foreach ($networkInfo3 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo3);
        self::assertCount(2, $networkInfo3);

        $networkInfo4 = $this->mixinSDK->wallet()->readUserSnapshots($limit, null, $assetId, $order);
        dump($networkInfo4);
        foreach ($networkInfo4 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo4);
        self::assertCount(2, $networkInfo4);

        $networkInfo5 = $this->mixinSDK->wallet()->readUserSnapshots(null, $offset, $assetId);
        dump($networkInfo5);
        foreach ($networkInfo5 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo5);
    }

    public function test_it_can_read_user_snapshot_success0()
    {
        $snapshot_id = '2081330a-2f74-4c3c-9663-538f3ba93a0d';

        $res = $this->mixinSDK->wallet()->readUserSnapshot($snapshot_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_search_assets_success0()
    {
        $q = 'EPC';

        $res = $this->mixinSDK->wallet()->searchAssets($q);
        dump($res);
        self::assertInternalType('array', $res);
    }
}
