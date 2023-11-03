<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:52
 */

namespace ExinOne\MixinSDK\Apis;

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
        $url = str_replace('{$userId}', $user_id, $this->getEndPointUrl());
        return $this->res([], $url);
    }

    /**
     * @param string $user_id
     * @return array
     * @throws \Exception
     */
    public function removeFavoriteApp(string $user_id): array
    {
        $url = str_replace('{$userId}', $user_id, $this->getEndPointUrl());
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
        $url = str_replace('{$userId}', $user_id, $this->getEndPointUrl());
        return $this->res([], $url);
    }
}
