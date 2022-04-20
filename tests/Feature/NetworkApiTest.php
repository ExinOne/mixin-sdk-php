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

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixinSDK = new MixinSDK(require './testKeys.php');
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

    public function test_it_can_read_multisig_asset_success0()
    {
        $res = $this->mixinSDK->network()->multisigAsset();
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

    public function test_it_can_search_assets_success0()
    {
        $q = 'EPC';

        $res = $this->mixinSDK->network()->searchAssets($q);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_chains(){
        $res = $this->mixinSDK->network()->chains();
        dump($res);
        self::assertIsArray($res);
    }
}
