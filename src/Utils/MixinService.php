<?php

namespace ExinOne\MixinSDK\Utils;

class MixinService
{
    public static function formatConfigFromCreateUser(array $info): array
    {
        return [
            'mixin_id'      => $info['identity_number'],
            'client_id'     => $info['user_id'],
            'client_secret' => '',
            'pin'           => '',
            'pin_token'     => $info['pin_token_base64'],
            'session_id'    => $info['session_id'],
            'private_key'   => $info['priKey'],
        ];
    }
}