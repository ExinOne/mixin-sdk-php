<?php

// 开启严格模式
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils;

use ExinOne\MixinSDK\Exceptions\InvalidInputFieldException;

/**
 * Class TransactionHelper
 *
 * @package ExinOne\MixinSDK\Utils
 */
class TransactionHelper
{
    /**
     * @param array $input_object
     *
     * @return string
     * @throws InvalidInputFieldException
     */
    public static function buildTransaction(array $input_object): string
    {
        $tx = Transaction::NewTransaction($input_object['asset']);
        // fill up inputObject
        foreach ($input_object['inputs'] as $v) {
            if (! empty($input->genesis)) {
                throw new InvalidInputFieldException("invalid input with Genesis, it's not needed in this function");
            }
            if (! empty($input->deposit)) {
                throw new InvalidInputFieldException("invalid input with Deposit, it's not needed in this function");
            }
            if (! empty($input->mint)) {
                throw new InvalidInputFieldException("invalid input with Mint, it's not needed in this function");
            }

            $tx->AddInput($v);
        }

        // fill up outputObject
        foreach ($input_object['outputs'] as $v) {
            if (strlen($v['mask']) > 0) {
                $tx->AddOutput($v);
            }
        }

        // 16进制解码为bytes
        $extra     = hex2bin($input_object['extra']);
        $tx->extra = $extra;

        $signed = $tx->AsLatestVersion();

        return bin2hex($signed->Marshal());
    }
}


class Transaction
{
    /** @var int */
    public $version = 0x01;

    /** @var string */
    public $asset;

    /** @var TransactionInput[] */
    public $inputs = [];

    /** @var TransactionOutput[] */
    public $outputs = [];

    /** @var string */
    public $extra;

    public static function NewTransaction(string $asset): self
    {
        $ret        = new self();
        $ret->asset = $asset;

        return $ret;
    }

    public function AddInput(TransactionInput $input)
    {
        $this->inputs[] = $input;
    }

    public function AddOutput(TransactionOutput $output)
    {
        if (is_string($output->amount)) {
            $output->Amount = new BigInteger($output->amount);
        } elseif (is_int($output->amount)) {
            $output->Amount = new BigInteger((string)($output->amount));
        }

        $this->outputs[] = $output;
    }

    public function AsLatestVersion(): VersionedTransaction
    {
        if ($this->version != 0x01) {
            throw new \Exception("version: {$this->version} is not support");
        }

        return new VersionedTransaction(new SignedTransaction($this));
    }
}

class SignedTransaction extends Transaction
{
    /** @var [][]crypto.Signature */
    public $signatures;

    /** @var Transaction */
    public $transaction;

    public function __construct(Transaction $v)
    {
        $this->version     = $v->version;
        $this->asset       = $v->asset;
        $this->inputs      = $v->inputs;
        $this->outputs     = $v->outputs;
        $this->extra       = $v->extra;
        $this->transaction = $v;
    }

    public function encode(): string
    {
        // 按字段的顺序
        $ret = encodeMapLen(6);
        $ret .= encodeString('Version').encodeInt($this->version);  // 序列化Version字段
        $ret .= encodeString('Asset').encodeBytes(hex2bin($this->asset));  // 序列化Asset, 先转为32长度的bytes
        $ret .= encodeString('Inputs').encodeArray($this->inputs);
        $ret .= encodeString('Outputs').encodeArray($this->outputs);
        $ret .= encodeString('Extra').encodeBytes($this->extra);  // Extra 已经被序列为bytes
        if ($this->signatures == null) {
            $ret .= encodeString('Signatures').encodeNil($this->signatures);
        }

        return $ret;
    }
}

class VersionedTransaction extends SignedTransaction
{
    /** @var SignedGenesisHackTransaction */
    public $bad_genesis;

    /** @var SignedTransaction */
    public $signed_transaction;

    public function __construct(SignedTransaction $v)
    {
        $this->version            = $v->version;
        $this->asset              = $v->asset;
        $this->inputs             = $v->inputs;
        $this->outputs            = $v->outputs;
        $this->extra              = $v->extra;
        $this->signatures         = $v->signatures;
        $this->signed_transaction = $v;
    }

    public function Marshal(): string
    {
        switch ($this->version) {
            case 0:
                return $this->CompressMsgpackMarshalPanic($this->bad_genesis);
            case 0x01:
                return $this->CompressMsgpackMarshalPanic($this->signed_transaction);
        }

        return '';
    }

    public function CompressMsgpackMarshalPanic($v): string
    {
        $payload = '';
        if ($v instanceof SignedTransaction) {
            $payload = $v->encode();
        }
        // TODO: 调用 php-zstd 进行压缩, go中使用的是gozstd, 在github.com/MixinNetwork/mixin/common/msgpack.go的方法
        // CompressMsgpackMarshalPanic 中
        return $payload;
    }
}

class TransactionInput
{
    /** @var string */
    public $hash;

    /** @var int */
    public $index;

    /** @var bytes */
    public $genesis;

    /** @var DepositData */
    public $deposit;

    /** @var MintData */
    public $mint;

    public function encode()
    {
        $ret = encodeMapLen(5);  // 一共有5个字段
        $ret .= encodeString('Hash').encodeBytes(hex2bin($this->hash));  // 序列化Hash, 先转为32长度的bytes
        $ret .= encodeString('Index').encodeInt($this->index);
        if ($this->genesis == null) {
            $ret .= encodeString('Genesis').encodeNil($this->genesis);
        }
        if ($this->deposit == null) {
            $ret .= encodeString('Deposit').encodeNil($this->deposit);
        }
        if ($this->mint == null) {
            $ret .= encodeString('Mint').encodeNil($this->mint);
        }

        return $ret;
    }
}

class BigInteger
{
    // amd64 平台
    // const max_base = 10 + ('z' - 'a' + 1) + ('Z' - 'A' + 1)
    // const max_base_small = 10 + ('z' - 'a' + 1);
    public const max_base       = 62;
    public const max_base_small = 36;

    /** @var string */
    public $abs;

    public $neg = false;

    /** @var string */
    public $nat_string;

    public $nat = [];

    public function __construct(string $i)
    {
        $this->abs = $i;
        if ($i[0] == '-') {  // 如果是负数
            $this->abs = substr($i, 1, strlen($i) - 1);
            $this->neg = true;
        }
        $tmp = $this->abs;
        // go的库中没有精确到8位以后, 所以这里需要手动处理一下
        if (strstr($tmp, '.') > 0) {  // 说明存在小数
            // $tmp = strval(round(floatval($tmp), 8));
            $tmp = number_format(floatval($tmp), 8, '.', '');
        }
        $this->nat_string = bcmul($tmp, '100000000');
        $this->parseNat();
    }

    public function parseNat()
    {
        // If frac_ok is set, a period followed by a fractional part is permitted.
        // The result value is computed as if there were no period present; and the count value is used to determine the fractional part.
        $frac_ok = false;

        // 如果 frac_ok 为true, 则 $b 必须为 0, 2, 8, 10, 16
        // 如果 $b 为0, 则根据abs字符串的前缀来判断, 如 0b 为 2
        $b      = 10;  // 默认10进制
        $prefix = 0;

        $prev      = '.';
        $inval_sep = false;
        $count     = 0;

        $b1 = strval($b);
        [$bn, $n] = self::maxPow($b);  // 获取小于系统位数的最大位
        $di = '0';  // 0 <= di < b**i < bn
        $i  = 0; // 0 <= i < n
        $dp = -1;  // 小数点的位置

        foreach (str_split($this->nat_string) as $ch) {
            if ($ch === '.' && $frac_ok) {
                $frac_ok = false;
                if ($prev === '_') {
                    $inval_sep = true;
                }
                $prev = '.';
                $dp   = $count;
            } elseif ($ch === '_' && $b == 0) {
                if ($prev != '0') {
                    $inval_sep = true;
                }
                $prev = '_';
            } else {
                if ('0' <= $ch && $ch <= '9') {
                    $d1 = $ch - '0';
                } elseif ('a' <= $ch && $ch <= 'z') {
                    $d1 = $ch - 'a' + 10;
                } elseif ('A' <= $ch && $ch <= 'Z') {
                    if ($b <= self::max_base_small) {
                        $d1 = $ch - 'A' + 10;
                    } else {
                        $d1 = $ch - 'A' + self::max_base_small;
                    }
                } else {
                    $d1 = self::max_base + 1;
                }
                if ($d1 >= $b1) {
                    // ch 不是任何一个数字, 说明是无效的abs
                    break;
                }
                $prev = '0';
                $count++;

                $di = bcadd(bcmul($di, $b1), strval($d1));
                $i++;

                if ($i == $n) {
                    $this->nat = $this->mulAddWW($this->nat, $bn, $di);
                    $di        = '0';
                    $i         = 0;
                }
            }
        }

        if ($inval_sep || $prev === '_') {
            throw new \Exception("'_' must separate successive digits");
        }

        if ($count == 0) {
            if ($prefix === '0') {
                $this->nat = [];

                return;
            }
            throw new \Exception('number has no digits');
        }

        if ($i > 0) {
            // prettyPrint(["-----", $this->nat, $b1, $i, $di]);
            $this->nat = $this->mulAddWW($this->nat, bcpow($b1, strval($i)), $di);
        }

        if ($dp >= 0) {
            $count = $dp - $count;
        }
    }

    // 求出基于当前的进制, 在小于当前系统最大位数时的最大次方
    public static function maxPow(int $base): array
    {
        $p = strval($base);
        $n = 1;
        for ($max = (bcpow('2', strval(PHP_INT_SIZE * 8)) - 1) / $base; $p <= $max;) {
            $p = bcmul($p, strval($base));
            $n++;
        }

        return [$p, $n];
    }

    public function encode(): string
    {
        // 转成 big int 的二进制, 大端序
        $ret = '';
        foreach ($this->nat as $nat) {
            $ret = $this->encodeNat($nat).$ret;
        }

        return $ret;
    }

    private function mulAddWW(array $x, string $y, string $r): array
    {
        $m = count($x);
        if ($m == 0 || $y == 0) {
            return [$r];
        }
        $ret = [];
        // print_r("========\n");
        // prettyPrint([$ret, $x, $y, $r, $m]);
        $ret[$m] = $this->mulAddVWW($ret, $x, $y, $r);
        // prettyPrint($ret);
        return $ret;
    }

    // From math/big/arith_arm64.s mulAddVWW
    // 参考文章: https://blog.csdn.net/LaoK189/article/details/83661104
    // y: 进制
    // r: 偏移
    // x = [a1, a2 ...]
    // c = a1*2^(64*0)*y + a2*2^(64*1)*y + ... an*2^(64*(n-1))*y + r
    private function mulAddVWW(array &$z, array $x, string $y, string $r): string
    {
        $tmp = '0';
        $i   = 0;
        foreach ($x as $key => $v) {
            $i++;
            $c       = bcadd(bcmul($v, $y), $r);
            $tmp     = bcdiv($c, bcpow('2', strval(PHP_INT_SIZE * 8)));
            $r       = bcadd($r, $tmp);
            $z[$key] = bcsub($c, bcmul($tmp, bcpow('2', strval(PHP_INT_SIZE * 8))));
        }

        return $tmp;
    }

    private function encodeNat(string $nat): string
    {
        $len   = strlen($nat);
        $index = $len * PHP_INT_SIZE;
        $buf   = [];
        for ($j = 0; $j < PHP_INT_SIZE; $j++) {
            $index--;
            // 取最低8位,高位舍去,转为数字, go语法
            // $buf[$index] = $nat - ($nat >> 8) * 256;
            // $nat >>= 8;
            $tmp         = bcdiv($nat, '256');
            $buf[$index] = bcsub($nat, bcmul($tmp, '256'));
            $nat         = $tmp;
        }
        // 这里为了计算出非0的起始位
        for (; $index < ($len * PHP_INT_SIZE) && $buf[$index] == 0; $index++) {
        }
        // 取起始位到最高位的数组
        $max_key = $len * PHP_INT_SIZE - 1;
        $ret     = [];
        if ($max_key >= $index) {
            foreach (range($index, $max_key) as $v) {
                $ret[] = $buf[$v];
            }
        }

        return pack('C'.($max_key - $index + 1), ...$ret);
    }
}

class TransactionOutput
{
    /** @var string */
    public $type = 0;

    /** @var BigInteger */
    public $amount;

    /** @var array */
    public $keys;

    /** @var string */
    public $script;

    /** @var string */
    public $mask;

    public function encode(): string
    {
        $ret = encodeMapLen(5);  // 一共有5个字段, 对应的Withdrawal 被msgpack标记为不传递
        $ret .= encodeString('Type').encodeInt($this->type);
        $ret .= encodeString('Amount').encodeExt($this->amount);
        $ret .= encodeString('Keys').encodeArray($this->keys);
        $ret .= encodeString('Script').encodeBytes(hex2bin($this->script));
        $ret .= encodeString('Mask').encodeBytes(hex2bin($this->mask));

        return $ret;
    }
}


function prettyString(array $obj): string
{
    $ret = '';
    foreach ($obj as $v) {
        if (is_array($v)) {
            $ret .= '['.prettyString($v).'] ';
        } else {
            $ret .= $v.' ';
        }
    }

    return $ret;
}

function prettyPrint(array $obj)
{
    echo prettyString($obj)."\n";
}

function echoBin(string $a)
{
    $ret = [];
    $len = strlen($a);
    for ($i = 0; $i < $len; $i++) {
        $ret[] = ord($a[$i]);
    }
    echo implode(' ', $ret)."\n";

    return $ret;
}

function assertArray(array $a1, array $a2): bool
{
    foreach ($a1 as $key => $v) {
        if ($v != $a2[$key]) {
            return false;
        }
    }

    return true;
}

function encodeNil($data): string
{
    if ($data == null) {
        return pack('C', 0xc0);
    }

    return '';
}

function encodeMapLen(int $data): string
{
    if ($data < 16) {
        return pack('C', 0x80 | $data);
    } elseif ($data < 65536) {
        return pack('C2n', 0xde, $data >> 8, $data);
    }
}

function encodeString(string $data): string
{
    $len = strlen($data);
    if ($len < 32) {
        return pack('C', 0xa0 | $len).$data;
    } elseif ($len < 256) {
        return pack('C', 0xd9).'';
    }
}

function encodeInt(int $data): string
{
    if ($data < 32) {
        return pack('C', $data);
    }
}

function encodeBytes(string $data): string
{
    $len = strlen($data);
    if ($len < 256) {
        return pack('C2', 0xc4, $len).$data;
    }
}

function encodeArray(array $data): string
{
    $ret = '';
    $len = count($data);
    if ($len < 16) {
        $ret .= pack('C', 0x90 | $len);
    }
    for ($i = 0; $i < $len; $i++) {
        $ret .= encodeObject($data[$i]);
    }

    return $ret;
}

function encodeObject($data): string
{
    $ret = '';
    if ($data instanceof TransactionInput) {
        $ret = $data->encode();
    } elseif ($data instanceof TransactionOutput) {
        $ret = $data->encode();
    } elseif (is_string($data)) {
        $ret = encodeBytes(hex2bin($data));
    }

    return $ret;
}

function encodeExt($obj): string
{
    if (! $obj instanceof BigInteger) {
        throw new \Exception('error type');
    }
    $data = $obj->encode();

    $type_id = 0;  // go 代码中写死的
    $len     = strlen($data);
    if ($len == 1) {
        return pack('C2', 0xd4, $type_id).$data;
    } elseif ($len == 2) {
        return pack('C2', 0xd5, $type_id).$data;
    } elseif ($len == 4) {
        return pack('C2', 0xd6, $type_id).$data;
    } elseif ($len == 8) {
        return pack('C2', 0xd7, $type_id).$data;
    } elseif ($len == 16) {
        return pack('C2', 0xd8, $type_id).$data;
    }

    if ($len < 256) {
        return pack('C3', 0xc7, $len, $type_id).$data;
    } elseif ($len < 65536) {
        return pack('C2nC', 0xc8, $len >> 8, $len, $type_id).$data;  // 没有测试
    }

    return pack('C2nNJC', 0xc9, $len >> 24, $len >> 16, $len >> 8, $len, $type_id).$data;  // 没有测试
}