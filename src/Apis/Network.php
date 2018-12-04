<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:55
 */

namespace ExinOne\MixinSDK\Apis;

class Network extends Api
{
    /**
     * @param string $userId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readUser(string $userId): array
    {
        $url = $this->endPointUrl.$userId;

        return $this->res(null, $url);
    }

    /**
     * @param array $userIds
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readUsers(array $userIds): array
    {
        return $this->res($userIds);
    }

    /**
     * @param $item
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function searchUser($item): array
    {
        $url = $this->endPointUrl.(string) $item;

        return $this->res([], $url);
    }

    /**
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readNetworkAsset(string $assetId): array
    {
        $url = $this->endPointUrl.$assetId;

        return $this->res([], $url);
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
    public function readNetworkSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
    {
        $limit = empty($limit) ? $limit : (int) $limit;

        $urlArgv = compact('limit', 'offset', 'asset', 'order');

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));
        dump($url);

        return $this->res([], $url);
    }

    /**
     * @param string $snapshotId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readNetworkSnapshot(string $snapshotId): array
    {
        $url = $this->endPointUrl.$snapshotId;

        return $this->res([], $url);
    }

    /**
     * @param string $fullName
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function createUser($fullName): array
    {
        [$priKey, $pubKey, $session_secret] = $this->generateSSLKey();
        $body = [
            "session_secret" => $session_secret,
            "full_name"      => (string)$fullName,
        ];

        return $this->res($body, null, [], compact('priKey', 'pubKey'));
    }

    /**
     * @param string|null $asset
     * @param string|null $public_key
     * @param int|null    $limit
     * @param string|null $offset
     * @param string|null $account_name
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function externalTransactions(string $asset = null, string $public_key = null, $limit = null, string $offset = null, string $account_name = null): array
    {
        $limit = empty($limit) ? $limit : (int) $limit;
        if (empty($account_name)) {
            $urlArgv = compact('asset', 'public_key', 'limit', 'offset', 'account_name');
        } else {
            $account_tag = $public_key;
            $urlArgv     = compact('asset', 'limit', 'offset', 'account_tag', 'account_name');
        }

        $url = $this->endPointUrl.'?'.http_build_query(delEmptyItemInArray($urlArgv));

        return $this->res([], $url);
    }

    /**
     * @return mixed
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function createAttachments(): array
    {
        return $this->res();
    }

    // TODO
    //public function createConversations()
    //{

    //}

    // TODO
    //public function readConversations()
    //{

    //}

    /**
     * @return mixed
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function mixinNetworkChainsSyncStatus(): array
    {
        return $this->res();
    }

    /**
     * @return mixed
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function topAsset(): array
    {
        return $this->res();
    }
}
