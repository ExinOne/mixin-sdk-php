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
        'create_address_raw'               => [
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
        'read_network_snapshot'            => [
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
        'chains' => [
            'method' => 'get',
            'url'    => '/network/chains',
        ],
        'top_asset'                        => [
            'method' => 'get',
            'url'    => '/network/assets/top',
        ],
        'multisig_asset'                   => [
            'method' => 'get',
            'url'    => '/network/assets/multisig',
        ],
        'create_attachments'               => [
            'method' => 'post',
            'url'    => '/attachments',
        ],
        'create_conversations'             => [
            'method' => 'post',
            'url'    => '/conversations',
        ],
        'read_conversations'               => [
            'method' => 'get',
            'url'    => '/conversations/', //后面需要填参数
        ],
        'participants_actions'             => [
            'method' => 'post',
            'url'    => '/conversations/{$conversationId}/participants/{$action}',
        ],
        'rotate_conversation'              => [
            'method' => 'post',
            'url'    => '/conversations/{$conversationId}/rotate',
        ],
        'external_transactions'            => [
            'method' => 'get',
            'url'    => '/external/transactions',
        ],

        //
        'request_access_token'             => [
            'method' => 'post',
            'url'    => '/oauth/token',
        ],
        'access_token_get_info'            => [
            'method' => 'get',
            'url'    => '/me',
        ],
        'access_token_get_assets'          => [
            'method' => 'get',
            'url'    => '/assets',
        ],
        'access_token_get_addresses'       => [
            'method' => 'get',
            'url'    => '/assets/{$assetId}/addresses',
        ],
        'access_token_get_address'         => [
            'method' => 'get',
            'url'    => '/addresses/',  //后面需要填参数
        ],
        'access_token_get_contacts'        => [
            'method' => 'get',
            'url'    => '/friends',
        ],
        'access_token_get_user_snapshots'  => [
            'method' => 'get',
            'url'    => '/snapshots',
        ],
        'access_token_get_user_snapshot'   => [
            'method' => 'get',
            'url'    => '/snapshots/',  //后面需要填参数
        ],
        'access_token_get_transfer'        => [
            'method' => 'get',
            'url'    => '/transfers/trace/{$traceId}',
        ],
        'search_assets'                    => [
            'method' => 'get',
            'url'    => '/network/assets/search/', //后面需要填参数
        ],
        'send_batch_message'               => [
            'method' => 'post',
            'url'    => '/messages',
        ],
        'read_raw_main_net_address'        => [
            'method' => 'post',
            'url'    => '/outputs',
        ],
        'multisig_payment'                 => [
            'method' => 'post',
            'url'    => '/payments',
        ],
        'check_code'                       => [
            'method' => 'get',
            'url'    => '/codes/',
        ],
        'access_token_post_outputs'        => [
            'method' => 'post',
            'url'    => '/outputs',
        ],
        'read_outputs'                     => [
            'method' => 'post',
            'url'    => '/outputs',
        ],
        'external_proxy'                   => [
            'method' => 'post',
            'url'    => '/external/proxy',
        ],
        'read_multisigs'                   => [
            'method' => 'get',
            'url'    => '/multisigs',
        ],
        'post_multisigs'                   => [
            'method' => 'post',
            'url'    => '/multisigs',
        ],
        'access_token_post_multisigs'      => [
            'method' => 'post',
            'url'    => '/multisigs',
        ],
        'multisigs_requests'      => [
            'method' => 'post',
            'url'    => '/multisigs/requests',
        ],
        'multisigs_requests_sign'      => [
            'method' => 'post',
            'url'    => '/multisigs/requests/{$requestId}/sign',
        ],
        'multisigs_requests_cancel'      => [
            'method' => 'post',
            'url'    => '/multisigs/requests/{$requestId}/cancel',
        ],
        'multisigs_requests_unlock'      => [
            'method' => 'post',
            'url'    => '/multisigs/requests/{$requestId}/unlock',
        ],
        'multisigs_sign'                   => [
            'method' => 'post',
            'url'    => '/multisigs/{$requestId}/sign',
        ],
        'multisigs_cancel'                 => [
            'method' => 'post',
            'url'    => '/multisigs/{$requestId}/unlock',
        ],
        'read_fiats'                       => [
            'method' => 'get',
            'url'    => '/fiats',
        ],
        'access_token_get_asset'           => [
            'method' => 'get',
            'url'    => '/assets/',
        ],
        'add_favorite_app'                 => [
            'method' => 'post',
            'url'    => '/apps/{$userId}/favorite',
        ],
        'remove_favorite_app'              => [
            'method' => 'post',
            'url'    => '/apps/{$userId}/unfavorite',
        ],
        'read_favorite_apps'               => [
            'method' => 'get',
            'url'    => '/users/{$userId}/apps/favorite',
        ],
        'read_historical_prices'           => [
            'method' => 'get',
            'url'    => '/network/ticker',
        ],
        'send_multisig_transactions'       => [
            'method' => 'post',
            'url'    => '/transactions',
        ],
        'send_mainnet_transactions'        => [
            'method' => 'post',
            'url'    => '/transactions',
        ],
        'read_multisigs_outputs'           => [
            'method' => 'get',
            'url'    => '/multisigs/outputs',
        ],
        'access_token_read_multisigs_outputs' => [
            'method' => 'get',
            'url'    => '/multisigs/outputs',
        ],
        'read_snapshots_by_trace'               => [
            'method' => 'get',
            'url'    => '/snapshots/trace/',//后面需要填参数
        ],
    ],
];
