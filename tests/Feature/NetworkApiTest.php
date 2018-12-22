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
                'client_secret' => '10d53145962b9c9e3d79dc3143f81f75e0a69df611a26590f4ddc6f735a934c5',
                'pin'           => '764227',
                'pin_token'     => 'Y393cKaYI9vx37y11vuyrQWcF60eRTAsxitiwWeO2qRI+KPui+6lSg8BXMhKNS2Gvby4TsDg4zfgy2aBTCiuLlVJEVNftRyWKB46iQOgfMyUUh+dKPwnklopAyJ2rrWQIxMWYy6o4x4x3g9PQRIlaH8qyQuJveDHtFbnNHSSU1M=',
                'session_id'    => '0b7f65c8-47db-4e9e-89dc-29e8eac80dc2',
                'private_key'   => <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCL/uFeTdkeXZEhj1fQl2Bew7Qi+AaadNCMXK9dO0MGcwNWTC7f
R0cEKVygbFc7CdMOUiiKnx+gdt5igklwdS459P4XJ7IX73ia3uf3G6vqCsX+FSaK
TfEBCDCWIPjEYiEvga46KLlmWjXtHH2P1vaOlvoBpY6eQCF/POCXVRGVjQIDAQAB
AoGAFNrkcesA9DAWJby35UAXwCZBWJBAU5QhWTeZfDcO1hAeKCXzOZnhr3IF9XQO
TqI5CcIdfgEUchAjMuOb0x/xwcIG95JMCZagYSITvr+cnjFzT3gHPM2VeyKUYzdA
hbhvTqhOS9QBKK/Am7PqWze/8vjROwaZ8aCRPENEPPP04WECQQDJBxJ5ogVzGvOx
xY9TrEv5Y67rmDbD/3gz6Js6uyJqVQdpLOUSn+p3VsR/kYcGSL3C5o+N2Wu/HWHe
MT63GeFlAkEAskdENSPEfF3efdjikSI1apOAtkSYlagzUcofW0zXg2ZWGSl19cuJ
IWA8IlyFXT9uRKOq8fMgWGcUNJYdiPf1CQJAfx2iuBkUuxli2ZmkLPO5QuSeukkQ
8FT9zE0cw0GL3JMR0Zba7zEB0R6juErsh7O9kp26TqcaM8o/lYGsN5n85QJBAJwA
iMsHVXSOn5b4JqphiOSN1l+ofuzWlrHHcNDv2NZ+wnCaO0KurHysPXLEC1+hldgy
b+/wlClHagLqKUYl1CECQQCPGG4PuDVsYaHiHUnsCAUv1g7XN4p0WdG66S416cnS
tqdCIpAkfQkVmgOzFdt3lhjNxEucUYv+PbcK31TtYxQ+
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
        self::assertInternalType('array', $res);
    }

    public function test_it_can_access_token_request_access_token_success0()
    {
        $code = '2c97aedf7e3bc90ddd9a399308caf8abb549e2c090fbdf0d2d08bf5a2c3f0389';

        $res = $this->mixinSDK->network()->requestAccessToken($code);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_access_token_get_info_success0()
    {
        $accessToken = 'eyJhbGciOiJSUzUxMVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV\VVVVVVVVVVVVVVVVVVVVvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv50PRk8DUHdcRhEp\nqMnNkT8a6GzHFKrGPQ';
        $res         = $this->mixinSDK->network()->accessTokenGetInfo($accessToken);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_test()
    {
        //dd($this->mixinSDK->network()->createAttachments());

        // 4d7a1c69-dd56-4c64-b160-61f234364cea
        //dd($this->mixinSDK->network()->searchUser('+8615002087196'));
        dd($this->mixinSDK->network()->webs('17d1c125-aada-46b0-897d-3cb2a29eb011'));

        dd($this->mixinSDK->network()->readConversations('d2c77eb2-e0b0-3e6e-bd66-2160437c5171'));
        dd($this->mixinSDK->network()->createConversations('CONTACT', [
            [
                'action'  => 'ADD',
                'role'    => '',
                'user_id' => '17d1c125-aada-46b0-897d-3cb2a29eb011',
            ],
        ]));

        [$userId, $recipientId] = ["The fog is getting thicker!1", "And Leon's getting laaarger!1"];

        [$minId, $maxId] = [$userId, $recipientId];
        if (strcmp($userId, $recipientId) > 0) {
            [$maxId, $minId] = [$userId, $recipientId];
        }
        $sum         = md5($minId.$maxId);
        $replacement = dechex((hexdec($sum[12].$sum[13]) & 0x0f) | 0x30);
        $sum         = substr_replace($sum, $replacement, 12, 2);

        $replacement = dechex((hexdec($sum[16].$sum[17]) & 0x3f) | 0x80);
        $sum         = substr_replace($sum, $replacement, 16, 2);

        $sum = Uuid::fromString($sum)->toString();

        dd($sum, 'cb4e3830-cee0-329c-ab2b-0ae35a88d28e', $sum == 'cb4e3830-cee0-329c-ab2b-0ae35a88d28e');

        dd($this->mixinSDK->uniqueConversationId());
        dump(hash('md5', 'The fog is getting thicker!And Leon\'s getting laaarger!'));
        dd(md5('The fog is getting thicker!And Leon\'s getting laaarger!'));
        var_dump(0x0 ."5f");
        dd(1);
        $a = hash('md5', 'The fog is getting thi0cker!And Leon\'s getting laaarger!');

        dd($a);
        dd(crypt('csdcsdcsdcsdc', 'sdfpljsdijhfusdijfoisdfc'));
        dd(md5("The fog is getting thicker!\r\nAnd Leons getting laaarger!", true));

        dd($this->mixinSDK->network()->createConversations('CONTACT', [
            [
                'action'  => 'ADD',
                'role'    => '',
                'user_id' => '17d1c125-aada-46b0-897d-3cb2a29eb011',
            ],
        ]));

        //$this->mixinSDK->network()->webs();

        dd(-1);
    }

    public function test_v()
    {
        //dd($this->mixinSDK->wallet()->readAssets());
        dd($this->mixinSDK->wallet()->readUserSnapshots());


        $a = $this->mixinSDK->network()->createUser("ccc");
        dd($a);
        
        //dd($this->mixinSDK->network()->searchUser('+8618588225667'));
        dd($this->mixinSDK->message()->sendBatchMessage(['17d1c125-aada-46b0-897d-3cb2a29eb011'], ['sdfsdfsdf']));

        dump($this->mixinSDK->message()->sendText('17d1c125-aada-46b0-897d-3cb2a29eb011', 'start'));

        for ($i = 0; $i < 50; ++$i) {
            dump($this->mixinSDK->message()->sendText('17d1c125-aada-46b0-897d-3cb2a29eb011', $i));
        }
    }
}
