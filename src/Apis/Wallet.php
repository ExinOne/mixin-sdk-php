<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:53
 */

namespace ExinOne\MixinSDK\Apis;

use Ramsey\Uuid\Uuid;

class Wallet extends Api
{
    /**
     * @param string $asset_id
     * @param string $destination BTC address or EOS account name like ‘eoswithmixin’
     * @param        $pin
     * @param        $label       “Mixin”, can’t be blank, max size 64
     * @param        $tag         can be blank, EOS account tag or memo
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function createAddress(string $asset_id, string $destination, $pin, $label, $tag = false): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        if (is_bool($tag)) {
            if (! $tag) {
                $body = [
                    'asset_id'   => $asset_id,
                    'public_key' => $destination,
                    'label'      => $label,
                    'pin'        => $pin == '' ? '' : $this->encryptPin((string)$pin),
                ];
            } else {
                $body = [
                    'asset_id'     => $asset_id,
                    'account_name' => $destination,
                    'account_tag'  => $label,
                    'pin'          => $pin == '' ? '' : $this->encryptPin((string)$pin),
                ];
            }
        } else {
            $body = [
                'asset_id'    => $asset_id,
                'label'       => $label,
                'pin'         => $pin == '' ? '' : $this->encryptPin((string)$pin),
                'destination' => $destination,
                'tag'         => $tag,
            ];
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
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     * @deprecated 由于支持新的 alpha 创建地址api，该方法遗弃
     */
    public function createAddressRaw(string $asset_id, $public_key, $label, $account_name, $account_tag, $pin = null)
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

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
     * @param        $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function deleteAddress(string $addressId, $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            'pin' => $pin == '' ? '' : $this->encryptPin((string)$pin),
        ];

        $url = str_replace('{$addressId}', $addressId, $this->endPointUrl);

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
     * @param string $addressId
     * @param        $amount
     * @param string $memo
     * @param        $pin
     * @param null   $trace_id
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function withdrawal(string $addressId, $amount, $pin, $memo = '', $trace_id = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            'address_id' => $addressId,
            'amount'     => (string)$amount,
            'memo'       => $memo,
            'trace_id'   => empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id,
            'pin'        => $this->encryptPin($pin),
        ];

        return $this->res($body);
    }

    /**
     * @param string $assetId
     * @param string $opponentId
     * @param        $pin
     * @param        $amount
     * @param string $memo
     * @param null   $trace_id
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function transfer(string $assetId, string $opponentId, $pin, $amount, $memo = '', $trace_id = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            'asset_id'    => $assetId,
            'opponent_id' => $opponentId,
            'amount'      => (string)$amount,
            'pin'         => $this->encryptPin($pin),
            'trace_id'    => empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id,
            'memo'        => $memo,
        ];

        $headers = [
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
        $limit   = empty($limit) ? $limit : (int)$limit;
        $urlArgv = compact('limit', 'offset', 'asset', 'order');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));

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
        $limit   = empty($limit) ? $limit : (int)$limit;
        $urlArgv = compact('limit', 'offset', 'asset', 'order');

        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));

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
     * @deprecated
     * @param string|null $offset
     * @param int|null    $limit
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readMultisigs(string $offset = '', $limit = null): array
    {
        $limit   = empty($limit) ? 100 : (int)$limit;
        $urlArgv = compact('limit', 'offset');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));

        return $this->res([], $url);
    }

    /**
     * @deprecated
     * @param string $access_token
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
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
    public function accessTokenPostOutputs($access_token, $receivers, $index = 0,$hint = ""): array
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
     * @param  array  $receivers
     * @param  int  $index
     * @param  string  $hint
     * @return array
     */
    public function readOutputs($receivers, $index = 0,$hint = ""): array
    {
        if ($hint === "") {
            $hint = Uuid::uuid4()->toString();
        }
        $body = compact('receivers', 'index','hint');

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
     * @deprecated
     * @param string $raw
     * @param string $action
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
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
     * @param string $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function multisigsRequestsSign(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        $body = [
            'pin' => $this->encryptPin((string)$pin),
        ];

        return $this->res($body, $url);
    }

    /**
     * @param string $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function multisigsRequestsCancel(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        $body = [
            'pin' => $this->encryptPin((string)$pin),
        ];

        return $this->res($body, $url);
    }

    /**
     * @param string $request_id
     * @param string|null $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function multisigsRequestsUnlock(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $url = str_replace('{$requestId}', $request_id, $this->endPointUrl);

        $body = [
            'pin' => $this->encryptPin((string)$pin),
        ];

        return $this->res($body, $url);
    }

    /**
     * @deprecated
     * @param string $request_id
     * @param string $pin
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function multisigsSign(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

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
     */
    public function multisigsCancel(string $request_id, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

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

        $body = [
            'asset_id'          => $asset_id,
            'opponent_multisig' => $opponent_multisig,
            'amount'            => $amount,
            'pin'               => $this->encryptPin($pin),
            'trace_id'          => empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id,
            'memo'              => $memo,
        ];

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

        $body = [
            'asset_id'     => $asset_id,
            'opponent_key' => $opponent_key,
            'amount'       => $amount,
            'pin'          => $this->encryptPin($pin),
            'trace_id'     => empty($trace_id) ? Uuid::uuid4()->toString() : $trace_id,
            'memo'         => $memo,
        ];

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
    public function readMultisigsOutputs(string $offset = '', array $members, $state = '', $threshold = 2, $limit = '500')
    {
        sort($members);
        $members = hash('sha3-256', implode('', $members));
        $limit   = empty($limit) ? 100 : (int) $limit;
        $urlArgv = compact('limit', 'offset', 'state', 'members', 'threshold');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));
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
    public function accessTokenReadMultisigsOutputs($access_token, string $offset = '', array $members, $state = '', $threshold = 2, $limit = '500')
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        sort($members);
        $members = hash('sha3-256', implode('', $members));
        $body = compact('limit', 'offset', 'state', 'members', 'threshold');

        return $this->res($body, null, $headers);
    }

    /**
     * @param string $request_id
     * @param string $action
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function multisigsRequestsAction(string $request_id, $action = 'sign'): array
    {
        $url = str_replace(['{$requestId}','{$action}'], [$request_id, $action], $this->endPointUrl);

        return $this->res($body, $url);
    }

    /**
     * @param string $access_token
     * @param string $request_id
     * @param string $action
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function accessTokenMultisigsRequestsAction(string $access_token, string $request_id, $action = 'sign'): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = str_replace(['{$requestId}','{$action}'], [$request_id, $action], $this->endPointUrl);

        return $this->res($body, $url, $headers);
    }
}
