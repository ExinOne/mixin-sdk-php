<?php

return [
    // API 基础URL
    'base_uri'  => 'https://api.mixin.one',

    // api endpoints
    'endpoints' => [
        'read_profile'                     => [
            'method' => 'get',
            'url'    => '/me',
        ],
        'update_profile'                   => [
            'method' => 'post',
            'url'    => '/me',
        ],
        'update_preferences'               => [
            'method' => 'post',
            'url'    => '/me/preferences',
        ],
        'read_user'                        => [
            'method' => 'get',
            'url'    => '/users/',  //后面需要填参数
        ],
        'read_users'                       => [
            'method' => 'post',
            'url'    => '/users/fetch',
        ],
        'search_user'                      => [
            'method' => 'get',
            'url'    => '/search/',  //后面需要填参数
        ],
        'update_pin'                       => [
            'method' => 'post',
            'url'    => '/pin/update',
        ],
        'verify_pin'                       => [
            'method' => 'post',
            'url'    => '/pin/verify',
        ],
        'rotate_qrcode'                    => [
            'method' => 'get',
            'url'    => '/me/code',
        ],
        'deposit'                          => [
            'method' => 'get',
            'url'    => '/assets/',  //后面需要填参数
        ],
        'withdrawal'                       => [
            'method' => 'post',
            'url'    => '/withdrawals',
        ],
        'transfer'                         => [
            'method' => 'post',
            'url'    => '/transfers',
        ],
        'verify_payment'                   => [
            'method' => 'post',
            'url'    => '/payments',
        ],
        'read_transfer'                    => [
            'method' => 'get',
            'url'    => '/transfers/trace/{$traceId}',
        ],
        'create_address'                   => [
            'method' => 'post',
            'url'    => '/addresses',
        ],
        'read_addresses'                   => [
            'method' => 'get',
            'url'    => '/assets/{$assetId}/addresses',
        ],
        'delete_address'                   => [
            'method' => 'post',
            'url'    => '/addresses/{$addressId}/delete',
        ],
        'read_address'                     => [
            'method' => 'get',
            'url'    => '/addresses/',  //后面需要填参数
        ],
        'read_assets'                      => [
            'method' => 'get',
            'url'    => '/assets',
        ],
        'read_asset'                       => [
            'method' => 'get',
            'url'    => '/assets/',  //后面需要填参数
        ],
        'read_asset_fee'                   => [
            'method' => 'get',
            'url'    => '/assets/{$assetId}/fee',
        ],
        'read_friends'                     => [
            'method' => 'get',
            'url'    => '/friends',
        ],
        'read_network_asset'               => [
            'method' => 'get',
            'url'    => '/network/assets/',  //后面需要填参数
        ],
        'read_network_snapshots'           => [
            'method' => 'get',
            'url'    => '/network/snapshots',
        ],
        'read_network_snapshot'                    => [
            'method' => 'get',
            'url'    => '/network/snapshots/',  //后面需要填参数
        ],
        'read_user_snapshots'              => [
            'method' => 'get',
            'url'    => '/snapshots',
        ],
        'read_user_snapshot'               => [
            'method' => 'get',
            'url'    => '/snapshots/',  //后面需要填参数
        ],
        'create_user'                      => [
            'method' => 'post',
            'url'    => '/users',
        ],
        'mixin_network_chains_sync_status' => [
            'method' => 'get',
            'url'    => '/network',
        ],
        'top_asset'                        => [
            'method' => 'get',
            'url'    => '/network',
        ],
        'create_attachments'               => [
            'method' => 'post',
            'url'    => '/attachments',
        ],
        'create_conversations'             => [
            'method' => 'post',
            'url'    => '/conversations',
        ],

    ],
];
