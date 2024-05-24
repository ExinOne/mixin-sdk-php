<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:53
 */

namespace ExinOne\MixinSDK\Apis;

use Base64Url\Base64Url;
use Brick\Math\BigDecimal;
use ExinOne\MixinSDK\Exceptions\EncodeFailException;
use ExinOne\MixinSDK\Exceptions\EncodeNotYetImplementedException;
use ExinOne\MixinSDK\Exceptions\InternalErrorException;
use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;
use ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException;
use ExinOne\MixinSDK\Exceptions\NotSupportTIPPINException;
use ExinOne\MixinSDK\Traits\MixinSDKTrait;
use ExinOne\MixinSDK\Utils\Blake3;
use ExinOne\MixinSDK\Utils\TIPService;
use ExinOne\MixinSDK\Utils\TransactionV5\Encoder;
use Ramsey\Uuid\Uuid;

class Wallet extends Api
{
    /**
     * @param string      $asset_id
     * @param string      $destination BTC address or EOS account name like ‘eoswithmixin’
     * @param string|null $pin
     * @param string      $label       can’t be blank, max size 64
     * @param string      $tag         can be blank, EOS account tag or memo
     * @return array
     * @throws LoadPrivateKeyException
     */
    public function createAddress(string $asset_id, string $destination, ?string $pin, string $label, string $tag = ''): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            'asset_id'    => $asset_id,
            'label'       => $label,
            'destination' => $destination,
            'tag'         => $tag,
        ];

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, "TIP:ADDRESS:ADD:", $asset_id, $destination, $tag, $label);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        return $this->res($body);
    }

    /**
     * @param string $asset_id
     * @param        $public_key
     * @param        $label
     * @param        $account_name
     * @param        $account_tag
     * @param null   $pin
     *
     * @return array
     * @throws LoadPrivateKeyException|NotSupportTIPPINException
     * @deprecated 由于支持新的 alpha 创建地址api，该方法遗弃
     */
    public function createAddressRaw(string $asset_id, $public_key, $label, $account_name, $account_tag, $pin = null)
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        assertTIPPIN($pin, 'createAddressRaw does not support TIP pin');

        $body = [
            'asset_id'     => $asset_id,
            'public_key'   => $public_key,
            'label'        => $label,
            'account_name' => $account_name,
            'account_tag'  => $account_tag,
            'pin'          => $pin == '' ? '' : $this->encryptPin((string)$pin),
        ];

        return $this->res($body);
    }

    /**
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readAddresses(string $assetId): array
    {
        $url = str_replace('{$assetId}', $assetId, $this->endPointUrl);

        return $this->res([], $url);
    }

    /**
     * @param string $addressId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readAddress(string $addressId): array
    {
        $url = $this->endPointUrl.$addressId;

        return $this->res([], $url);
    }

    /**
     * @param string $addressId
     * @param string $pin
     *
     * @return array
     * @throws LoadPrivateKeyException
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function deleteAddress(string $address_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, "TIP:ADDRESS:REMOVE:", $address_id);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        $url = str_replace('{$addressId}', $address_id, $this->endPointUrl);

        return $this->res($body, $url);
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readAssets(): array
    {
        return $this->res();
    }

    /**
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readAsset(string $assetId): array
    {
        $url = $this->endPointUrl.$assetId;

        return $this->res([], $url);
    }

    /**
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function deposit(string $assetId): array
    {
        $url = $this->endPointUrl.$assetId;

        return $this->res([], $url);
    }

    /**
     * @param string      $address_id
     * @param string      $amount
     * @param string|null $pin
     * @param string      $memo
     * @param string|null $trace_id
     * @param string      $fee 通过查询手续费的接口得到的手续费具体数值，不传的话会由主网自动决定
     * @return array
     * @throws \Exception
     */
    public function withdrawal(string $address_id, string $amount, ?string $pin, string $memo = '', string $trace_id = null, string $fee = '0'): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $trace_id = empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id;

        $amount = (string)BigDecimal::of($amount)->stripTrailingZeros();
        $fee    = (string)BigDecimal::of($fee)->stripTrailingZeros();

        $body = [
            'address_id' => $address_id,
            'amount'     => $amount,
            'memo'       => $memo,
            'trace_id'   => $trace_id,
        ];

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, 'TIP:WITHDRAWAL:CREATE:', $address_id, $amount, $fee, $trace_id, $memo);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        if (is_numeric($fee) && $fee > 0) {
            $body['fee'] = $fee;
        }

        return $this->res($body);
    }

    /**
     * @param string      $asset_id
     * @param string      $opponent_id
     * @param string|null $pin
     * @param string      $amount
     * @param string      $memo
     * @param string|null $trace_id
     *
     * @return array
     * @throws LoadPrivateKeyException
     */
    public function transfer(string $asset_id, string $opponent_id, ?string $pin, string $amount, string $memo = '', string $trace_id = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $trace_id = empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id;

        $amount = (string)BigDecimal::of($amount)->stripTrailingZeros();

        $body = [
            'asset_id'    => $asset_id,
            'opponent_id' => $opponent_id,
            'amount'      => $amount,
            'trace_id'    => $trace_id,
            'memo'        => $memo,
        ];

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, 'TIP:TRANSFER:CREATE:', $asset_id, $opponent_id, $amount, $trace_id, $memo);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        $headers = [
            //todo 似乎已经不需要了
            'Mixin-Device-Id' => $this->config['session_id'],
        ];

        return $this->res($body, null, $headers);
    }

    /**
     * @param string $asset_id
     * @param string $opponent_id
     * @param        $amount
     * @param string $trace_id
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function verifyPayment(string $asset_id, string $opponent_id, $amount, string $trace_id): array
    {
        $amount = (string)$amount;
        $body   = compact('asset_id', 'opponent_id', 'amount', 'trace_id');

        return $this->res($body);
    }

    /**
     * @param string $traceId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readTransfer(string $traceId): array
    {
        $url = str_replace('{$traceId}', $traceId, $this->endPointUrl);

        return $this->res([], $url);
    }

    /**
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readAssetFee(string $assetId): array
    {
        $url = str_replace('{$assetId}', $assetId, $this->endPointUrl);

        return $this->res(null, $url);
    }

    /**
     * @param int|null    $limit
     * @param string|null $offset
     * @param string      $asset
     * @param string      $order
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readUserSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
    {
        $limit    = empty($limit) ? $limit : (int)$limit;
        $url_argv = compact('limit', 'offset', 'asset', 'order');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url);
    }

    /**
     * @param string $snapshotId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readUserSnapshot(string $snapshotId): array
    {
        $url = $this->endPointUrl.$snapshotId;

        return $this->res([], $url);
    }


    /**
     * @param string      $access_token
     * @param int|null    $limit
     * @param string|null $offset
     * @param string      $asset
     * @param string      $order
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetUserSnapshots(string $access_token, $limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
    {
        $limit    = empty($limit) ? $limit : (int)$limit;
        $url_argv = compact('limit', 'offset', 'asset', 'order');

        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url, $headers);
    }

    /**
     * @param string $access_token
     * @param string $snapshot_id
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetUserSnapshot(string $access_token, string $snapshot_id): array
    {
        $url = $this->endPointUrl.$snapshot_id;

        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res([], $url, $headers);
    }

    /**
     * @param string $access_token
     * @param string $trace_id
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetTransfer(string $access_token, string $trace_id): array
    {
        $url = str_replace('{$traceId}', $trace_id, $this->endPointUrl);

        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res([], $url, $headers);
    }

    /**
     * @param string $client_id
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readRawMainNetAddress(string $client_id): array
    {
        $body = [
            'receivers' => [$client_id],
        ];

        return $this->res($body);
    }

    /**
     * @param array       $client_ids
     * @param int|null    $index
     * @param string|null $hint
     * @return array
     * @throws \Exception
     */
    public function generateGhostKeys(array $client_ids, int $index = null, string $hint = null): array
    {
        $body = [
            'receivers' => $client_ids,
        ];

        if ($index !== null) {
            $body['index'] = $index;
        }

        if ($hint !== null) {
            $body['hint'] = $hint;
        }

        return $this->res($body);
    }

    /**
     * @param string $asset_id
     * @param array  $opponent_multisig
     * @param int    $threshold
     * @param        $amount
     * @param string $memo
     * @param string $trace_id
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function multisigPayment(string $asset_id, array $receivers, int $threshold, $amount, $memo = '', $trace_id = null): array
    {
        $threshold         = $threshold < 2 ? 2 : $threshold;
        $amount            = (string)$amount;
        $opponent_multisig = [
            'receivers' => $receivers,
            'threshold' => $threshold,
        ];
        $trace_id          = empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id;
        $body              = compact('asset_id', 'opponent_multisig', 'amount', 'memo', 'trace_id');

        return $this->res($body);
    }

    /**
     * @param string $code_id
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function checkCode($code_id): array
    {
        $url = $this->endPointUrl.$code_id;

        return $this->res([], $url);
    }

    /**
     * @param string|null $offset
     * @param int|null    $limit
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     * @deprecated
     */
    public function readMultisigs(string $offset = '', $limit = null): array
    {
        $limit    = empty($limit) ? 100 : (int)$limit;
        $url_argv = compact('limit', 'offset');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url);
    }

    /**
     * @param string $access_token
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     * @deprecated
     */
    public function accessTokenPostMultisigs(string $access_token, string $raw, string $action = 'sign'): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $body = compact('action', 'raw');

        return $this->res($body, null, $headers);
    }

    /**
     * @param string $access_token
     * @param array  $receivers
     * @param int    $index
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenPostOutputs($access_token, $receivers, $index = 0, $hint = ""): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];
        if ($hint !== "") {
            $body = compact('receivers', 'index', 'hint');
        } else {
            $body = compact('receivers', 'index');
        }

        return $this->res($body, null, $headers);
    }

    /**
     * @param array  $receivers
     * @param int    $index
     * @param string $hint
     * @return array
     */
    public function readOutputs($receivers, $index = 0, $hint = ""): array
    {
        if ($hint === "") {
            $hint = Uuid::uuid4()->toString();
        }
        $body = compact('receivers', 'index', 'hint');

        return $this->res($body);
    }

    /**
     * @param array  $params
     * @param string $method
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function externalProxy($params, $method = 'sendrawtransaction'): array
    {
        $body = compact('params', 'method');

        return $this->res($body);
    }

    /**
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     * @deprecated
     */
    public function postMultisigs(string $raw, string $action = 'sign'): array
    {
        $body = compact('action', 'raw');

        return $this->res($body);
    }

    /**
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function multisigsRequests(string $raw, string $action = 'sign'): array
    {
        $body = compact('action', 'raw');

        return $this->res($body);
    }

    /**
     * @param string $access_token
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenMultisigsRequests(string $access_token, string $raw, string $action = 'sign'): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $body = compact('action', 'raw');

        return $this->res($body, null, $headers);
    }

    /**
     * @param string      $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws LoadPrivateKeyException|\Exception
     */
    public function multisigsRequestsSign(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        if (TIPService::isTIPPin($pin)) {
            $body = [
                'pin_base64' => $this->encryptTIPPin($pin, "TIP:MULTISIG:REQUEST:SIGN:", $request_id),
            ];
        } else {
            $body = [
                'pin' => $this->encryptPin($pin),
            ];
        }

        return $this->res($body, $url);
    }

    /**
     * @param string      $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws LoadPrivateKeyException
     */
    public function multisigsRequestsCancel(string $request_id): array
    {
        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        return $this->res([], $url);
    }

    /**
     * @param string      $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws LoadPrivateKeyException
     */
    public function multisigsRequestsUnlock(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        if (TIPService::isTIPPin($pin)) {
            $body = [
                'pin_base64' => $this->encryptTIPPin($pin, 'TIP:MULTISIG:REQUEST:UNLOCK:', $request_id),
            ];
        } else {
            $body = [
                'pin' => $this->encryptPin($pin),
            ];
        }

        return $this->res($body, $url);
    }

    /**
     * @param string      $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws LoadPrivateKeyException
     * @throws NotSupportTIPPINException
     * @deprecated use multisigsRequestsSign instead
     */
    public function multisigsSign(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        assertTIPPIN($pin, 'multisigsSign does not support TIP pin');

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        $body = [
            'pin' => $this->encryptPin((string)$pin),
        ];

        return $this->res($body, $url);
    }

    /**
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readFiats(): array
    {
        return $this->res();
    }

    /**
     * @param string $request_id
     * @param string $pin
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     * @deprecated use multisigsRequestsCancel instead
     */
    public function multisigsCancel(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        assertTIPPIN($pin, 'multisigsCancel does not support TIP pin');

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        $body = [
            'pin' => $this->encryptPin((string)$pin),
        ];

        return $this->res($body, $url);
    }

    /**
     * 转账到多签
     * @param string      $asset_id
     * @param array       $receivers
     * @param int         $threshold
     * @param string      $amount
     * @param string|null $pin
     * @param string|null $trace_id
     * @param string|null $memo
     * @return array
     * @throws \Exception
     */
    public function sendMultisigTransactions(string $asset_id, array $receivers, int $threshold, string $amount, string $pin = null, string $trace_id = null, string $memo = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $opponent_multisig = [
            'receivers' => $receivers,
            'threshold' => $threshold,
        ];

        $trace_id = empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id;

        $amount = (string)BigDecimal::of($amount)->stripTrailingZeros();

        $body = [
            'asset_id'          => $asset_id,
            'opponent_multisig' => $opponent_multisig,
            'amount'            => $amount,
            'trace_id'          => $trace_id,
            'memo'              => $memo,
        ];

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, "TIP:TRANSACTION:CREATE:", $asset_id, implode('', $receivers), $threshold, $amount, $trace_id, $memo);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        return $this->res($body);
    }

    /**
     * 转账到主网地址
     * @param string      $asset_id
     * @param string      $opponent_key
     * @param string      $amount
     * @param string|null $pin
     * @param string|null $trace_id
     * @param string|null $memo
     * @return array
     * @throws \Exception
     */
    public function sendMainnetTransactions(string $asset_id, string $opponent_key, string $amount, string $pin = null, string $trace_id = null, string $memo = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $trace_id = empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id;

        $amount = (string)BigDecimal::of($amount)->stripTrailingZeros();

        $body = [
            'asset_id'     => $asset_id,
            'opponent_key' => $opponent_key,
            'amount'       => $amount,
            'trace_id'     => $trace_id,
            'memo'         => $memo,
        ];

        if (TIPService::isTIPPin($pin)) {
            $body['pin_base64'] = $this->encryptTIPPin($pin, "TIP:TRANSACTION:CREATE:", $asset_id, $opponent_key, $amount, $trace_id, $memo);
        } else {
            $body['pin'] = $this->encryptPin($pin);
        }

        return $this->res($body);
    }

    /**
     * @param string $offset
     * @param array  $members
     * @param string $state
     * @param string $threshold
     * @param string $limit
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readMultisigsOutputs(string $offset = '', array $members = [], $state = '', $threshold = 2, $limit = '500')
    {
        if (! empty($members)) {
            sort($members);
            $members = hash('sha3-256', implode('', $members));
        } else {
            $members = null;
        }

        $limit    = empty($limit) ? 100 : (int)$limit;
        $url_argv = compact('limit', 'offset', 'state', 'members', 'threshold');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));
        return $this->res([], $url);
    }

    /**
     * @param string $access_token
     * @param string $offset
     * @param array  $members
     * @param string $state
     * @param string $threshold
     * @param string $limit
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenReadMultisigsOutputs($access_token, string $offset = '', array $members = [], $state = '', $threshold = 2, $limit = '500')
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        if (! empty($members)) {
            sort($members);
            $members = hash('sha3-256', implode('', $members));
        } else {
            $members = null;
        }

        $body = compact('limit', 'offset', 'state', 'members', 'threshold');

        return $this->res($body, null, $headers);
    }

    /**
     * @param string $access_token
     * @param string $request_id
     * @param string $action
     *
     * @return array
     * @throws LoadPrivateKeyException
     */
    public function accessTokenMultisigsRequestsAction(string $access_token, string $request_id, $action = 'sign'): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = str_replace(['{$requestId}', '{$action}'], [$request_id, $action], $this->endPointUrl);

        return $this->res([], $url, $headers);
    }

    public function safeFetchDepositEntries(string $chain_uuid, array $members, int $threshold): array
    {
        $body = [
            'members'   => $members,
            'threshold' => $threshold,
            'chain_id'  => $chain_uuid,
        ];
        return $this->res($body);
    }

    public function safeReadDeposits(string $asset_uuid = null, string $destination = null, string $tag = null, string $offset = null, int $limit = 500): array
    {
        $asset    = $asset_uuid;
        $url_argv = compact('asset', 'destination', 'tag', 'offset', 'limit');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url);
    }

    public function safeReadOutputs(array $members_array = null, int $threshold = null, int $offset_sequence = null, int $limit = 500, string $asset_hash = null, string $state = null, string $order = 'ASC', string $app = null): array
    {
        $members = null;
        if (is_array($members_array)) {
            sort($members_array);
            $members = hash('sha3-256', implode('', $members_array));
        }

        $offset = $offset_sequence;

        $asset = $asset_hash;

        $url_argv = compact('members', 'threshold', 'offset', 'limit', 'asset', 'state', 'order', 'app');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url);
    }

    public function accessTokenSafeReadOutputs(string $access_token, array $members_array = null, int $threshold = null, int $offset_sequence = null, int $limit = 500, string $asset_hash = null, string $state = null, string $order = 'ASC'): array
    {
        $members = null;
        if (is_array($members_array)) {
            sort($members_array);
            $members = hash('sha3-256', implode('', $members_array));
        }

        $offset = $offset_sequence;

        $asset = $asset_hash;

        $url_argv = compact('members', 'threshold', 'offset', 'limit', 'asset', 'state', 'order');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url, [
            'Authorization' => 'Bearer '.$access_token,
        ]);
    }

    /**
     * @param array{
     *     receivers: string[],
     *     index: int,
     *     hint: string
     * } $receiver_info
     * @return array
     * @throws \Exception
     */
    public function safeFetchKeys(array $receiver_info): array
    {
        // 简单的参数校验
        foreach ($receiver_info as $index => $info) {
            if (! isset($info['receivers'])) {
                throw new InvalidInputFieldException("field `receivers`(array) is required in element {$index}");
            }
            if (! isset($info['index'])) {
                throw new InvalidInputFieldException("field `index`(int) is required in element {$index}");
            }
            if (! isset($info['hint'])) {
                throw new InvalidInputFieldException("field `hint`(string) is required in element {$index}");
            }
        }
        return $this->res($receiver_info);
    }

    public function safeRequestTransaction(array $transaction, string $request_id): array
    {
        $body = [
            [
                'request_id' => $request_id,
                'raw'        => (new Encoder())->encodeTransaction($transaction),
            ],
        ];

        return $this->res($body);
    }

    //todo safeRequestTransactions

    //todo safeSendTransactions

    /**
     * @param array       $transaction
     * @param array       $views
     * @param string|null $trace_id
     * @param string|null $spent_key
     * @param bool        $use_32_bits
     * @return array
     * @throws EncodeFailException
     * @throws EncodeNotYetImplementedException
     * @throws InvalidInputFieldException
     * @throws InternalErrorException
     * @throws \SodiumException
     */
    public function safeSendTransaction(array $transaction, array $views, string $trace_id = null, string $spent_key = null, bool $use_32_bits = false): array
    {
        $inputs = $transaction['inputs'] ?? [];
        if (! $inputs) {
            throw new InvalidInputFieldException('MISSING_INPUTS');
        }
        if (count($inputs) !== count($views)) {
            throw new InvalidInputFieldException('INPUTS_AND_VIEWS_NOT_MATCH');
        }

        $raw = (new Encoder())->encodeTransaction($transaction);
        $msg = (new Blake3())->hash(hex2bin($raw));

        if (! $spent_key) {
            $spent_key = $this->config['safe_key'] ?? null;
        }
        if ($spent_key === null) {
            throw new InvalidInputFieldException('NEED_SPENT_KEY_TO_PERFORM_TRANSACTION');
        }

        $raw_key = substr(Base64Url::decode($spent_key), 0, 32);

        $spent_y = hash("sha512", $raw_key, true);

        $y = TIPService::getBytesWithClamping(substr($spent_y, 0, 32));

        $signatures_map = [];
        foreach ($inputs as $i => $input) {
            if (! ($views[$i] ?? null)) {
                throw new InvalidInputFieldException('MISSING_VIEW');
            }

            $seed = hex2bin($views[$i]);

            // $seed = sodium_crypto_core_ristretto255_scalar_add($seed, $y);
            $seed = \ParagonIE_Sodium_Core_Ed25519::scalar_add($seed, $y);

            $pub = TIPService::getPublicKeyFromEd25519KeyPair($seed, $use_32_bits);

            $new_key = $seed.$pub;

            $sig = bin2hex(TIPService::signWithMixinEd25519($new_key, hex2bin($msg)));

            $signatures_map[$i] = [0 => $sig]; // for 1/1 bot transaction
        }

        $transaction['signatures_map'] = $signatures_map;

        if (! $trace_id) {
            $trace_id = Uuid::uuid4()->toString();
        }
        $body = [
            [
                'request_id' => $trace_id,
                'raw'        => (new Encoder())->encodeTransaction($transaction),
            ]
        ];

        return $this->res($body);
    }

    public function safeReadTransaction(string $request_id): array
    {
        $url = $this->endPointUrl.$request_id;

        return $this->res([], $url);
    }

    public function accessTokenSafeReadTransaction(string $access_token, string $request_id): array
    {
        $url = $this->endPointUrl.$request_id;

        return $this->res([], $url, [
            'Authorization' => 'Bearer '.$access_token,
        ]);
    }

    public function safeReadSnapshots(string $asset_uuid = null, string $app = null, string $opponent = null, string $offset = null, int $limit = 500): array
    {
        $asset    = $asset_uuid;
        $url_argv = compact('asset', 'app', 'opponent', 'offset', 'limit');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url);
    }

    public function safeReadSnapshot(string $request_id): array
    {
        $url = $this->endPointUrl.$request_id;

        return $this->res([], $url);
    }

    public function safeReadAssets(): array
    {
        $url = $this->endPointUrl;
        return $this->res([], $url);
    }

    public function safeReadAsset(string $asset_id): array
    {
        $url = $this->endPointUrl.$asset_id;
        return $this->res([], $url);
    }

    public function safeReadAssetWithdrawFees(string $asset_id): array
    {
        $url = str_replace('{$asset_id}', $asset_id, $this->endPointUrl);

        return $this->res([], $url);
    }

    public function accessTokenSafeReadSnapshots(string $access_token, string $asset_uuid = null, string $app = null, string $opponent = null, string $offset = null, int $limit = 500): array
    {
        $asset    = $asset_uuid;
        $url_argv = compact('asset', 'app', 'opponent', 'offset', 'limit');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($url_argv));

        return $this->res([], $url, [
            'Authorization' => 'Bearer '.$access_token,
        ]);
    }

    public function safeMultisigCreateRequests(array $array): array
    {
        // 简单的参数校验
        foreach ($array as $index => $item) {
            if (! isset($item['raw'])) {
                throw new InvalidInputFieldException("field `raw`(array) is required in element {$index}");
            }
            if (! isset($item['request_id'])) {
                throw new InvalidInputFieldException("field `request_id`(string) is required in element {$index}");
            }
        }

        return $this->res($array);
    }

    public function safeMultisigCreateRequest(array $raw, string $request_id): array
    {
        $body = [
            [
                'request_id' => $request_id,
                'raw'        => $raw,
            ]
        ];

        return $this->res($body);
    }

    public function safeMultisigReadRequests(string $id_or_hash): array
    {
        $url = str_replace('{$idOrHash}', $id_or_hash, $this->endPointUrl);

        return $this->res([], $url);
    }

    public function safeMultisigSignRequest(string $request_id, array $input): array
    {
        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        return $this->res($input, $url);
    }

    public function safeMultisigUnlockRequest(string $request_id): array
    {
        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        return $this->res([], $url);
    }

    public function safeMultisigCancelRequest(string $request_id): array
    {
        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        return $this->res([], $url);
    }
}
