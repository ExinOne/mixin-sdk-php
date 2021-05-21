<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:55
 */

namespace ExinOne\MixinSDK\Apis;

use Ramsey\Uuid\Uuid;

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
    public function createUser($fullName, string $key_algorithm = 'RS512'): array
    {
        if ($key_algorithm === 'Ed25519') {
            [$priKey, $pubKey, $session_secret] = $this->generateEdDSAKey();
        } else {
            [$priKey, $pubKey, $session_secret] = $this->generateSSLKey();
        }
        $body = [
            'session_secret' => $session_secret,
            'full_name'      => (string) $fullName,
        ];

        return $this->res($body, null, [], compact('priKey', 'pubKey'));
    }

    /**
     * @param string|null $asset
     * @param string|null $destination
     * @param int|null    $limit
     * @param string|null $offset
     * @param string|null $tag
     * @return array
     * @throws \Exception
     */
    public function externalTransactions(string $asset = null, string $destination = null, $limit = null, string $offset = null, string $tag = null): array
    {
        $limit = empty($limit) ? $limit : (int) $limit;

        $urlArgv = compact('asset', 'limit', 'offset', 'destination', 'tag');

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

    /**
     * @param string      $category
     * @param array       $participants
     * @param string|null $conversation_id
     * @param string      $name
     *
     * @return array
     * @throws \Exception
     */
    public function createConversations(string $category, array $participants, string $conversation_id = null, string $name = ''): array
    {
        if (empty($conversation_id)) {
            $conversation_id = $category == 'GROUP'
                ? Uuid::uuid4()->toString()
                : $this->uniqueConversationId($participants[0]['user_id'], $this->config['client_id']);
        }

        $body = [
            'category'        => $category,
            'conversation_id' => $conversation_id,
            'participants'    => $participants,
            'name'            => $name
        ];

        return $this->res($body);
    }

    /**
     * @param string $conversation_id
     *
     * @return array
     * @throws \Exception
     */
    public function readConversations(string $conversation_id): array
    {
        $url = $this->endPointUrl.$conversation_id;

        return $this->res([], $url);
    }

    /**
     * @param string $conversation_id
     * @param array $participants
     * @param string $action
     * @return array
     * @throws \Exception
     */
    public function participantsActions(string $conversation_id, array $participants, string $action = "ADD"): array
    {
        $body = $participants;

        $url = str_replace(['{$conversationId}','{$action}'], [$conversation_id, $action], $this->endPointUrl);
        return $this->res($body, $url);
    }

    public function rotateConversation(string $conversation_id): array
    {
        $url = str_replace('{$conversationId}', $conversation_id, $this->endPointUrl);
        return $this->res(null, $url);
    }

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

    /**
     * @return mixed
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function multisigAsset(): array
    {
        return $this->res();
    }

    /**
     * @param string $code
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function requestAccessToken(string $code)
    {
        $body = [
            'client_id'     => $this->config['client_id'],
            'code'          => $code,
            'client_secret' => $this->config['client_secret'],
        ];

        return $this->res($body);
    }

    /**
     * @param string $access_token
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetInfo(string $access_token)
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res(null, null, $headers);
    }

    /**
     * @param string $access_token
     *
     * @return array
     * @throws \Exception
     */
    public function accessTokenGetAssets(string $access_token)
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res(null, null, $headers);
    }

    /**
     * @param string $access_token
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetAddresses(string $access_token, string $assetId): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = str_replace('{$assetId}', $assetId, $this->endPointUrl);

        return $this->res([], $url, $headers);
    }

    /**
     * @param string $access_token
     * @param string $addressId
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function accessTokenGetAddress(string $access_token, string $addressId): array
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->endPointUrl.$addressId;

        return $this->res([], $url, $headers);
    }

    /**
     * @param string $access_token
     *
     * @return array
     * @throws \Exception
     */
    public function accessTokenGetContacts(string $access_token)
    {
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res(null, null, $headers);
    }

    /**
     * @param string $q
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function searchAssets(string $q): array
    {
        $url = $this->endPointUrl.$q;
        return $this->res([], $url);
    }

    /**
     * @param string $access_token
     * @param string $assetId
     *
     * @return array
     * @throws \Exception
     */
    public function accessTokenGetAsset(string $access_token, string $assetId): array
    {
        $url = $this->endPointUrl.$assetId;

        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        return $this->res([], $url, $headers);
    }
}
