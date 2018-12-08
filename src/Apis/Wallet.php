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
     * @param string $public_key
     * @param        $pin
     * @param        $label
     * @param bool   $isEOS
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function createAddress(string $asset_id, string $public_key, $pin, $label, bool $isEOS = false): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        if (! $isEOS) {
            $body = [
                'asset_id'   => $asset_id,
                'public_key' => $public_key,
                'label'      => $label,
                'pin'        => $pin == '' ? '' : $this->encryptPin((string) $pin),
            ];
        } else {
            $body = [
                'asset_id'     => $asset_id,
                'account_name' => $label,
                'account_tag'  => $public_key,
                'pin'          => $pin == '' ? '' : $this->encryptPin((string) $pin),
            ];
        }

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
            "pin" => $pin == '' ? '' : $this->encryptPin((string) $pin),
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
     * @param null   $tracd_id
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function withdrawal(string $addressId, $amount, $pin, $memo = '', $tracd_id = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            "address_id" => $addressId,
            "amount"     => (string) $amount,
            "memo"       => $memo,
            "trace_id"   => empty($tracd_id) ? Uuid::uuid4()->toString() : $tracd_id,
            "pin"        => $this->encryptPin($pin),
        ];

        return $this->res($body);
    }

    /**
     * @param string $assetId
     * @param string $opponentId
     * @param        $pin
     * @param        $amount
     * @param string $memo
     * @param null   $tracd_id
     *
     * @return array
     * @throws \ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException
     */
    public function transfer(string $assetId, string $opponentId, $pin, $amount, $memo = '', $tracd_id = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $body = [
            "asset_id"    => $assetId,
            "opponent_id" => $opponentId,
            "amount"      => (string) $amount,
            "pin"         => $this->encryptPin($pin),
            "trace_id"    => empty($tracd_id) ? Uuid::uuid4()->toString() : $tracd_id,
            "memo"        => $memo,
        ];

        $headers = [
            "Mixin-Device-Id" => $this->config['session_id'],
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
}
