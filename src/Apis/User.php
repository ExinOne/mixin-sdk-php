<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:52
 */

namespace ExinOne\MixinSDK\Apis;

use Base64Url\Base64Url;
use ExinOne\MixinSDK\Exceptions\NeedTIPPINException;
use ExinOne\MixinSDK\Exceptions\NotFoundConfigException;
use ExinOne\MixinSDK\Utils\TIPService;
use Ramsey\Uuid\Uuid;

class User extends Api
{
    /**
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readProfile(): array
    {
        return $this->res();
    }

    public function safeReadProfile(): array
    {
        return $this->res();
    }

    /**
     * @param string $full_name
     * @param string $avatar_base64
     *
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function updateProfile(string $full_name, string $avatar_base64 = ''): array
    {
        $body = compact('full_name', 'avatar_base64');

        return $this->res($body);
    }

    /**
     * @param $receive_message_source
     * @param $accept_conversation_source
     *
     * @return mixed
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function updatePreferences(string $receive_message_source, string $accept_conversation_source): array
    {
        $body = compact('receive_message_source', 'accept_conversation_source');

        return $this->res($body);
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function rotateQRCode(): array
    {
        return $this->res();
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException
     */
    public function readFriends(): array
    {
        return $this->res();
    }

    /**
     * @param string $user_id
     * @return array
     * @throws \Exception
     */
    public function addFavoriteApp(string $user_id): array
    {
        $url = str_replace('{$userId}', $user_id, $this->endPointUrl);
        return $this->res([], $url);
    }

    /**
     * @param string $user_id
     * @return array
     * @throws \Exception
     */
    public function removeFavoriteApp(string $user_id): array
    {
        $url = str_replace('{$userId}', $user_id, $this->endPointUrl);
        return $this->res([], $url);
    }

    /**
     * @param string $user_id
     * @return array
     * @throws \Exception
     */
    public function readFavoriteApps(string $user_id = null): array
    {
        if (! $user_id) {
            $user_id = $this->config['client_id'];
        }
        $url = str_replace('{$userId}', $user_id, $this->endPointUrl);
        return $this->res([], $url);
    }

    public function safeRegister(string $safe_private_key, string $pin = null): array
    {
        if ($pin === null) {
            $pin = $this->config['pin'];
        }

        $client_id = $this->config['client_id'];

        if (! $client_id) {
            throw new NotFoundConfigException('missing parameter client_id');
        }

        if (! TIPService::isTIPPin($pin)) {
            throw new NeedTIPPINException('please upgrade to TIP PIN before register');
        }

        $public_base64url = TIPService::getPublicFromEd25519KeyPair($safe_private_key);
        $public_bin       = Base64Url::decode($public_base64url);
        $public_hex       = bin2hex($public_bin);
        $sig_raw          = TIPService::signWithEd25519($safe_private_key, hash('sha3-256', $client_id, true));

        $body = [
            'public_key' => $public_hex,
            'signature'  => Base64Url::encode($sig_raw),
            'pin_base64' => $this->encryptTIPPin($pin, 'SEQUENCER:REGISTER:', $client_id, $public_hex),
        ];

        return $this->res($body);
    }
}
