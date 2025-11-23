<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-16
 * Time: 下午10:05
 */

namespace ExinOne\MixinSDK;

use ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException;
use GuzzleHttp\Client;

/**
 * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
 * @see \ExinOne\MixinSDK\Apis\Pin
 * @method  array updatePin($oldPin, $pin): array
 * @method  array verifyPin($pin): array
 *
 * @see \ExinOne\MixinSDK\Apis\User
 * @method  array readProfile(): array
 * @method  array updateProfile(string $full_name, string $avatar_base64 = ''): array
 * @method  array updatePreferences(string $receive_message_source, string $accept_conversation_source): array
 * @method  array rotateQRCode(): array
 * @method  array readFriends(): array
 * @method  array addFavoriteApp(string $user_id): array
 * @method  array removeFavoriteApp(string $user_id): array
 * @method  array readFavoriteApps(string $user_id = null): array
 * @method  array safeRegister(string $safe_private_key, string $pin = null): array
 *
 * @see \ExinOne\MixinSDK\Apis\Network
 * @method  array readUser(string $userId): array
 * @method  array readUsers(array $userIds): array
 * @method  array searchUser($item): array
 * @method  array readNetworkAsset(string $assetId): array
 * @method  array readNetworkSnapshots(int $limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
 * @method  array readNetworkSnapshot(string $snapshotId): array
 * @method  array createUser($fullName, string $key_algorithm = 'RS512'): array
 * @method  array externalTransactions(string $asset = null, string $destination = null, $limit = null, string $offset = null, string $tag = null, string $transaction_hash = null, string $source = null, string $user = null): array
 * @method  array createAttachments(): array
 * @method  array createConversations(string $category, array $participants, string $conversation_id = null, string $name = ''): array
 * @method  array readConversations(string $conversation_id): array
 * @method  array participantsActions(string $conversation_id, array $participants, string $action): array
 * @method  array rotateConversation(string $conversation_id): array
 * @method  array mixinNetworkChainsSyncStatus(): array
 * @method  array chains(): array
 * @method  array topAsset(): array
 * @method  array multisigAsset(): array
 * @method  array requestAccessToken(string $code): array
 * @method  array accessTokenGetInfo(string $access_token): array
 * @method  array accessTokenGetAssets(string $access_token): array
 * @method  array accessTokenGetAddresses(string $access_token, string $assetId): array
 * @method  array accessTokenGetAddress(string $access_token, string $addressId): array
 * @method  array accessTokenGetContacts(string $access_token): array
 * @method  array searchAssets(string $q): array
 * @method  array accessTokenGetAsset(string $access_token, string $assetId): array
 * @method  array readHistoricalPrices(string $asset, string $offset): array
 * @method  array readSnapshotsByTrace(string $traceId): array
 *
 * @see \ExinOne\MixinSDK\Apis\Wallet
 * @method  array createAddress(string $asset_id, string $destination, ?string $pin, string $label, string $tag = ''): array
 * @method  array createAddressRaw(string $asset_id, $public_key, $label, $account_name, $account_tag, $pin = null): array
 * @method  array readAddresses(string $assetId): array
 * @method  array readAddress(string $addressId): array
 * @method  array deleteAddress(string $address_id, string $pin = null): array
 * @method  array readAssets(): array
 * @method  array readAsset(string $assetId): array
 * @method  array deposit(string $assetId): array
 * @method  array withdrawal(string $addressId, $amount, $pin, string $memo, $trace_id = null): array
 * @method  array transfer(string $assetId, string $opponentId, $pin, $amount, string $memo, $trace_id = null): array
 * @method  array verifyPayment(string $asset_id, string $opponent_id, $amount, string $trace_id): array
 * @method  array readTransfer(string $traceId): array
 * @method  array readAssetFee(string $assetId): array
 * @method  array readUserSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
 * @method  array readUserSnapshot(string $snapshotId): array
 * @method  array accessTokenGetUserSnapshots(string $access_token, $limit = null, string $offset = null, string $asset = '', string $order = 'DESC'): array
 * @method  array accessTokenGetUserSnapshot(string $access_token, string $snapshot_id): array
 * @method  array accessTokenGetTransfer(string $access_token, string $trace_id): array
 * @method  array readRawMainNetAddress(string $client_id): array
 * @method  array generateGhostKeys(array $client_ids, int $index = null, string $hint = null): array
 * @method  array multisigPayment(string $asset_id, array $receivers, int $threshold, $amount, $memo = '', $trace_id = null): array
 * @method  array checkCode($code_id): array
 * @method  array accessTokenPostOutputs($access_token, $receivers, $index = 0): array
 * @method  array readOutputs($receivers, $index = 0): array
 * @method  array externalProxy($params, $method = 'sendrawtransaction'): array
 * @method  array multisigsRequests(string $raw, string $action = 'sign'): array
 * @method  array accessTokenMultisigsRequests(string $access_token, string $raw, string $action = 'sign'): array
 * @method  array multisigsRequestsSign(string $request_id, string $pin = null): array
 * @method  array multisigsRequestsUnlock(string $request_id, string $pin = null): array
 * @method  array multisigsRequestsCancel(string $request_id): array
 * @method  array accessTokenMultisigsRequestsAction(string $access_token, string $request_id, $action = 'sign'): array
 * @method  array readFiats(): array
 * @method  array multisigsCancel(string $request_id, string $pin = null): array
 * @method  array sendMultisigTransactions(string $asset_id, array $receivers, int $threshold, string $amount, string $pin = null, string $trace_id = null, string $memo = null): array
 * @method  array sendMainnetTransactions(string $asset_id, string $opponent_key, string $amount, string $pin = null, string $trace_id = null, string $memo = null): array
 * @method  array safeFetchDepositEntries(string $chain_uuid, array $members, int $threshold): array
 * @method  array safeReadDeposits(string $asset_uuid = null, string $destination = null, string $tag = null, string $offset = null, int $limit = 500): array
 * @method  array safeReadOutputs(array $members_array = null, int $threshold = null, int $offset_sequence = null, int $limit = 500, string $asset = null, string $state = null, string $order = 'ASC', string $app = null): array asset_uuid and asset_hash would both work for asset
 * @method  array safeReadAssets(): array
 * @method  array safeFetchKeys(array $receiver_info): array
 * @method  array safeRequestTransaction(array $transaction, string $request_id): array
 * @method  array safeRequestTransactions(array $transactions, array $request_ids): array
 * @method  array safeSendTransaction(array $transaction, array $views, string $trace_id = null, string $spent_key = null, bool $use_32_bits = false): array
 * @method  array safeSendTransactions(array $transactions, array $views_arr, array $trace_ids, string $spent_key = null, bool $use_32_bits = false): array
 * @method  array safeReadTransaction(string $request_id): array
 * @method  array safeReadSnapshots(string $asset_uuid = null, string $app = null, string $opponent = null, string $offset = null, int $limit = 500): array
 * @method  array accessTokenSafeReadOutputs(string $access_token, array $members_array = null, int $threshold = null, int $offset_sequence = null, int $limit = 500, string $asset_hash = null, string $state = null, string $order = 'ASC'): array
 * @method  array accessTokenSafeReadTransaction(string $access_token, string $request_id): array
 * @method  array accessTokenSafeReadSnapshots(string $access_token, string $asset_uuid = null, string $app = null, string $opponent = null, string $offset = null, int $limit = 500): array
 * @method  array safeMultisigCreateRequests(array $array): array
 * @method  array safeMultisigCreateRequest(array $raw, string $request_id): array
 * @method  array safeMultisigReadRequests(string $id_or_hash): array
 * @method  array safeMultisigSignRequest(array $transaction, array $views, string $request_id, int $signer_position, string $spent_key = null, bool $use_32_bits = false): array
 * @method  array safeMultisigUnlockRequest(string $request_id): array
 * @method  array safeReadAssetWithdrawFees(string $asset_id, string $destination = null): array
 * @method  array safeFetchAssets(array $asset_ids): array
 *
 * @see \ExinOne\MixinSDK\Apis\Message
 * @method  array sendText($user_id, $data, $category = 'CONTACT', $conversation_id = null): array
 * @method  array sendContact($user_id, $contact_id, $category = 'CONTACT', $conversation_id = null): array
 * @method  array sendAppButtonGroup($user_id, $data, $category = 'CONTACT', $conversation_id = null): array
 * @method  array sendAppCard($user_id, $data, $category = 'CONTACT', $conversation_id = null): array
 * @method  array askMessageReceipt($message_id): array
 * @method  array sendBatchMessage(array $user_ids, $data, $use_http = false, $type = 'PLAIN_TEXT', $conversation_id = null): array
 *
 * @see \ExinOne\MixinSDK\Apis\Pin
 * @see \ExinOne\MixinSDK\Apis\Wallet
 * @see \ExinOne\MixinSDK\Apis\Network
 * @see \ExinOne\MixinSDK\Apis\User
 * @see \ExinOne\MixinSDK\Apis\Message
 *
 */
class Container
{
    protected $detailClass;

    protected $raw = false;

    protected $is_with_headers = false;

    protected $is_return_access_token = false;

    protected $http_async = false;

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function __call($name, $arguments)
    {
        $this->detailClass->init($name);

        // 调用对象的$name 方法,获得需要发送的 header 和 body
        ['content' => $content, 'customize_res' => $customize_res, 'auth_token' => $auth_token, 'promise' => $promise, 'headers' => $headers]
            = call_user_func_array([$this->detailClass, $name], $arguments);

        if ($this->isReturnAccessToken()) {
            return $auth_token;
        } elseif ($this->isHttpAsync()) {
            return $promise;
        } elseif (! $this->isRaw() && ($content['error'] ?? 0)) {
            // 出现异常
            $error       = $content['error'];
            $code        = isset($error['code']) ? $error['code'] : 404;
            $description = isset($error['description']) ? $error['description'] : '';
            $extra       = isset($error['extra']) ? $error['extra'] : '';
            $this->boomRoom($code, $description, $extra, $headers);
        } elseif ($this->isRaw()) {
            $_h = [];
            if ($this->is_with_headers) {
                $_h = ['headers' => $headers];
            }
            return array_merge($content ?? [], $customize_res, $_h);
        } else {
            return array_merge($content['data'] ?? [], $customize_res ?? null);
        }
    }

    /**
     * @param $detailClass
     *
     * @return $this
     */
    public function setDetailClass($detailClass)
    {
        $this->detailClass = $detailClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailClass()
    {
        return $this->detailClass;
    }

    /**
     * @param bool $raw
     *
     * @return $this
     */
    public function setRaw(bool $raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * only works when is raw
     *
     * @return $this
     */
    public function setWithHeaders(bool $is_with_headers = true)
    {
        $this->is_with_headers = $is_with_headers;

        return $this;
    }

    /**
     * @param bool $is_return_access_token
     *
     * @return $this
     */
    public function setReturnAccessToken(bool $is_return_access_token)
    {
        $this->is_return_access_token = $is_return_access_token;

        $this->detailClass->setReturnAccessToken($is_return_access_token);

        return $this;
    }

    /**
     * @param Client|false  $http_client
     * @param \Closure|null $on_resolve
     * @param \Closure|null $on_reject
     * @return $this
     */
    public function setHttpAsync($http_client = false, \Closure $on_resolve = null, \Closure $on_reject = null)
    {
        if ($http_client) {
            $this->http_async = true;
            if (! $on_resolve) {
                $on_resolve = function () {
                };
            }
            if (! $on_reject) {
                $on_reject = function () {
                };
            }

            $this->detailClass->setHttpAsync($http_client, $on_resolve, $on_reject);
        } else {
            $this->http_async = false;

            $this->detailClass->setHttpAsync(null);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isReturnAccessToken(): bool
    {
        return $this->is_return_access_token;
    }

    /**
     * @return bool
     */
    public function isHttpAsync(): bool
    {
        return $this->http_async;
    }

    /**
     * @return bool
     */
    public function getHttpClient(): Client
    {
        return $this->detailClass->getHttpClient();
    }


    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->detailClass->setTimeout($timeout);

        return $this;
    }

    public function setProxy(string $proxy = null)
    {
        $this->detailClass->setProxy($proxy);

        return $this;
    }

    /**
     * @param int $expire
     *
     * @return $this
     */
    public function setExpire(int $expire)
    {
        $this->detailClass->setExpire($expire);

        return $this;
    }

    /**
     * @param string $aud
     * @return $this
     */
    public function setAud(string $aud)
    {
        $this->detailClass->setAud($aud);

        return $this;
    }

    /**
     * @param $iterator
     *
     * @return $this
     */
    public function setIterator(array $iterator)
    {
        $this->detailClass->setIterator($iterator);

        return $this;
    }

    /**
     * 爆炸室
     *
     * @param $errorCode
     * @param $description
     *
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function boomRoom($errorCode, $description, $extra, $headers = [])
    {
        $extra = json_encode($extra);
        $id    = $headers['X-Request-Id'][0] ?? '';
        throw new MixinNetworkRequestException("{$description}, {$extra}, {$id}", $errorCode);
    }
}
