<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:55
 */

namespace ExinOne\MixinSDK\Apis;

use Ramsey\Uuid\Uuid;
use Wrench\Client;
use Wrench\Protocol\Protocol;

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
            "full_name"      => (string) $fullName,
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

    /**
     * @param string      $category
     * @param array       $participants
     * @param string|null $conversation_id
     *
     * @return array
     * @throws \Exception
     */
    public function createConversations(string $category, array $participants, string $conversation_id = null): array
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
        ];

        return $this->res($body);
    }

    /**
     * @param string $conversation_id
     *
     * @return array
     * @throws \Exception
     */
    public function readConversations(string $conversation_id)
    {
        $url = $this->endPointUrl.$conversation_id;

        return $this->res([], $url);
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

    //-------
    //-------
    public function webs($user_id)
    {
        $client = new Client("wss://blaze.mixin.one/", 'https://google.com');

        $client->addRequestHeader('Authorization', 'Bearer '.$this->getToken('GET', '/', ""));
        $client->addRequestHeader('protocol', 'Mixin-Blaze-1');
        $client->connect();

        $message = json_encode([
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $this->uniqueConversationId($user_id, $this->config['client_id']),
                //'recipient_id'    => $user_id,
                "status"          => "SENT",
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'PLAIN_TEXT',
                'data'            => base64_encode('lalala'),
            ],
        ]);

        //dump($message);

        //dd(base64_encode(gzcompress($message)));

        $client->sendData(gzencode($message), Protocol::TYPE_BINARY);
        sleep(1);
        $response = $client->receive()[0]->getPayload();
        $client->disconnect();
        dd(gzdecode($response));
        //dd($response);  // Will output 'Hello WebSocket.org!'
        //dd(1);

        //$client = new Client('wss://blaze.mixin.one/');
        //$client->d

        //$client = new Client('wss://blaze.mixin.one/','');
        //$client->addRequestHeader('Authorization','Bearer '.$this->getToken('GET','/', ''));

        exit();
    }
}
