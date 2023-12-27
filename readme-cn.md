# Mixin-SDK-PHP

![](https://img.shields.io/badge/Mixin-Network-2995f2.svg?style=for-the-badge&colorA=1cc2fd&longCache=true&logo=data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI0NSAyNDAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI0NSAyNDA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkZGRkZGO30KPC9zdHlsZT4KPGc+Cgk8Zz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjI3LjEsMzMuM2wtMzYuMywxNi4xYy0yLjIsMS4yLTMuNSwzLjUtMy41LDUuOXYxMjkuOGMwLDIuNSwxLjQsNC44LDMuNiw1LjlsMzYuMywxNS43YzIuMywxLjIsNS0wLjQsNS0zJiMxMDsmIzk7JiM5OyYjOTtWMzYuM0MyMzIuMSwzMy43LDIyOS4zLDMyLjEsMjI3LjEsMzMuM3ogTTUzLjMsNDkuMmwtMzUuMi0xNmMtMi4zLTEuMi01LDAuNC01LDN2MTY3LjRjMCwyLjcsMyw0LjMsNS4yLDIuOWwzNS40LTE4LjcmIzEwOyYjOTsmIzk7JiM5O2MyLTEuMiwzLjItMy40LDMuMi01Ljd2LTEyN0M1Ni44LDUyLjcsNTUuNSw1MC40LDUzLjMsNDkuMnogTTE2My43LDkzLjVsLTM3LjktMjEuN2MtMi4xLTEuMi00LjctMS4yLTYuNywwTDgwLjUsOTMuMyYjMTA7JiM5OyYjOTsmIzk7Yy0yLjEsMS4yLTMuNCwzLjUtMy40LDUuOXY0NGMwLDIuNCwxLjMsNC43LDMuNCw1LjlsMzguNiwyMi4yYzIuMSwxLjIsNC43LDEuMiw2LjcsMGwzNy45LTIyYzIuMS0xLjIsMy40LTMuNSwzLjQtNS45di00NCYjMTA7JiM5OyYjOTsmIzk7QzE2Ny4xLDk2LjksMTY1LjgsOTQuNywxNjMuNyw5My41eiIvPgoJPC9nPgo8L2c+Cjwvc3ZnPg==)
![](https://img.shields.io/badge/ExinOne-333333.svg?style=for-the-badge&longCache=true&logo=data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUxLjc3IiBoZWlnaHQ9IjE1MS43NyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KIDxkZWZzPgogIDxzdHlsZT4uY2xzLTF7ZmlsbDojZmZmO308L3N0eWxlPgogPC9kZWZzPgogPHRpdGxlPjI1PC90aXRsZT4KIDxnPgogIDx0aXRsZT5iYWNrZ3JvdW5kPC90aXRsZT4KICA8cmVjdCBmaWxsPSJub25lIiBpZD0iY2FudmFzX2JhY2tncm91bmQiIGhlaWdodD0iMTUzLjc3IiB3aWR0aD0iMTUzLjc3IiB5PSItMSIgeD0iLTEiLz4KIDwvZz4KIDxnPgogIDx0aXRsZT5MYXllciAxPC90aXRsZT4KICA8cGF0aCBpZD0ic3ZnXzEiIGQ9Im0xMTEuNTc2ODM4LDE0LjU4MTcyN2MtOC4zNywxLjQ0IC0xMC43Niw3LjM4IC0xNS44OSwxMy4zNmE5Ljc2LDkuNzYgMCAwIDEgLTcuNDEsMy4zNWwtMC44NywwYTkuNzcsOS43NyAwIDAgMSAtNy40MSwtMy4zNWMtNS4xMywtNiAtNy41MiwtMTEuOTIgLTE1Ljg5LC0xMy4zNmMtMTAuODQsLTEuODYgLTIxLjUzLDUuNDEgLTIyLjU4LDE2LjM1YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjM0YzEyLDAgMTQuMjIsLTYuMDcgMjAuMjgsLTEzLjQ0YTkuODYsOS44NiAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuODUsOS44NSAwIDAgMSA3LjYxLDMuNmM2LjA2LDcuMzcgOC4yNiwxMy40NCAyMC4yOCwxMy40NGExOC41MSwxOC41MSAwIDAgMCAxOC40MiwtMjAuMzRjLTEuMDUsLTEwLjk0IC0xMS43NCwtMTguMjEgLTIyLjU4LC0xNi4zNXoiIGNsYXNzPSJjbHMtMSIvPgogIDxwYXRoIGlkPSJzdmdfMiIgZD0ibTgzLjgxNjgzOCw1OC40NjE3MjdjLTguMzcsMS40MyAtMTAuNzYsNy4zNyAtMTUuOSwxMy4zNmE5Ljc0LDkuNzQgMCAwIDEgLTcuNCwzLjM1bC0wLjg2LDBhOS43Niw5Ljc2IDAgMCAxIC03LjQxLC0zLjM1Yy01LjEzLC02IC03LjUzLC0xMS45MyAtMTUuOSwtMTMuMzZjLTEwLjg0LC0xLjg2IC0yMS41NCw1LjQgLTIyLjU5LDE2LjM0YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjNjMTIsMCAxNC4yMywtNi4wNiAyMC4yOCwtMTMuNDRhOS45LDkuOSAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuODksOS44OSAwIDAgMSA3LjYyLDMuNmM2LjA2LDcuNDQgOC4yNiwxMy40NCAyMC4yOCwxMy40NGExOC41MSwxOC41MSAwIDAgMCAxOC40MiwtMjAuMzRjLTEuMDUsLTEwLjkgLTExLjc0LC0xOC4xNiAtMjIuNTgsLTE2LjN6IiBjbGFzcz0iY2xzLTEiLz4KICA8cGF0aCBpZD0ic3ZnXzMiIGQ9Im0xMTEuNzQ2ODM4LDEwMS43MzE3MjdjLTguMzcsMS40NCAtMTAuNzcsNy4zOCAtMTUuOSwxMy4zNmE5LjcxLDkuNzEgMCAwIDEgLTcuNCwzLjM2bC0wLjg4LDBhOS43MSw5LjcxIDAgMCAxIC03LjQsLTMuMzZjLTUuMTQsLTYgLTcuNTMsLTExLjkyIC0xNS45LC0xMy4zNmMtMTAuODMsLTEuODYgLTIxLjUzLDUuMzcgLTIyLjYxLDE2LjM3YTE4LjUxLDE4LjUxIDAgMCAwIDE4LjQyLDIwLjM0YzEyLDAgMTQuMjIsLTYuMDcgMjAuMjgsLTEzLjQ0YTkuODksOS44OSAwIDAgMSA3LjYyLC0zLjZsMCwwYTkuOSw5LjkgMCAwIDEgNy42MiwzLjZjNi4wNiw3LjM3IDguMjYsMTMuNDQgMjAuMjgsMTMuNDRhMTguNTEsMTguNTEgMCAwIDAgMTguNDUsLTIwLjM0Yy0xLjA1LC0xMSAtMTEuNzUsLTE4LjIzIC0yMi41OCwtMTYuMzd6IiBjbGFzcz0iY2xzLTEiLz4KIDwvZz4KPC9zdmc+)

------

![](https://img.shields.io/badge/php-~7.0.0-green.svg?longCache=true&style=flat-square&colorA=333333)
![](https://img.shields.io/github/languages/code-size/ExinOne/laravel-mixin-sdk.svg?style=flat-square&colorA=333333)
![](https://img.shields.io/github/license/ExinOne/laravel-mixin-sdk.svg?style=flat-square&colorA=333333)
![](https://img.shields.io/github/release/ExinOne/laravel-mixin-sdk.svg?style=flat-square&colorA=333333)
[![](https://img.shields.io/badge/language-English-333333.svg?longCache=true&style=flat-square&colorA=E62B1E)](readme.md)

Mixin-Network SDK for PHP

## 要求

1. `Composer`
2. `PHP` >= 7.0

## Installation

```bash
$ composer require exinone/mixin-sdk-php -vvv
```

## 使用

### 例示

```php
// 配置文件格式例示
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
// 使用 setConfig 方法，保存配置
$mixinSdk->setConfig('myConfig-A',$config0);
$mixinSdk->setConfig('myConfig-B',$config1);
// 在之后的调用中你就可以
$mixinSdk->use('myConfig-A')->user()->readProfile();

//-------
// 或者更加简洁一些，直接使用 use 方法后，链式调用其他方法
$mixinSdk->use('myConfig-A',$config)->user()->readProfile();
// 在之后的调用中你就可以
$mixinSdk->use('myConfig-A')->user()->readProfile();
```

### 调用

| code                                                                                                                                                      | description                                     | module  | Mixin Network Docs                                                                                                       |
| --------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------- | ------- | ------------------------------------------------------------------------------------------------------------------------ |
| `MixinSDK::pin()->updatePin($oldPin, $pin)`                                                                                                               | 更新 Pin 码                                        | Pin     | [link](https://developers.mixin.one/docs/api/pin/pin-update)                 |
| `MixinSDK::pin()->verifyPin($pin)`                                                                                                                        | 验证 Pin 码                                        | Pin     | [link](https://developers.mixin.one/docs/api/pin/pin-verify)                 |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| `MixinSDK::user()->readProfile()`                                                                                                                         | 读取当前账号信息                                        | User    | [link](https://developers.mixin.one/docs/api/users/profile)                                                |
| `MixinSDK::user()->updateProfile(string $full_name, string $avatar_base64 = '')`                                                                          | 更新账号信息                                          | User    | [link](https://developers.mixin.one/docs/api/users/profile)                                              |
| `MixinSDK::user()->updatePreferences(string $receive_message_source, string $accept_conversation_source)`                                                 | 更新隐私设置                                          | User    | [link](https://developers.mixin.one/docs/api/users/profile)                                           |
| `MixinSDK::user()->rotateQRCode()`                                                                                                                        | 更换 QRCode                                       | User    | [link](https://developers.mixin.one/docs/api/users/profile)                                                   |
| `MixinSDK::user()->readFriends()`                                                                                                                         | read friends                                    | User    | [link](https://developers.mixin.one/docs/api/users/contacts)                                                     |
| `MixinSDK::user()->addFavoriteApp(string $user_id)`                                                                                                       | 增加 App 至喜欢列表                                | User    | [link](https://developers.mixin.one/docs/api/shared-bots)                                                                |
| `MixinSDK::user()->removeFavoriteApp(string $user_id)`                                                                                                    | 从喜欢列表中删除 App                               | User    | [link](https://developers.mixin.one/docs/api/shared-bots)                                                              |
| `MixinSDK::user()->readFavoriteApps(string $user_id = null)`                                                                                              | 读取喜欢列表                                      | User    | [link](https://developers.mixin.one/docs/api/shared-bots)                                                               |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| `MixinSDK::wallet()->createAddress(string $asset_id, string $destination, $pin, $label, $tag)`                                                            | 创建一个 address                                    | Wallet  | [link](https://developers.mixin.one/docs/api/withdrawal/address-add)                                             |
| `MixinSDK::wallet()->readAddresses(string $assetId)`                                                                                                      | 获取某个 asset 的全部地址                                | Wallet  | [link](https://developers.mixin.one/docs/api/withdrawal/addresses)                                       |
| `MixinSDK::wallet()->readAddress(string $addressId)`                                                                                                      | 获取某个 address 的信息                                | Wallet  | [link](https://developers.mixin.one/docs/api/withdrawal/address)                                               |
| `MixinSDK::wallet()->deleteAddress(string $addressId, $pin = null)`                                                                                              | 删除一个 address                                    | Wallet  | [link](https://developers.mixin.one/docs/api/withdrawal/address-delete)                                             |
| `MixinSDK::wallet()->readAssets()`                                                                                                                        | 获取当前用户全部的 assets 信息                             | Wallet  | [link](https://developers.mixin.one/docs/api/assets)                                                |
| `MixinSDK::wallet()->readAsset(string $assetId)`                                                                                                          | 获取当前用户某个 asset 的信息                              | Wallet  | [link](https://developers.mixin.one/docs/api/assets/asset)                                                 |
| `MixinSDK::wallet()->deposit(string $assetId)`                                                                                                            | deposit (The api same as `wallet()->readAsset`) | Wallet  | [link](https://developers.mixin.one/docs/api/assets/asset)                                                    |
| `MixinSDK::wallet()->withdrawal(string $addressId, $amount, $pin, $memo = '', $trace_id = null)`                                                          | 转账到某个 address                                   | Wallet  | [link](https://developers.mixin.one/docs/api/withdrawal)                                                 |
| `MixinSDK::wallet()->transfer(string $assetId, string $opponentId, $pin, $amount, $memo = '', $trace_id = null)`                                          | 转账给某个用户                                         | Wallet  | [link](https://developers.mixin.one/docs/api/transfer)                                                   |
| `MixinSDK::wallet()->verifyPayment(string $asset_id, string $opponent_id, $amount, string $trace_id)`                                                     | verify payment                                  | Wallet  | [link](https://developers.mixin.one/docs/api/transfer/payment)                                             |
| `MixinSDK::wallet()->readTransfer(string $traceId)`                                                                                                       | 获取转账详情                                          | Wallet  | [link](https://developers.mixin.one/docs/api/transfer)                                              |
| `MixinSDK::wallet()->readAssetFee(string $assetId)`                                                                                                       | 获取资产提现费率                                        | Wallet  | [link](https://developers.mixin.one/docs/api/assets/fee)                                                                                                                 |
| `MixinSDK::wallet()->readUserSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC')`                                 | 获取当前用户某个资产全部的 snapshots                         | Wallet  | [link](https://developers.mixin.one/docs/api/network/snapshots)                                          |
| `MixinSDK::wallet()->readUserSnapshot(string $snapshotId)`                                                                                                | 获取当前用户某个 snapshot 的信息                           | Wallet  | [link](https://developers.mixin.one/docs/api/network/snapshot)                                           |
| `MixinSDK::wallet()->accessTokenGetUserSnapshots(string $access_token, $limit = null, string $offset = null, string $asset = '', string $order = 'DESC')` | 获取当前用户某个资产全部的 snapshots                         | Wallet  | [link](https://developers.mixin.one/docs/api/network/snapshots)                                          |
| `MixinSDK::wallet()->accessTokenGetUserSnapshot(string $access_token, string $snapshot_id)`                                                               | 获取当前用户某个 snapshot 的信息                           | Wallet  | [link](https://developers.mixin.one/docs/api/network/snapshot)                                           |
| `MixinSDK::wallet()->accessTokenGetTransfer(string $access_token, string $trace_id)`                                                                      | 获取转账详情                                          | Wallet  | [link](https://developers.mixin.one/docs/api/transfer/snapshot)                                              |
| `MixinSDK::wallet()->readRawMainNetAddress(string $client_id)`                                                                                            | 获取转账地址                                          | Wallet  |                                                                                                                          |
| `MixinSDK::wallet()->accessTokenPostOutputs($access_token, $receivers, $index = 0)`                                                                       | 获取转账地址                                          | Wallet  | [link](https://developers.mixin.one/docs/api/outputs)                                                                             |
| `MixinSDK::wallet()->multisigPayment(string $asset_id, array $receivers, int $threshold, $amount, $memo = '', $trace_id = null)`                          | 发起多签                                            | Wallet  | [link](https://developers.mixin.one/docs/api/transfer/payment)                                                                             |
| `MixinSDK::wallet()->checkCode($code_id)`                                                                                                                 | 查看支付详情                                          | Wallet  |                                                                                                                          |
| `MixinSDK::wallet()->readMultisigs(string $offset = '', $limit = null)`                                                                                   | 查看所有多签                                          | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/outputs)                                                                             |
| `MixinSDK::wallet()->accessTokenPostMultisigs(string $access_token, string $raw, string $action = 'sign')`                                                | 发起多签交易请求                                        | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/outputs)                                                                             |
| `MixinSDK::wallet()->externalProxy($params, $method = 'sendrawtransaction')`                                                                              | 提取资产                                            | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                             |
| `MixinSDK::wallet()->postMultisigs(string $raw, string $action = 'sign')`                                                                                 | 发起多签交易请求                                        | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                             |
| `MixinSDK::wallet()->multisigsRequests(string $raw, string $action = 'sign')`                                                                                 | 发起多签交易请求                                        | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                             |
| `MixinSDK::wallet()->multisigsRequestsSign(string $request_id, String $pin)`                                                                                 | 签名                          | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                       |
| `MixinSDK::wallet()->multisigsRequestsCancel(string $request_id, String $pin)`                                                                                 | 取消签名                          | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                       |
| `MixinSDK::wallet()->multisigsRequestsUnlock(string $request_id, String $pin)`                                                                                 | 取消多签交易                         | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                       |
| `MixinSDK::wallet()->multisigsSign(string $request_id, String $pin)`                                                                                      | 签名                                              | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                             |
| `MixinSDK::wallet()->multisigsCancel(string $request_id, String $pin)`                                                                                    | 取消签名                                            | Wallet  | [link](https://developers.mixin.one/docs/api/multisigs/request)                                                                             |
| `MixinSDK::wallet()->readFiats()`                                                                                                                         | 法币对应美元汇率                                        | Wallet  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| `MixinSDK::network()->readUser($userId)`                                                                                                                  | 获取某个用户的信息                                       | Network | [link](https://developers.mixin.one/docs/api/users/user)                     |
| `MixinSDK::network()->readUsers(array $userIds)`                                                                                                          | 获取多个用户的信息                                       | Network | [link](https://developers.mixin.one/docs/api/users)                                                  |
| `MixinSDK::network()->searchUser($item)`                                                                                                                  | search user                                     | Network | [link](https://developers.mixin.one/docs/api/users/search)                                                 |
| `MixinSDK::network()->readNetworkAsset(string $assetId)`                                                                                                  | read network asset                              | Network | [link](https://developers.mixin.one/docs/api/network/assets)                                              |
| `MixinSDK::network()->readNetworkSnapshots($limit = null, string $offset = null, string $asset = '', string $order = 'DESC')`                             | read network snapshots                          | Network | [link](https://developers.mixin.one/docs/api/network/snapshots)                                          |
| `MixinSDK::network()->readNetworkSnapshot(string $snapshotId)`                                                                                            | read network snapshot                           | Network | [link](https://developers.mixin.one/docs/api/network/snapshot)                                           |
| `MixinSDK::network()->createUser($fullName)`                                                                                                              | 在 Mixin Network 上创建用户                           | Network | [link](https://developers.mixin.one/docs/api/users/network-user)                                                   |
| `MixinSDK::network()->externalTransactions($asset, $destination, $limit, $offset, $tag, $transaction_hash, $source, $user)`                               | read external transactions                      | Network | [link](https://developers.mixin.one/docs/api/external/pending-deposits)                                      |
| `MixinSDK::network()->createAttachments()`                                                                                                                | create attachments                              | Network | [link](https://developers.mixin.one/docs/api/messages/attachment-upload)                                           |
| `MixinSDK::network()->mixinNetworkChainsSyncStatus()`                                                                                                     | 获取 Mixin Network 当前的区块同步状态                      | Network |                                                                                                                  |
| `MixinSDK::network()->topAsset()`                                                                                                                         | top asset                                       | Network | [link](https://developers.mixin.one/docs/api/network/assets)                       |
| `MixinSDK::network()->multisigAsset()`                                                                                                                    | multisig asset                                  | Network | [link](https://developers.mixin.one/docs/api/network/assets)                       |
| `MixinSDK::network()->requestAccessToken(string $code)`                                                                                                   | use code request access token                   | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->accessTokenGetInfo(string $access_token)`                                                                                           | use access token get info                       | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->accessTokenGetAssets(string $access_token)`                                                                                         | use access token get assets info                | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->accessTokenGetAddresses(string $access_token, string $assetId)`                                                                     | use access token get addresses                  | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->accessTokenGetAddress(string $access_token, string $addressId)`                                                                     | use access token get an addresseses             | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->accessTokenGetContacts(string $access_token)`                                                                                       | use access token get contact info               | Network | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::network()->createConversations($category, $participants, $conversation_id, $name)`                                                             | 创建群聊                                            | Network | [link](https://developers.mixin.one/docs/api/conversations/create) |
| `MixinSDK::network()->readConversations($conversation_id)`                                                                                                | 获取群聊                                            | Network | [link](https://developers.mixin.one/docs/api/conversations/read)     |
| `MixinSDK::network()->participantsActions(string $conversation_id, array $participants, string $action = "ADD")`                                                                                                                   | 管理群聊                                            | Network | [link](https://developers.mixin.one/docs/api/conversations/group)            |
| `MixinSDK::network()->rotateConversation(string $conversation_id)`                                                                                                                   | 刷新群邀请链接                                            | Network | [link](https://developers.mixin.one/docs/api/conversations/create)            |
| `MixinSDK::network()->searchAssets($q)`                                                                                                                   | 搜索资产                                            | Network | [link](https://developers.mixin.one/docs/api/assets/asset)            |
| `MixinSDK::network()->readHistoricalPrices(string $asset, string $offset)`                                                                                                                   | 获取历史价格                                            | Network | [link](https://developers.mixin.one/docs/api/network/ticker)            |
| **---**                                                                                                                                                   | **---**                                         | **---** |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| `MixinSDK::message()->sendText($user_id, $data, $category, $conversation_id, $recipient_id)`                                                              | 发送文本消息                                          | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| `MixinSDK::message()->sendContact($user_id, $contact_id, $category, $conversation_id, $recipient_id)`                                                     | 发送用户名片                                          | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| `MixinSDK::message()->sendAppButtonGroup($user_id, $data, $category, $conversation_id, $recipient_id)`                                                    | 发送 App Button Group (最多三个)                      | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| `MixinSDK::message()->sendAppCard($user_id, $data, $category, $conversation_id, $recipient_id)`                                                           | 发送 App Card                                     | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| `MixinSDK::message()->askMessageReceipt($message_id)`                                                                                                     | 确认消息是否送达                                        | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| `MixinSDK::message()->sendBatchMessage($user_id, $data, $use_http, $type)`                                                                                | 群发消息                                            | Message | [link](https://developers.mixin.one/docs/api/messages/send)   |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| **---**                                                                                                                                                   | **--**                                          | **--**  |                                                                                                                          |
| `MixinSDK::getOauthUrl($user_id, $data, $category = 'CONTACT', $conversation_id = null)`                                                                  | 获取 Oauth Url                                    | other   | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::getPayUrl($asset_id, $amount, $trace_id, $memo, $client_id = null)`                                                                            | 生成一个支付 Url                                      | other   | [link](https://developers.mixin.one/docs/api-overview)                                                                           |
| `MixinSDK::getConfig($configGroupName='')`                                                                                                                | 查看一个或者全部配置                                      | other   |                                                                                                                  |

## 异常

在 MixinNetwork 的返回体中如果存在 error ，则会直接抛出一个 `ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException` 异常。使用者需要对这个异常进行捕获并处理。

```php
try {
    //如果这里转账失败将会抛出错误
    $mixinSdk->wallet()->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
} catch (MixinNetworkRequestException $e) {
    // 此处的 errCode 和 errMessage 与 MixinNetwork 一致，可以参阅下方官方链接
    $errCode    = $e->getCode();
    $errMessage = $e->getMessage();
    ...
} catch (\Throwable $e) {
    ...
}
```

[MixinNetwork Error Codes](https://developers.mixin.one/docs/api/error-codes)

### 其他的异常

| class                                                      | description     |
| ---------------------------------------------------------- | --------------- |
| `ExinOne\MixinSDK\Exceptions\MixinNetworkRequestException` | Api 请求失败        |
| `ExinOne\MixinSDK\Exceptions\NotFoundConfigException`      | 未找到指定的配置组       |
| `ExinOne\MixinSDK\Exceptions\LoadPrivateKeyException`      | 私钥格式等错误         |
| `ExinOne\MixinSDK\Exceptions\ClassNotFoundException`       | 寻找指定的 module 失败 |

## WARNING

1. 进行如下操作可以配置 `iterator`, 在加密 PIN 时会使用到这个变量。在大部分时候，这个变量基本不需要修改。如果需要修改这个变量，请务必知道你在做什么。[关于 iterator 更详细的说明](https://developers.mixin.one/docs/dapp/guide/pin#encrypting-pin)
   
   ```php
    $mixinSdk->wallet()->setIterator($iterator)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // 默认使用 ( microtime(true) . 随机数 ) * 1000000000 作为 iterator
   ```

2. 设定 Http Request 超时时间
   
   ```php
    $iterator = [time()];
    // 如果是在 $mixinSdk->pin()->updatePin($oldPin,$pin) 中使用,
    // $iterator 需要是有两个元素的数组, 即 count($iterator) == 2
   
    $mixinSdk->wallet()->setTimeout(10)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // 默认超时时间为 20s
   ```

3. 获取原始结果
   
   ```php
    $mixinSdk->wallet()->setRaw(true)->transfer($asset_id, $opponent_id, $pin, $amount, $memo);
    // 返回 MixinNetwork 原始 Response 内容
   ```

## Alternatives

[[exinone/laravel-mixin-sdk](https://github.com/ExinOne/laravel-mixin-sdk)]

[[zamseam/mixin](https://github.com/zamseam/mixin)]

## LICENSE

**MIT**
