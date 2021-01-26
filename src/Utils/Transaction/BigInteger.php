<?php
declare(strict_types=1);

namespace ExinOne\MixinSDK\Utils\Transaction;

class BigInteger
{
    // amd64 平台
    // const max_base = 10 + ('z' - 'a' + 1) + ('Z' - 'A' + 1)
    // const max_base_small = 10 + ('z' - 'a' + 1);
    public const max_base = 62;
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