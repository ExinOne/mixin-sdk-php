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
     * @param string $destination   BTC address or EOS account name like ‘eoswithmixin’
     * @param $pin
     * @param $label    “Mixin”, can’t be blank, max size 64
     * @param $tag  can be blank, EOS account tag or memo
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
                    'pin'        => $pin == '' ? '' : $this->encryptPin((string) $pin),
                ];
            } else {
                $body = [
                    'asset_id'     => $asset_id,
                    'account_name' => $destination,
                    'account_tag'  => $label,
                    'pin'          => $pin == '' ? '' : $this->encryptPin((string) $pin),
                ];
            }

        } else {
            $body = [
                'asset_id'    => $asset_id,
                'label'       => $label,
                'pin'         => $pin == '' ? '' : $this->encryptPin((string) $pin),
                'destination' => $destination,
                'tag'         => $tag,
            ];
        }

        return $this->res($body);
    }

    /**
     * @deprecated 由于支持新的 alpha 创建地址api，该方法遗弃
     * @param string $asset_id
     * @param        $public_key
     * @param        $label
     * @param        $account_name
     * @param        $account_tag
     * @param null   $pin
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function createAddressRaw(string $asset_id,  $public_key,  $label,  $account_name,  $account_tag, $pin = null)
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
    public function deleteAddress(string $addressId, $pin): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            'pin' => $pin == '' ? '' : $this->encryptPin((string) $pin),
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
            'amount'     => (string) $amount,
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
            'amount'      => (string) $amount,
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
        $amount = (string) $amount;
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
        $limit   = empty($limit) ? $limit : (int) $limit;
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
        $limit   = empty($limit) ? $limit : (int) $limit;
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
     * @param string $snapshot_id
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

    public function readRawMainNetAddress(string $client_id): array
    {
        $body = [$client_id];

        return $this->res($body);
    }
}
