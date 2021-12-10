# Mixin-SDK-PHP

![](https://img.shields.io/badge/Mixin-Network-2995f2.svg?style=for-the-badge&colorA=1cc2fd&longCache=true&logo=data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI0NSAyNDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI0NSAyNDA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkZGRkZGO30KPC9zdHlsZT4KPGc+Cgk8Zz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjI3LjEsMzMuM2wtMzYuMywxNi4xYy0yLjIsMS4yLTMuNSwzLjUtMy41LDUuOXYxMjkuOGMwLDIuNSwxLjQsNC44LDMuNiw1LjlsMzYuMywxNS43YzIuMywxLjIsNS0wLjQsNS0zJiMxMDsmIzk7JiM5OyYjOTtWMzYuM0MyMzIuMSwzMy43LDIyOS4zLDMyLjEsMjI3LjEsMzMuM3ogTTUzLjMsNDkuMmwtMzUuMi0xNmMtMi4zLTEuMi01LDAuNC01LDN2MTY3LjRjMCwyLjcsMyw0LjMsNS4yLDIuOWwzNS40LTE4LjcmIzEwOyYjOTsmIzk7JiM5O2MyLTEuMiwzLjItMy40LDMuMi01Ljd2LTEyN0M1Ni44LDUyLjcsNTUuNSw1MC40LDUzLjMsNDkuMnogTTE2My43LDkzLjVsLTM3LjktMjEuN2MtMi4xLTEuMi00LjctMS4yLTYuNywwTDgwLjUsOTMuMyYjMTA7JiM5OyYjOTsmIzk7Yy0yLjEsMS4yLTMuNCwzLjUtMy40LDUuOXY0NGMwLDIuNCwxLjMsNC43LDMuNCw1LjlsMzguNiwyMi4yYzIuMSwxLjIsNC43LDEuMiw2LjcsMGwzNy45LTIyYzIuMS0xLjIsMy40LTMuNSwzLjQtNS45di00NCYjMTA7JiM5OyYjOTsmIzk7QzE2Ny4xLDk2LjksMTY1LjgsOTQuNywxNjMuNyw5My41eiIvPgoJPC9nPgo8L2c+Cjwvc3ZnPg==)
![](https://img.shields.io/badge/ExinOne-333333.svg?style=for-the-badge&longCache=true&logo=data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUxLjc3IiBoZWlnaHQ9IjE1MS43NyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KIDxkZWZzPgogIDxzdHlsZT4uY2xzLTF7ZmlsbDojZmZmO308L3N0eWxlPgogPC9kZWZzPgogPHRpdGxlPjI1PC90aXRsZT4KIDxnPgogIDx0aXRsZT5iYWNrZ3JvdW5kPC90aXRsZT4KICA8cmVjdCBmaWxsPSJub25lIiBpZD0iY2FudmFzX2JhY2tncm91bmQiIGhlaWdodD0iMTUzLjc3IiB3aWR0aD0iMTUzLjc3IiB5PSItMSIgeD0iLTEiLz4KIDwvZz4KIDxnPgogIDx0aXRsZT5MYXllciAxPC90aXRsZT4KICA8cGF0aCBpZD0ic3ZnXzEiIGQ9Im0xMTEuNTc2ODM4LDE0LjU4MTcyN2MtOC4zNywxLjQ0IC0xMC43Niw3LjM4IC0xNS44OSwxMy4zNmE5Ljc2LDkuNzYgMCAwIDEgLTcuNDEsMy4zNWwtMC44NywwYTkuNzcsOS43NyAwIDAgMSAtNy40MSwtMy4zNWMtNS4xMywtNiAtNy41MiwtMTEuOTIgLTE1Ljg5LC0xMy4zNmMtMTAuODQsLTEuODYgLTIxLjUzLDUuNDEgLTIyLjU4LDE2LjM1YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjM0YzEyLDAgMTQuMjIsLTYuMDcgMjAuMjgsLTEzLjQ0YTkuODYsOS44NiAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuODUsOS44NSAwIDAgMSA3LjYxLDMuNmM2LjA2LDcuMzcgOC4yNiwxMy40NCAyMC4yOCwxMy40NGExOC41MSwxOC41MSAwIDAgMCAxOC40MiwtMjAuMzRjLTEuMDUsLTEwLjk0IC0xMS43NCwtMTguMjEgLTIyLjU4LC0xNi4zNXoiIGNsYXNzPSJjbHMtMSIvPgogIDxwYXRoIGlkPSJzdmdfMiIgZD0ibTgzLjgxNjgzOCw1OC40NjE3MjdjLTguMzcsMS40MyAtMTAuNzYsNy4zNyAtMTUuOSwxMy4zNmE5Ljc0LDkuNzQgMCAwIDEgLTcuNCwzLjM1bC0wLjg2LDBhOS43Niw5Ljc2IDAgMCAxIC03LjQxLC0zLjM1Yy01LjEzLC02IC03LjUzLC0xMS45MyAtMTUuOSwtMTMuMzZjLTEwLjg0LC0xLjg2IC0yMS41NCw1LjQgLTIyLjU5LDE2LjM0YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjNjMTIsMCAxNC4yMywtNi4wNiAyMC4yOCwtMTMuNDRhOS45LDkuOSAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuODksOS44OSAwIDAgMSA3LjYyLDMuNmM2LjA2LDcuNDQgOC4yNiwxMy40NCAyMC4yOCwxMy40NGExOC41MSwxOC41MSAwIDAgMCAxOC40MiwtMjAuMzRjLTEuMDUsLTEwLjkgLTExLjc0LC0xOC4xNiAtMjIuNTgsLTE2LjN6IiBjbGFzcz0iY2xzLTEiLz4KICA8cGF0aCBpZD0ic3ZnXzMiIGQ9Im0xMTEuNzQ2ODM4LDEwMS43MzE3MjdjLTguMzcsMS40NCAtMTAuNzcsNy4zOCAtMTUuOSwxMy4zNmE5LjcxLDkuNzEgMCAwIDEgLTcuNCwzLjM2bC0wLjg4LDBhOS43MSw5LjcxIDAgMCAxIC03LjQsLTMuMzZjLTUuMTQsLTYgLTcuNTMsLTExLjkyIC0xNS45LC0xMy4zNmMtMTAuODMsLTEuODYgLTIxLjUzLDUuMzcgLTIyLjYxLDE2LjM3YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjM0YzEyLDAgMTQuMjIsLTYuMDcgMjAuMjgsLTEzLjQ0YTkuODksOS44OSAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuOSw5LjkgMCAwIDEgNy42MiwzLjZjNi4wNiw3LjM3IDguMjYsMTMuNDQgMjAuMjgsMTMuNDRhMTguNTEsMTguNTEgMCAwIDAgMTguNDUsLTIwLjM0Yy0xLjA1LC0xMSAtMTEuNzUsLTE4LjIzIC0yMi41OCwtMTYuMzd6IiBjbGFzcz0iY2xzLTEiLz4KIDwvZz4KPC9zdmc+)

------

![](https://img.shields.io/badge/php-~7.0.0-green.svg?longCache=true&style=flat-square&colorA=333333)
![](https://img.shields.io/github/languages/code-size/ExinOne/mixin-sdk-php.svg?style=flat-square&colorA=333333)
![](https://img.shields.io/github/license/ExinOne/mixin-sdk-php.svg?style=flat-square&colorA=333333)
![](https://img.shields.io/github/release/ExinOne/mixin-sdk-php.svg?style=flat-square&colorA=333333)
[![](https://img.shields.io/badge/language-中文文档-333333.svg?longCache=true&style=flat-square&colorA=E62B1E)](readme-cn.md)

Mixin-Network SDK for PHP

## Requirement

1. `Composer`
2. `PHP` >= 7.0

## Installation

```bash
$ composer require exinone/mixin-sdk-php -vvv
```

## Use

### Example

```php
// Configuration file format
$config0 = [
    'mixin_id'      => '7000101633',
    'client_id'     => '982afd4e-92dd-4430-98cf-d308442ea04d',
    'client_secret' => 'b0a9adf1b358501b1fb6065c6292b09dbc675d5734225f86e0ec14a71d0fd38a',
    'pin'           => '125334',
    'pin_token'     => 'RzgyepFhLbMx+zLw6ogYzZ5k+kmlo8gQ2f4+1uwGMi1HgvMexGdFdeny0ffuBl7gXgPqi1GpUDPWPNrgAIjwGIFu+rHSre1G7JA5ET6tgIYoC+OI2dF0PmNK0qtkjK+qpGpSCt8nFbTfgyHjFENAp4hLZEIhuhzSPPmkkhXGlAU=',
    'session_id'    => '8a70b414-bdef-46f3-9738-186c1095da61',
    'private_key'   => <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCZAkpYA7eH38GbzIX653dxBAEanrSSdYbzQYIV/kKbULYSB43X
5hWfCFpNJ6FoIUjHAzrNyqJMvSS6LwAA+R4w5GYv8en1Ga1blKbziCMvZsRJ65bP
F2cPbPQUYNWZpZRsyFlMZCjVVytA1a+HWba5FzGBXiEEzd5BVo9truxGKQIDAQAB
AoGBAJHpVj4ipUWEgtvxKR9A1m1G9UqOaAJ2409cfBI/GwOD72y4GXL2rk2vKHYF
Qe3MN9vY353aC/YENV77bRSAfGF+lAuw3hQOFfMvKfRUmVaB5V4kEaF1+z9jPHwh
QcSpqyAsMClEnyMxbNrFih4tQGlGUydHI3xv1wK+53MjncwBAkEA98GvzldsLW7E
dhwXLba3KN/GvLZLcljLyHjctUxCU8EywD73UM1lJuayWvYdLfzFe0p9cDjvS8Gx
sbYg3eE2qQJBAJ4ZkqVbdCAyUBpjaIO9QzxsL77zCgayrQTvWv0QQgJputO7S2rs
i/iCmt1bZ3IAnfVnDUJfAaxSL6VU1T6Fw4ECQQCtUvrCx7YOMqeOWSh9+o04MxS9
gGpXnHcz0BnXW3orTcVLaMFr7cUN6eZsbDENswAUuI/4qlv+C4tcX6Wuk5fBAkA+
EggzB37GDTrJwXGNF0dId6kfLMgo7QlkwJxWcoWX8O66pfPsHMavYIdwlKw+Y+Og
Lz9TaX18rB+sp2u5SkcBAkBIsC/AJNhf1xILLAkkpycJ7rc864Y1JbmKk+I5fXid
vA4vKPqu2ZnD0O4YbGmciuTRPgeJqAt8bbHq/xOfL0lE
-----END RSA PRIVATE KEY-----
EOF
            ,  //import your private_key
];

$mixinSdk = new \ExinOne\MixinSDK\MixinSDK();
// use setConfig method to save config
$mixinSdk->setConfig('myConfig-A',$config0);
$mixinSdk->setConfig('myConfig-B',$config1);
// then you can
$mixinSdk->use('myConfig-A')->user()->readProfile();

//-------
// Or more simple way, using the 'use' method , chained with other methods
$mixinSdk->use('myConfig-A',$config)->user()->readProfile();
// then you can
$mixinSdk->use('myConfig-A')->user()->readProfile();
```

### Run

| code                                                                                                                                                      | description                                                             | module  | Mixin Network Docs                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------- | ------- | ---------------------------------------------------------------------------------------------------------------------- |
| `MixinSDK::pin()->updatePin($oldPin, $pin)`                                                                                                               | Update Pin code                                                         | Pin     | [link](https://developers.mixin.one/api/alpha-mixin-network/create-pin/)                                               |
| `MixinSDK::pin()->verifyPin($pin)`                                                                                                                        | Verify Pin code                                                         | Pin     | [link](https://developers.mixin.one/api/alpha-mixin-network/verify-pin/)                                               |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| `MixinSDK::user()->readProfile()`                                                                                                                         | Read self profile                                                       | User    | [link](https://developers.mixin.one/api/beta-mixin-message/read-profile/)                                              |
| `MixinSDK::user()->updateProfile(string $full_name, string $avatar_base64 = '')`                                                                          | Update user’s profile.                                                  | User    | [link](https://developers.mixin.one/api/beta-mixin-message/update-profile/)                                            |
| `MixinSDK::user()->updatePreferences(string $receive_message_source, string $accept_conversation_source)`                                                 | Update user’s preferences.                                              | User    | [link](https://developers.mixin.one/api/beta-mixin-message/update-perference/)                                         |
| `MixinSDK::user()->rotateQRCode()`                                                                                                                        | Rotate user’s code_id.                                                  | User    | [link](https://developers.mixin.one/api/beta-mixin-message/rotate-qr/)                                                 |
| `MixinSDK::user()->readFriends()`                                                                                                                         | Get user’s friends.                                                     | User    | [link](https://developers.mixin.one/api/beta-mixin-message/friends/)                                                   |
| `MixinSDK::user()->addFavoriteApp(string $user_id)`                                                                                                       | Add app to favorite app list.                                           | User    | [link](https://developers.mixin.one/api/b-apps/favorite/)                                                     |
| `MixinSDK::user()->removeFavoriteApp(string $user_id)`                                                                                                    | Remove app from favorite app list.                                      | User    | [link](https://developers.mixin.one/api/b-apps/unfavorite/)                                                     |
| `MixinSDK::user()->readFavoriteApps(string $user_id = null)`                                                                                              | Returns the favorite apps are recommended by the user.                  | User    | [link](https://developers.mixin.one/api/b-apps/favorites/)                                                     |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| `MixinSDK::wallet()->createAddress(string $asset_id, string $destination, $pin, $label, $tag)`                                                            | Create an address for withdrawal                                        | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/create-address/)                                           |
| `MixinSDK::wallet()->readAddresses(string $assetId)`                                                                                                      | Read addresses by asset ID.                                             | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/withdrawal-addresses/)                                     |
| `MixinSDK::wallet()->readAddress(string $addressId)`                                                                                                      | Read an address by ID.                                                  | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/read-address/)                                             |
| `MixinSDK::wallet()->deleteAddress(string $addressId, $pin = null)`                                                                                              | Delete an address by ID.                                                | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/delete-address/)                                           |
| `MixinSDK::wallet()->readAssets()`                                                                                                                        | Read user’s all assets.                                                 | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/read-assets/)                                              |
| `MixinSDK::wallet()->readAsset(string $assetId)`                                                                                                          | Read asset by ID.                                                       | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/read-asset/)                                               |
| `MixinSDK::wallet()->deposit(string $assetId)`                                                                                                            | Gant an asset’s deposit address (The api same as `wallet()->readAsset`) | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/deposit/)                                                  |
| `MixinSDK::wallet()->withdrawal(string $addressId, $amount, $pin, $memo = '', $trace_id = null)`                                                          | Get assets out of Mixin Network                                         | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/withdrawal/)                                               |
| `MixinSDK::wallet()->transfer(string $assetId, string $opponentId, $pin, $amount, $memo = '', $trace_id = null)`                                          | Transfer of assets between Mixin Network users.                         | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/transfer/)                                                 |
| `MixinSDK::wallet()->verifyPayment(string $asset_id, string $opponent_id, $amount, string $trace_id)`                                                     | Verify a transfer                                                       | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/verify-payment/)                                           |
| `MixinSDK::wallet()->readTransfer(string $traceId)`                                                                                                       | Read transfer by trace ID.                                              | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/read-transfer/)                                            |
| `MixinSDK::wallet()->readAssetFee(string $assetId)`                                                                                                       | Read transfer fee                                                       | Wallet  | **null**                                                                                                               |
| `MixinSDK::wallet()->readUserSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC')`                                 | Get user's all snapshots.                                               | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshots/)                                        |
| `MixinSDK::wallet()->readUserSnapshot(string $snapshotId)`                                                                                                | Get user's a snapshots by ID.                                           | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshot/)                                         |
| `MixinSDK::wallet()->accessTokenGetUserSnapshots(string $access_token, $limit = null, string $offset = null, string $asset = '', string $order = 'DESC')` | Get user's all snapshots.                                               | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshots/)                                        |
| `MixinSDK::wallet()->accessTokenGetUserSnapshot(string $access_token, string $snapshot_id)`                                                               | Get user's a snapshots by ID.                                           | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshot/)                                         |
| `MixinSDK::wallet()->accessTokenGetTransfer(string $access_token, string $trace_id)`                                                                      | Get Transfer                                                            | Wallet  | [link](https://developers.mixin.one/api/alpha-mixin-network/read-transfer/)                                            |
| `MixinSDK::wallet()->readRawMainNetAddress(string $client_id)`                                                                                            | Get Transfer Address                                                    | Wallet  |                                                                                                                        |
| `MixinSDK::wallet()->accessTokenPostOutputs($access_token, $receivers, $index = 0)`                                                                       | Get Transfer Address                                                    | Wallet  |                                                                                                                        |
| `MixinSDK::wallet()->multisigPayment(string $asset_id, array $receivers, int $threshold, $amount, $memo = '', $trace_id = null)`                          | Post Multisig Payment                                                   | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->checkCode($code_id)`                                                                                                                 | Get payment details                                                     | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->readMultisigs(string $offset = '', $limit = null)`                                                                                   | Get Multisigs                                                           | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->accessTokenPostMultisigs(string $access_token, string $raw, string $action = 'sign')`                                                | Initiate a multi-signature transaction request                          | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->postMultisigs(string $raw, string $action = 'sign')`                                                                                 | Initiate a multi-signature transaction request                          | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->externalProxy($params, $method = 'sendrawtransaction')`                                                                              | Draw assets                                                             | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->multisigsSign(string $request_id, String $pin)`                                                                                      | Sign                                                                    | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->multisigsCancel(string $request_id, String $pin)`                                                                                    | Cancel Sign                                                             | Wallet  | [link](https://w3c.group/c/1574309272319630)                                                                           |
| `MixinSDK::wallet()->readFiats()`                                                                                                                         | Fiat currency to USD exchange rate                                      | Wallet  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| `MixinSDK::network()->readUser( $userId)`                                                                                                                 | Get user’s information by ID.                                           | Network | [/api/beta-mixin-message/read-user/](https://developers.mixin.one/api/beta-mixin-message/read-user/)                   |
| `MixinSDK::network()->readUsers(array $userIds)`                                                                                                          | Get users information by IDs.                                           | Network | [link](https://developers.mixin.one/api/beta-mixin-message/read-users/)                                                |
| `MixinSDK::network()->searchUser($item)`                                                                                                                  | Search user by ID.                                                      | Network | [link](https://developers.mixin.one/api/beta-mixin-message/search-user/)                                               |
| `MixinSDK::network()->readNetworkAsset(string $assetId)`                                                                                                  | Read public asset information by ID from Mixin Network.                 | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/network-asset/)                                            |
| `MixinSDK::network()->readNetworkSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC')`                             | Read public snapshots of Mixin Network.                                 | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshots/)                                        |
| `MixinSDK::network()->readNetworkSnapshot(string $snapshotId)`                                                                                            | Read public snapshots of Mixin Network by ID.                           | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/network-snapshot/)                                         |
| `MixinSDK::network()->createUser($fullName)`                                                                                                              | Create a new Mixin Network user                                         | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/app-user/)                                                 |
| `MixinSDK::network()->externalTransactions($asset, $destination, $limit, $offset, $tag)`                                                         | Read external transactions                                              | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/external-transactions/)                                    |
| `MixinSDK::network()->createAttachments()`                                                                                                                | Create an attachment upload address.                                    | Network | [link](https://developers.mixin.one/api/beta-mixin-message/create-attachment/)                                         |
| `MixinSDK::network()->mixinNetworkChainsSyncStatus()`                                                                                                     | Get Mixin Network Chains Synchronize status                             | Network | **null**                                                                                                               |
| `MixinSDK::network()->topAsset()`                                                                                                                         | top asset                                                               | Network | [/api/alpha-mixin-network/network/](https://developers.mixin.one/api/alpha-mixin-network/network/)                     |
| `MixinSDK::network()->multisigAsset()`                                                                                                                    | multisig asset                                                          | Network | [/api/alpha-mixin-network/network/](https://developers.mixin.one/api/alpha-mixin-network/network/)                     |
| `MixinSDK::network()->requestAccessToken(string $code)`                                                                                                   | use code request access token                                           | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->accessTokenGetInfo(string $access_token)`                                                                                           | use access token get info                                               | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->accessTokenGetAssets(string $access_token)`                                                                                         | use access token get assets info                                        | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->accessTokenGetContacts(string $access_token)`                                                                                       | use access token get contact info                                       | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->accessTokenGetAddresses(string $access_token, string $assetId)`                                                                     | use access token get addresses                                          | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->accessTokenGetAddress(string $access_token, string $addressId)`                                                                     | use access token get an addresseses                                     | Network | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::network()->rotateConversation(string $conversation_id)`                                                                                                                   | Rotate Conversation                                            | Network | [/guides](https://developers.mixin.one/api/h-conversations/rotate-conversation/)            |
| `MixinSDK::network()->searchAssets(string $snapshotId)`                                                                                                   | search assets                                                           | Network | [link](https://developers.mixin.one/api/alpha-mixin-network/search-assets/)                                            |
| `MixinSDK::network()->participantsActions(string $conversation_id, array $participants, string $action = "ADD")`                                                                                                                   | participants actions                                            | Network | [/guides](https://developers.mixin.one/api/h-conversations/participants-actions/)            |
| `MixinSDK::network()->readHistoricalPrices(string $asset, string $offset)`                                                                                                                   | get the historical price of a given asset_id                                            | Network | [/guides](https://developers.mixin.one/docs/api/network/ticker/)            |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| `MixinSDK::message()->sendText($user_id, $data, $category , $conversation_id)`                                                                            | send text                                                               | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| `MixinSDK::message()->sendContact($user_id, $contact_id, $category, $conversation_id)`                                                                    | send user card                                                          | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| `MixinSDK::message()->sendAppButtonGroup($user_id, $data, $category, $conversation_id)`                                                                   | send App Button Group (max three)                                       | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| `MixinSDK::message()->sendAppCard($user_id, $data, $category, $conversation_id)`                                                                          | send App Card                                                           | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| `MixinSDK::message()->askMessageReceipt($message_id)`                                                                                                     | ask Message Receipt                                                     | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| `MixinSDK::message()->sendBatchMessage($user_id, $data, $use_http, $type)`                                                                                | send batch message                                                      | Message | [/api/beta-mixin-message/websocket-messages/](https://developers.mixin.one/api/beta-mixin-message/websocket-messages/) |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| **---**                                                                                                                                                   | **--**                                                                  | **--**  |                                                                                                                        |
| `MixinSDK::getOauthUrl($client_id, string $scope)`                                                                                                        | Get Oauth Url                                                           | other   | [link](https://developers.mixin.one/guides)                                                                            |
| `MixinSDK::getPayUrl($asset_id, $amount, $trace_id, $memo, $client_id = null)`                                                                            | generate a pay Url                                                      | other   | [/guides](https://developers.mixin.one/guides)                                                                         |
| `MixinSDK::getConfig($configGroupName='')`                                                                                                                | read config                                                             | other   | **null**                                                                                                               |

## Exceptions

If MixinNetwork response with an error，An Exception `ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException` will be thrown. Developers need to capture and handle this exception.

```php
try {
    // If the transfer fails here, an error will be thrown.
    $mixinSdk->wallet()->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
} catch (MixinNetworkRequestException $e) {
    // Here errCode and errMessage are the same as MixinNetwork, refer to the following link.
    $errCode    = $e->getCode();
    $errMessage = $e->getMessage();
    ...
} catch (\Throwable $e) {
    ...
}
```

[MixinNetwork Error Codes](https://developers.mixin.one/api/alpha-mixin-network/errors/)

### Other Exceptions

| class                                                      | description          |
| ---------------------------------------------------------- | -------------------- |
| `ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException` | Api request fail     |
| `ExinOne\MixinSDK\Exceptions\NotFoundConfigException`      | not found config set |
| `ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException`      | private Key error    |
| `ExinOne\MixinSDK\Exceptions\ClassNotFoundException`       | class not found      |

## WARNING

1. You can config `iterator` in the following way. The `iterator` is used when a PIN is encrypted. Generally, `iterator` should not be modified. If you want ot modify this variable,  be sure to know what you are doing. [More details on iterator](https://developers.mixin.one/api/alpha-mixin-network/encrypted-pin/)
   
   ```php
    $iterator = [time()];
    // if use it by $mixinSdk->pin()->updatePin($oldPin,$pin),
    // $iterator need have two element (count($iterator) == 2)
    $mixinSdk->wallet()->setIterator($iterator)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // By default, microtime(true) * 100000 is used as iterator
   ```

2. Setting Http Request timeout
   
   ```php
    $mixinSdk->wallet()->setTimeout(10)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // The default timeout is 20 s
   ```

3. Get raw Recponse content
   
   ```php
    $mixinSdk->wallet()->setRaw(true)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // Return MixinNetwork raw Response content
   ```

## Alternatives

[[exinone/laravel-mixin-sdk](https://github.com/ExinOne/laravel-mixin-sdk)]

[[zamseam/mixin](https://github.com/zamseam/mixin)]

## LICENSE

**MIT**
