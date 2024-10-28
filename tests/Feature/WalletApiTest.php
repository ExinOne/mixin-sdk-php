<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-29
 * Time: 下午3:10
 */

namespace ExinOne\MixinSDK\Tests\Feature;

use Brick\Math\BigDecimal;
use ExinOne\MixinSDK\MixinSDK;
use ExinOne\MixinSDK\Utils\MixinService;
use ExinOne\MixinSDK\Utils\TIPService;
use ExinOne\MixinSDK\Utils\TransactionV5\Encoder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class WalletApiTest extends TestCase
{

    protected $mixin_sdk;

    protected $mixin_sdk_safe;

    protected $mixin_sdk_server_public;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mixin_sdk               = new MixinSDK(require 'test_keys_ed25519.php');
        $this->mixin_sdk_safe          = new MixinSDK(require 'test_safe_keys.php');
        // $this->mixin_sdk_server_public = new MixinSDK(require 'test_keys_server_public.php');
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
        $opponent_id = '2ef7c59f-bf5c-41b3-bb67-2d2c4d6b925c';
        $amount      = "0.010000";
        $memo        = 'sdkfjklsdjfklsjdfkl';

        $res = $this->mixin_sdk->wallet()->transfer($asset_id, $opponent_id, null, $amount, $memo);
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

    public function test_safe_fetch_deposit_entries_success0()
    {
        $members    = [$this->mixin_sdk_safe->config['default']['client_id']];
        $chain_uuid = '25dabac5-056a-48ff-b9f9-f67395dc407c';
        $threshold  = 1;

        $res = $this->mixin_sdk_safe->wallet()->safeFetchDepositEntries($chain_uuid, $members, $threshold);
        dump($res);
        self::assertEquals($res[0]['type'], 'deposit_entry');
    }

    public function test_safe_read_deposits_success0()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadDeposits('25dabac5-056a-48ff-b9f9-f67395dc407c');
        dump('res', $res);

        self::assertIsArray($res);
    }

    public function test_migrate_to_safe_network()
    {
        $info = $this->mixin_sdk->network()->createUser('test', 'Ed25519');

        $config = MixinService::formatConfigFromCreateUser($info);

        // transfer asset
        $this->mixin_sdk->wallet()->transfer('69b2d237-1eb2-3b6c-8e1d-3876e507b263', $config['client_id'], null, 12345, 'test');

        $sub_user_tip_pin = TIPService::createEd25519PrivateKey();

        dump('sub_user_tip_pin', $sub_user_tip_pin);

        (new MixinSDK($config))
            ->pin()
            ->updatePin('', $sub_user_tip_pin);

        $config['pin'] = $sub_user_tip_pin;

        dump('config', $config);

        $sub_user_safe_pin = TIPService::createEd25519PrivateKey();

        dump('sub_user_safe_pin', $sub_user_safe_pin);

        // 迁移至 safe
        $res = (new MixinSDK($config))->user()->safeRegister($sub_user_safe_pin);

        dump('migrate to safe', $res);

        $safe_network_migration_uuid = '84c9dfb1-bfcf-4cb4-8404-cc5a1354005b';

        // 转账给迁移机器人
        $res = (new MixinSDK($config))->wallet()->transfer('69b2d237-1eb2-3b6c-8e1d-3876e507b263', $safe_network_migration_uuid, null, 10, 'memo:parse_me');

        dump('migrate asset', $res);
    }

    public function test_transfer_old_mainnet_asset_to_safe_user()
    {
        $res = $this->mixin_sdk->wallet()->transfer('69b2d237-1eb2-3b6c-8e1d-3876e507b263', $this->mixin_sdk_safe->config['default']['client_id'], null, 12345, 'test');

        dump($res);

        self::assertEquals('transfer', $res['type']);
    }

    public function test_migrate_old_mainnet_asset_to_safe()
    {
        $res = $this->mixin_sdk_safe->wallet()->readAsset('69b2d237-1eb2-3b6c-8e1d-3876e507b263');

        dump($res);

        $safe_network_migration_uuid = '84c9dfb1-bfcf-4cb4-8404-cc5a1354005b';

        $trace_id = Uuid::uuid4()->toString();

        $res = $this->mixin_sdk_safe->wallet()->transfer('69b2d237-1eb2-3b6c-8e1d-3876e507b263', $safe_network_migration_uuid, null, 12, 'test', $trace_id);

        dump('transfer result:', $res);

        self::assertEquals($res['trace_id'], $trace_id);
    }

    public function test_read_safe_mainnet_outputs_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadOutputs([$this->mixin_sdk_safe->config['default']['client_id']], 1);

        dump($res);

        self::assertIsNumeric($res[0]['amount']);
    }

    public function test_fetch_keys()
    {
        $info = [
            [
                'receivers' => ['2ef7c59f-bf5c-41b3-bb67-2d2c4d6b925c'],
                'index'     => 0,
                'hint'      => Uuid::uuid4()->toString(),
            ],
            [
                'receivers' => [$this->mixin_sdk_safe->config['default']['client_id']],
                'index'     => 0,
                'hint'      => Uuid::uuid4()->toString(),
            ]
        ];

        $keys = $this->mixin_sdk_safe->wallet()->safeFetchKeys($info);

        dump($keys);

        self::assertEquals(count($keys), count($info));
    }

    public function test_safe_mainnet_transfer_success()
    {
        $asset_hash = '6d2a7b89fcaca190f711043aeb5d6c274d6db49900257c1bd2e91aa24185d10c'; // asset ROAY
        $res        = $this->mixin_sdk_safe->wallet()->safeReadOutputs([$this->mixin_sdk_safe->config['default']['client_id']], 1, null, 10, $asset_hash, 'unspent');
        dump($res);
        $input = $res[0];

        $data = [
            [
                'receivers' => ['2ef7c59f-bf5c-41b3-bb67-2d2c4d6b925c'],
                'index'     => 0,
                'hint'      => Uuid::uuid4()->toString(),
            ],
            [
                'receivers' => [$this->mixin_sdk_safe->config['default']['client_id']],
                'index'     => 1,
                'hint'      => Uuid::uuid4()->toString(),
            ],
        ];

        $keys = $this->mixin_sdk_safe->wallet()->safeFetchKeys($data);

        dump('ghost keys', $keys);

        $transfer_amount = '1.1';

        $transaction = [
            'version' => 5,
            'asset'   => $asset_hash,
            'extra'   => bin2hex('test'), // <= 512
            'outputs' => [
                [
                    'type'   => 0,
                    'amount' => $transfer_amount,
                    //todo 收款人长度超过10的话待测试
                    'script' => "fffe0".count($data[0]['receivers']),
                    'keys'   => $keys[0]['keys'],
                    'mask'   => $keys[0]['mask'],
                ],
                [
                    'type'   => 0,
                    'amount' => (string)BigDecimal::of($input['amount'])->minus($transfer_amount)->stripTrailingZeros(),
                    'script' => "fffe0".count($data[1]['receivers']),
                    'keys'   => $keys[1]['keys'],
                    'mask'   => $keys[1]['mask'],
                ]
            ],
            'inputs'  => [
                [
                    'hash'  => $input['transaction_hash'],
                    'index' => $input['output_index'],
                ]
            ],
        ];

        dump('transaction', $transaction);

        $request_id = Uuid::uuid4()->toString();

        $trans = $this->mixin_sdk_safe->wallet()->safeRequestTransaction($transaction, $request_id);

        dump('request transaction', $trans);

        self::assertEquals($trans[0]['request_id'], $request_id);

        $res = $this->mixin_sdk_safe->wallet()->setRaw(true)->safeSendTransaction($transaction, $trans[0]['views'], $request_id);

        dump('send transaction', $res);
    }

    public function test_server_public_safe_mainnet_transfer_success()
    {
        $asset_hash = '6d2a7b89fcaca190f711043aeb5d6c274d6db49900257c1bd2e91aa24185d10c'; // asset ROAY
        $res        = $this->mixin_sdk_server_public->wallet()->safeReadOutputs([$this->mixin_sdk_server_public->config['default']['client_id']], 1, null, 10, $asset_hash, 'unspent');
        dump($res);
        $input = $res[0];

        $data = [
            [
                'receivers' => ['2ef7c59f-bf5c-41b3-bb67-2d2c4d6b925c'],
                'index'     => 0,
                'hint'      => Uuid::uuid4()->toString(),
            ],
            [
                'receivers' => [$this->mixin_sdk_server_public->config['default']['client_id']],
                'index'     => 1,
                'hint'      => Uuid::uuid4()->toString(),
            ],
        ];

        $keys = $this->mixin_sdk_server_public->wallet()->safeFetchKeys($data);

        dump('ghost keys', $keys);

        $transfer_amount = '1.1';

        $transaction = [
            'version' => 5,
            'asset'   => $asset_hash,
            'extra'   => bin2hex('test'), // <= 512
            'outputs' => [
                [
                    'type'   => 0,
                    'amount' => $transfer_amount,
                    //todo 收款人长度超过10的话待测试
                    'script' => "fffe0".count($data[0]['receivers']),
                    'keys'   => $keys[0]['keys'],
                    'mask'   => $keys[0]['mask'],
                ],
                [
                    'type'   => 0,
                    'amount' => (string)BigDecimal::of($input['amount'])->minus($transfer_amount)->stripTrailingZeros(),
                    'script' => "fffe0".count($data[1]['receivers']),
                    'keys'   => $keys[1]['keys'],
                    'mask'   => $keys[1]['mask'],
                ]
            ],
            'inputs'  => [
                [
                    'hash'  => $input['transaction_hash'],
                    'index' => $input['output_index'],
                ]
            ],
        ];

        dump('transaction', $transaction);

        $request_id = Uuid::uuid4()->toString();

        $trans = $this->mixin_sdk_server_public->wallet()->safeRequestTransaction($transaction, $request_id);

        dump('request transaction', $trans);

        self::assertEquals($trans[0]['request_id'], $request_id);

        $res = $this->mixin_sdk_server_public->wallet()->setRaw(true)->safeSendTransaction($transaction, $trans[0]['views'], $request_id);

        dump('send transaction', $res);
    }

    public function test_sign_mixin_ed25519()
    {
        $transaction = [
            "version" => 5,
            "asset"   => "6d2a7b89fcaca190f711043aeb5d6c274d6db49900257c1bd2e91aa24185d10c",
            "extra"   => "74657374",
            "hash"    => "7a161cc0f0c61c2f11a7d92f08ceecfc45a1d20a59c3ce04f1ad421528dfbd27",
            "inputs"  => [
                [
                    "hash"  => "7f555768fb407e2d9f8f37d10058929bb43d810148122598b21b5673cedc6421",
                    "index" => 0,
                ],
            ],
            "outputs" => [
                [
                    "amount" => "1.10000000",
                    "keys"   => [
                        "ba207da0d05bc72e0ad583ed92c9cd842c21beb77426e80d369a7fb84a11da28",
                    ],
                    "mask"   => "ee03ed1f8bfb20aad934368fbc025a8ad5bee04a79a4a05fb32fe1e4982b90c8",
                    "script" => "fffe01",
                    "type"   => 0,
                ],
                [
                    "amount" => "9.90000000",
                    "keys"   => [
                        "81ba99270e649697eb63905d0ce2569f86b405534b42eb463e6a290f826c44bb",
                    ],
                    "mask"   => "3c8e47aae35c770c50d8445a55bc2e34dbb17a328d455753d53a7c1669b4ed3b",
                    "script" => "fffe01",
                    "type"   => 0,
                ],
            ],
        ];

        $res = $this->mixin_sdk_safe->wallet()->setRaw(true)->safeSendTransaction($transaction, ["f122fbb9e26479f9c764415bc84046f9594e79967ad0ab9ac0cb76a1c2b0800c"], "12dcb8a2-90e3-479c-a83a-323088cc5fd1");

        dump('send transaction', $res);
    }

    public function test_safe_read_snapshots_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadSnapshots(null, '628bbb72-e53f-37f6-ba49-c4a070494a70');

        dump($res);

        self::assertIsArray($res);
    }

    public function test_safe_read_snapshot_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadSnapshot('01ef948c-fda9-3487-8571-0283f99ab574');

        dump($res);

        self::assertIsArray($res);
    }

    public function test_safe_read_assets_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadAssets();

        dump($res);

        self::assertIsArray($res);
    }

    public function test_server_public_safe_read_assets_success()
    {
        $res = $this->mixin_sdk_server_public->wallet()->safeReadAssets();

        dump($res);

        self::assertIsArray($res);
    }

    public function test_safe_read_asset_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadAsset('b91e18ff-a9ae-3dc7-8679-e935d9a4b34b');
        dump($res);
        self::assertIsArray($res);
    }

    public function test_safe_read_asset_withdraw_fee_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->safeReadAssetWithdrawFees('b91e18ff-a9ae-3dc7-8679-e935d9a4b34b');

        dump($res);

        self::assertIsArray($res);
    }

    public function test_set_with_headers_success()
    {
        $res = $this->mixin_sdk_safe->wallet()->setRaw(true)->setWithHeaders()->safeReadSnapshots(null, '628bbb72-e53f-37f6-ba49-c4a070494a70');

        dump($res);

        self::assertIsArray($res['headers']);
        self::assertIsString($res['headers']['X-Request-Id'][0]);
    }

    public function test_multiple_safe_register()
    {
        $info = $this->mixin_sdk->network()->createUser('test', 'Ed25519');

        $config = MixinService::formatConfigFromCreateUser($info);

        $sub_user_tip_pin = TIPService::createEd25519PrivateKey();

        dump('sub_user_tip_pin', $sub_user_tip_pin);

        (new MixinSDK($config))
            ->pin()
            ->updatePin('', $sub_user_tip_pin);

        $config['pin'] = $sub_user_tip_pin;

        dump('config', $config);

        $sub_user_safe_pin = TIPService::createEd25519PrivateKey();

        dump('sub_user_safe_pin', $sub_user_safe_pin);

        // 迁移至 safe
        $res = (new MixinSDK($config))->user()->safeRegister($sub_user_safe_pin);

        dump('migrate to safe 1', $res);

        // 重复迁移 safe
        // 目前这里会报错
        $res = (new MixinSDK($config))->user()->safeRegister($sub_user_safe_pin);

        dump('migrate to safe 2', $res);
    }

    public function test_safe_withdraw_fee_same_asset_success()
    {
        $fee_user_id = '674d6776-d600-4346-af46-58e77d8df185';
        $asset_id = 'b91e18ff-a9ae-3dc7-8679-e935d9a4b34b'; // USDT trc-20
        $asset_hash = '5b9d576914e71e2362f89bb867eb69084931eb958f9a3622d776b861602275f4'; // USDT trc-20
        $destination = 'TTHy6BDKj9NjBSjACQkSYvf5YRZxBLask4'; // TRX
        $tag = '';

        // $asset_id = '69b2d237-1eb2-3b6c-8e1d-3876e507b263'; // asset ROAY
        // $asset_hash = '6d2a7b89fcaca190f711043aeb5d6c274d6db49900257c1bd2e91aa24185d10c'; // asset ROAY
        // $destination = '0x74736236D17aEf4F819Bd2FaD6eC055C01E0FE98'; // ETH
        // $tag = '';

        $res        = $this->mixin_sdk_safe->wallet()->safeReadOutputs([$this->mixin_sdk_safe->config['default']['client_id']], 1, null, 10, $asset_hash, 'unspent');

        dump($res);
        $input = $res[0];

        $fee_res = $this->mixin_sdk_safe->wallet()->safeReadAssetWithdrawFees($asset_id, $destination);

        dump($fee_res);

        $fee_asset_id = null;
        $fee_amount = null;
        foreach ($fee_res as $info) {
            if ($info['asset_id'] === $asset_id) {
                $fee_asset_id = $info['asset_id'];
                $fee_amount = $info['amount'];
                break;
            }
        }

        if (! $fee_asset_id) {
            throw new \Exception('SAME_FEE_ASSET_NOT_EXISTS');
        }

        // fetch keys for fee and change
        $data = [
            [
                'receivers' => [$fee_user_id],
                'index'     => 1,
                'hint'      => Uuid::uuid4()->toString(),
            ],
            [
                'receivers' => [$this->mixin_sdk_safe->config['default']['client_id']],
                'index'     => 2,
                'hint'      => Uuid::uuid4()->toString(),
            ],
        ];

        $keys = $this->mixin_sdk_safe->wallet()->safeFetchKeys($data);

        dump('ghost keys', $keys);

        $transfer_amount = '0.1';

        $transaction = [
            'version' => 5,
            'asset'   => $asset_hash,
            'extra'   => bin2hex('test_withdraw'), // <= 512
            'outputs' => [
                // withdraw
                [
                    'type'   => 161, // type withdraw
                    'amount' => $transfer_amount,
                    'withdrawal' => [
                        'address' => $destination,
                        'tag' => $tag,
                    ],
                ],
                // fee
                [
                    'type'   => 0,
                    'amount' => (string)$fee_amount,
                    'script' => "fffe01",
                    'keys'   => $keys[0]['keys'],
                    'mask'   => $keys[0]['mask'],
                ],
                // change back. Here assumes there always a change back
                [
                    'type'   => 0,
                    'amount' => (string)BigDecimal::of($input['amount'])->minus($transfer_amount)->minus($fee_amount)->stripTrailingZeros(),
                    'script' => "fffe0".count($data[1]['receivers']),
                    'keys'   => $keys[1]['keys'],
                    'mask'   => $keys[1]['mask'],
                ],
            ],
            'inputs'  => [
                [
                    'hash'  => $input['transaction_hash'],
                    'index' => $input['output_index'],
                ]
            ],
        ];

        dump('transaction', $transaction);

        $request_id = Uuid::uuid4()->toString();

        $trans = $this->mixin_sdk_safe->wallet()->safeRequestTransaction($transaction, $request_id);

        dump('request transaction', $trans);

        self::assertEquals($trans[0]['request_id'], $request_id);

        $res = $this->mixin_sdk_safe->wallet()->setRaw(true)->safeSendTransaction($transaction, $trans[0]['views'], $request_id);

        dump('send transaction', $res);
    }

    /**
     * 在同一个交易中发起提现和支付手续费
     * @return void
     * @throws \Exception
     */
    public function test_safe_withdraw_fee_diff_asset_success()
    {
        $fee_user_id = '674d6776-d600-4346-af46-58e77d8df185';
        $asset_id = 'b91e18ff-a9ae-3dc7-8679-e935d9a4b34b'; // USDT trc-20
        $asset_hash = '5b9d576914e71e2362f89bb867eb69084931eb958f9a3622d776b861602275f4'; // USDT trc-20
        $fee_asset_id = '25dabac5-056a-48ff-b9f9-f67395dc407c';
        $fee_asset_hash = '05edf1e8723e2ece67afcdfd7fbb504c64a5a939ec8fe5fa05fc7a104011abc9';
        $destination = 'TTHy6BDKj9NjBSjACQkSYvf5YRZxBLask4'; // TRX
        $tag = '';

        $res        = $this->mixin_sdk_safe->wallet()->safeReadOutputs([$this->mixin_sdk_safe->config['default']['client_id']], 1, null, 10, $asset_id, 'unspent');

        dump($res);
        $input = $res[0];

        $fee_res = $this->mixin_sdk_safe->wallet()->safeReadAssetWithdrawFees($asset_id, $destination);

        dump($fee_res);

        $fee_asset_id = null;
        $fee_amount = null;
        foreach ($fee_res as $info) {
            if ($info['asset_id'] === $fee_asset_id) {
                $fee_amount = $info['amount'];
                break;
            }
        }

        if (! $fee_asset_id) {
            throw new \Exception('SAME_FEE_ASSET_NOT_EXISTS');
        }

        $request_id = Uuid::uuid4()->toString();
        $fee_request_id = Uuid::uuid4()->toString();

        $res        = $this->mixin_sdk_safe->wallet()->safeReadOutputs([$this->mixin_sdk_safe->config['default']['client_id']], 1, null, 10, $fee_asset_id, 'unspent');

        dump($res);
        $fee_input = $res[0];

        // fetch keys for change
        $data = [
            [
                'receivers' => [$this->mixin_sdk_safe->config['default']['client_id']],
                'index'     => 1,
                'hint'      => Uuid::uuid4()->toString(),
            ],
        ];

        $keys = $this->mixin_sdk_safe->wallet()->safeFetchKeys($data);

        dump('change ghost keys', $keys);

        $transfer_amount = '0.1';

        $transaction = [
            'version' => 5,
            'asset'   => $asset_hash,
            'extra'   => bin2hex('test_withdraw'), // <= 512
            'outputs' => [
                // withdraw
                [
                    'type'   => 161, // type withdraw
                    'amount' => $transfer_amount,
                    'withdrawal' => [
                        'address' => $destination,
                        'tag' => $tag,
                    ],
                ],
                // change back. Here assumes there always a change back
                [
                    'type'   => 0,
                    'amount' => (string)BigDecimal::of($input['amount'])->minus($transfer_amount)->stripTrailingZeros(),
                    'script' => "fffe0".count($data[0]['receivers']),
                    'keys'   => $keys[0]['keys'],
                    'mask'   => $keys[0]['mask'],
                ],
            ],
            'inputs'  => [
                [
                    'hash'  => $input['transaction_hash'],
                    'index' => $input['output_index'],
                ]
            ],
        ];

        dump('transaction', $transaction);

        $raw = (new Encoder())->encodeTransaction($transaction);

        dump('transaction raw', $raw);

        // fetch keys for fee and fee change
        $data = [
            [
                'receivers' => [$fee_user_id],
                'index'     => 0,
                'hint'      => Uuid::uuid4()->toString(),
            ],
            [
                'receivers' => [$this->mixin_sdk_safe->config['default']['client_id']],
                'index'     => 1,
                'hint'      => Uuid::uuid4()->toString(),
            ],
        ];

        $keys = $this->mixin_sdk_safe->wallet()->safeFetchKeys($data);

        dump('fee ghost keys', $keys);

        $transaction = [
            'version' => 5,
            'asset'   => $fee_asset_hash,
            'extra'   => bin2hex(''), // <= 512
            'outputs' => [
                // fee
                [
                    'type'   => 0,
                    'amount' => (string)$fee_amount,
                    'script' => "fffe01",
                    'keys'   => $keys[0]['keys'],
                    'mask'   => $keys[0]['mask'],
                ],
                // change back. Here assumes there always a change back
                [
                    'type'   => 0,
                    'amount' => (string)BigDecimal::of($fee_input['amount'])->minus($fee_amount)->stripTrailingZeros(),
                    'script' => "fffe0".count($data[1]['receivers']),
                    'keys'   => $keys[1]['keys'],
                    'mask'   => $keys[1]['mask'],
                ],
            ],
            'inputs'  => [
                [
                    'hash'  => $fee_input['transaction_hash'],
                    'index' => $fee_input['output_index'],
                ]
            ],
            'reference' => $raw, //blake hash
        ];

    }
}
