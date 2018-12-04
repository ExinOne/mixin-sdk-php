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

class NetworkApiTest extends TestCase
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

    public function test_it_can_read_user_success0()
    {
        $userInfo = $this->mixinSDK->network()->readUser('36d51948-4a0d-400a-80de-f71070e374c0');
        dump($userInfo);
        self::assertFalse(false);
    }

    public function test_it_can_read_users_success0()
    {

        $userUuids = [
            '36d51948-4a0d-400a-80de-f71070e374c0',
            '17d1c125-aada-46b0-897d-3cb2a29eb011',
        ];
        $userInfos = $this->mixinSDK->network()->readUsers($userUuids);
        dump($userInfos);

        self::assertInternalType('array', $userInfos);
        foreach ($userInfos as $item) {
            self::assertInternalType('array', $item);
        }
    }

    public function test_it_can_search_user_success0()
    {
        $searchInfo = [
            7000101528,
        ];

        foreach ($searchInfo as $item) {
            $userInfo = $this->mixinSDK->network()->searchUser($item);
            dump($userInfo);
            self::assertInternalType('array', $userInfo);
        }
    }

    public function test_it_can_read_network_asset_success0()
    {
        $assetIds = [
            'c6d0c728-2624-429b-8e0d-d9d19b6592fa',
            '43d61dcd-e413-450d-80b8-101d5e903357',
        ];

        foreach ($assetIds as $assetId) {
            $assetInfo = $this->mixinSDK->network()->readNetworkAsset($assetId);
            dump($assetInfo);
            self::assertInternalType('array', $assetInfo);
        }
    }

    public function test_it_can_read_network_snapshots_success0()
    {

        $limit   = 2;
        $offset  = '2018-11-27T09:58:25.362528Z';
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $order   = 'ASC';

        $networkInfo0 = $this->mixinSDK->network()->readNetworkSnapshots($limit);
        dump($networkInfo0);
        self::assertInternalType('array', $networkInfo0);
        self::assertCount(2, $networkInfo0);

        $networkInfo1 = $this->mixinSDK->network()->readNetworkSnapshots($limit, $offset);
        dump($networkInfo1);
        self::assertInternalType('array', $networkInfo1);
        self::assertCount(2, $networkInfo1);

        $networkInfo2 = $this->mixinSDK->network()->readNetworkSnapshots($limit, $offset, $assetId);
        dump($networkInfo2);
        foreach ($networkInfo2 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset']['asset_id']);
        }
        self::assertInternalType('array', $networkInfo2);
        self::assertCount(2, $networkInfo2);

        $networkInfo3 = $this->mixinSDK->network()->readNetworkSnapshots($limit, $offset, $assetId, $order);
        dump($networkInfo3);
        foreach ($networkInfo3 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset']['asset_id']);
        }
        self::assertInternalType('array', $networkInfo3);
        self::assertCount(2, $networkInfo3);

        $networkInfo4 = $this->mixinSDK->network()->readNetworkSnapshots($limit, null, $assetId, $order);
        dump($networkInfo4);
        foreach ($networkInfo4 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset']['asset_id']);
        }
        self::assertInternalType('array', $networkInfo4);
        self::assertCount(2, $networkInfo4);

        $networkInfo5 = $this->mixinSDK->network()->readNetworkSnapshots(null, $offset, $assetId);
        dump($networkInfo5);
        foreach ($networkInfo5 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset']['asset_id']);
        }
        self::assertInternalType('array', $networkInfo5);
    }

    public function test_it_can_read_network_snapshot_success0()
    {
        $arr = [
            '520118d0-e8d2-41c1-9066-9f499228aa31',
            '1167ccbf-4e7e-4265-8e99-d5a39b2e2cf8',
        ];
        foreach ($arr as $snapshotId) {
            $snapshotInfo = $this->mixinSDK->network()->readNetworkSnapshot($snapshotId);
            dump($snapshotInfo);
            self::assertEquals($snapshotId, $snapshotInfo['snapshot_id']);
        }
    }

    public function test_it_can_create_user_success0()
    {
        $name = 'balaslslsdfjkl';
        $res  = $this->mixinSDK->network()->createUser($name);
        dump($res);
        self::assertEquals($name, $res['full_name']);
        self::assertArrayHasKey('priKey', $res);
        self::assertArrayHasKey('pubKey', $res);
    }

    public function test_it_can_read_external_transaction_success0()
    {
        //TODO
    }

    public function test_it_can_create_attachments_success0()
    {
        $res = $this->mixinSDK->network()->createAttachments();
        dump($res);
        self::assertArrayHasKey('upload_url', $res);
        self::assertArrayHasKey('view_url', $res);
        self::assertNotEmpty($res['upload_url']);
        self::assertNotEmpty($res['view_url']);
    }

    public function test_it_can_read_mixin_network_chains_sync_status_success0()
    {
        $res = $this->mixinSDK->network()->mixinNetworkChainsSyncStatus();
        dump($res);
        self::assertArrayHasKey('chains', $res);
    }

    public function test_it_can_read_top_asset_success0()
    {
        $res = $this->mixinSDK->network()->topAsset();
        dump($res);
        self::assertArrayHasKey('chains', $res);
    }
}
