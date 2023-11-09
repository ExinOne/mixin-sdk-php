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

    protected $mixin_sdk;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixin_sdk = new MixinSDK(require 'test_keys_ed25519.php');
    }

    public function test_it_can_create_address_success0()
    {
        $asset_id    = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $destination = '0xb8d5A3F0e2118B1DcE5c17015F2435D9d6e76668';

        $res = $this->mixin_sdk->wallet()->createAddress($asset_id, $destination, null, 'test');
        dump($res);
        self::assertIsArray($res);
        self::assertEquals($res['destination'] ?? '', $destination);


        $asset_id    = '6cfe566e-4aad-470b-8c9a-2fd35b49c68d';
        $destination = 'mixin';
        $tag         = '920945298';
        $res         = $this->mixin_sdk->wallet()->createAddress($asset_id, $destination, null, 'test-eos', $tag);

        dump($res);
        self::assertIsArray($res);
        self::assertEquals($res['destination'] ?? '', $destination);
        self::assertEquals($res['tag'] ?? '', $tag);
    }

    public function test_it_can_read_addresses_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $res     = $this->mixin_sdk->wallet()->readAddresses($assetId);
        dump($res);
        self::assertInternalType('array', $res);
        foreach ($res as $address) {
            self::assertEquals($assetId, $address['asset_id']);
        }
    }

    public function test_it_can_read_address_success0()
    {
        $address_id = '8999cd05-d6d4-4c77-a957-3b5b5b089d68';
        $res        = $this->mixin_sdk->wallet()->readAddress($address_id);
        self::assertIsArray($res);
        self::assertEquals($address_id, $res['address_id']);
    }

    public function test_it_can_delete_address_success0()
    {
        $address_id = '8999cd05-d6d4-4c77-a957-3b5b5b089d68';
        $res        = $this->mixin_sdk->wallet()->deleteAddress($address_id);
        self::assertEmpty($res);
    }

    public function test_it_can_read_user_assets_success0()
    {
        $assets = $this->mixin_sdk->wallet()->readAssets();
        dump($assets);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_read_user_asset_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $assets  = $this->mixin_sdk->wallet()->readAsset($assetId);
        dump($assets);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_deposit_success0()
    {
        $assetId = 'c6d0c728-2624-429b-8e0d-d9d19b6592fa';
        $assets  = $this->mixin_sdk->wallet()->deposit($assetId);
        dump($assets);
        self::assertInternalType('array', $assets);
    }

    public function test_it_can_withdrawal_success0()
    {
        $address_id = '7c89ac7c-1cd3-47f7-b025-b291cf02f719';
        $amount     = 0.01;
        $memo       = 'sdkfjklsdjfklsjdfkl';
        $pin        = $this->mixin_sdk->getConfig('default')['pin'];

        $res = $this->mixin_sdk->wallet()->withdrawal($address_id, $amount, $memo, $pin);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_transfer_success0()
    {
        $asset_id    = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $opponent_id = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $amount      = 0.01;
        $pin         = $this->mixin_sdk->getConfig('default')['pin'];
        $memo        = 'sdkfjklsdjfklsjdfkl';

        $res = $this->mixin_sdk->wallet()->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_verify_payment_success0()
    {
        $asset_id    = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $opponent_id = '17d1c125-aada-46b0-897d-3cb2a29eb011';
        $amount      = 0.01;
        $trace_id    = '75432bad-750f-4cbd-98a0-77015a5b20ce';

        $res = $this->mixin_sdk->wallet()->verifyPayment($asset_id, $opponent_id, $amount, $trace_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_transfer_success0()
    {
        $trace_id = '75432bad-750f-4cbd-98a0-77015a5b20ce';

        $res = $this->mixin_sdk->wallet()->readTransfer($trace_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_asset_fee_success0()
    {
        $asset_id = '965e5c6e-434c-3fa9-b780-c50f43cd955c';

        $res = $this->mixin_sdk->wallet()->readAssetFee($asset_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

    public function test_it_can_read_user_snapshots_success0()
    {
        $assetId = '965e5c6e-434c-3fa9-b780-c50f43cd955c';
        $limit   = 2;
        $offset  = '2018-11-27T09:58:25.362528Z';
        $order   = 'ASC';

        $networkInfo0 = $this->mixin_sdk->wallet()->readUserSnapshots($limit);
        dump($networkInfo0);
        self::assertInternalType('array', $networkInfo0);
        self::assertCount(2, $networkInfo0);

        $networkInfo1 = $this->mixin_sdk->wallet()->readUserSnapshots($limit, $offset);
        dump($networkInfo1);
        self::assertInternalType('array', $networkInfo1);
        self::assertCount(2, $networkInfo1);

        $networkInfo2 = $this->mixin_sdk->wallet()->readUserSnapshots($limit, $offset, $assetId);
        dump($networkInfo2);
        foreach ($networkInfo2 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo2);
        self::assertCount(2, $networkInfo2);

        $networkInfo3 = $this->mixin_sdk->wallet()->readUserSnapshots($limit, $offset, $assetId, $order);
        dump($networkInfo3);
        foreach ($networkInfo3 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo3);
        self::assertCount(2, $networkInfo3);

        $networkInfo4 = $this->mixin_sdk->wallet()->readUserSnapshots($limit, null, $assetId, $order);
        dump($networkInfo4);
        foreach ($networkInfo4 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo4);
        self::assertCount(2, $networkInfo4);

        $networkInfo5 = $this->mixin_sdk->wallet()->readUserSnapshots(null, $offset, $assetId);
        dump($networkInfo5);
        foreach ($networkInfo5 as $networkInfo) {
            self::assertEquals($assetId, $networkInfo['asset_id']);
        }
        self::assertInternalType('array', $networkInfo5);
    }

    public function test_it_can_read_user_snapshot_success0()
    {
        $snapshot_id = '2081330a-2f74-4c3c-9663-538f3ba93a0d';

        $res = $this->mixin_sdk->wallet()->readUserSnapshot($snapshot_id);
        dump($res);
        self::assertInternalType('array', $res);
    }

}
