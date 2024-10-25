<?php

namespace ExinOne\MixinSDK\Utils\TransactionV5;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use ExinOne\MixinSDK\Exceptions\EncodeFailException;
use ExinOne\MixinSDK\Exceptions\EncodeNotYetImplementedException;

class Encoder
{
    const TX_VERSION_COMMON_ENCODING = 2;
    const TX_VERSION_BLAKE_3_HASH    = 3;
    const TX_VERSION_REFERENCES      = 4;

    const EXTRA_SIZE_STORAGE_CAPACITY = 1024 * 1024 * 4;

    const MINIMUM_ENCODING_INT = 0x1; // 1
    const MAXIMUM_ENCODING_INT = 0xFFFF; // 65535


    const MAGIC = "\x77\x77";
    const NULL  = "\x00\x00";

    private $buffer = '';

    public function encodeTransaction(array $signed)
    {
        if (
            ! isset($signed['version'])
            || $signed['version'] < self::TX_VERSION_COMMON_ENCODING
            || $signed['version'] > 255 // 0xff
        ) {
            throw new EncodeFailException('INVALID_VERSION');
        }

        $this->write(self::MAGIC);
        $this->write("\x00".chr($signed['version']));

        $this->write(hex2bin($signed['asset']));

        $il = count($signed['inputs']);
        $this->writeInt($il);
        foreach ($signed['inputs'] as $input) {
            $this->encodeInput($input);
        }

        $ol = count($signed['outputs']);
        $this->writeInt($ol);
        foreach ($signed['outputs'] as $output) {
            $this->encodeOutput($output);
        }

        if (isset($signed['references'])) {
            throw new EncodeNotYetImplementedException('REFERENCES_NOT_YET_IMPLEMENTED');
        } else {
            $this->writeInt(0);
        }

        $_extra = hex2bin($signed['extra']);
        $el     = strlen($_extra);
        if ($el > self::EXTRA_SIZE_STORAGE_CAPACITY) {
            throw new EncodeFailException('EXTRA_TOO_LARGE');
        }

        $this->writeUint32($el);
        $this->write($_extra);

        if (isset($signed['aggregated_signature'])) {
            throw new EncodeNotYetImplementedException('AGGREGATED_SIGNATURE_NOT_YET_IMPLEMENTED');
        } else {
            if (isset($signed['signatures_map'])) {
                $sl = count($signed['signatures_map']);
                if ($sl >= self::MAXIMUM_ENCODING_INT) {
                    throw new EncodeFailException('SIGNATURES_TOO_MANY');
                }
                $this->writeInt($sl);
                foreach ($signed['signatures_map'] as $sm) {
                    $this->encodeSignatures($sm);
                }
            } else {
                $this->writeInt(0);
            }
        }

        return bin2hex($this->buffer);
    }

    private function write(string $bytes)
    {
        $this->buffer .= $bytes;
    }

    private function writeInt(int $int)
    {
        if ($int > self::MAXIMUM_ENCODING_INT) {
            throw new EncodeFailException("INTEGER_TOO_LARGE");
        }

        $this->write(pack('n', $int)); // n for 16-bit unsigned integer (big endian)
    }

    private function writeDecimal(string $decimal)
    {
        $bytes = BigDecimal::of($decimal)
            ->multipliedBy('1e8')
            ->toScale(0, RoundingMode::DOWN)
            ->toBigInteger()
            ->toBytes(false);

        $this->writeInt(strlen($bytes));
        $this->write($bytes);
    }

    private function writeUint16($d)
    {
        if ($d >= self::MAXIMUM_ENCODING_INT) {
            throw new EncodeFailException('INTEGER_TOO_LARGE');
        }

        // 使用 pack 将 16位无符号整数打包成大端序的二进制字符串
        $bytes = pack('n', $d);

        $this->write($bytes);
    }

    private function writeUint32($d)
    {
        // 使用 pack 将 32位无符号整数打包成大端序的二进制字符串
        $bytes = pack('N', $d);

        $this->write($bytes);
    }

    private function encodeInput(array $input)
    {
        $this->write(hex2bin($input['hash']));
        $this->writeInt($input['index']);

        $_genesis = '';
        if (isset($input['genesis'])) {
            // $_genesis = hex2bin($input['genesis']);
            throw new EncodeNotYetImplementedException('GENESIS_NOT_YET_IMPLEMENTED');
        }
        $this->writeInt(strlen($_genesis));
        $this->write($_genesis);

        if (isset($input['deposit'])) {
            throw new EncodeNotYetImplementedException('DEPOSIT_NOT_YET_IMPLEMENTED');
        } else {
            $this->write(self::NULL);
        }

        if (isset($input['mint'])) {
            throw new EncodeNotYetImplementedException('MINT_NOT_YET_IMPLEMENTED');
        } else {
            $this->write(self::NULL);
        }
    }

    private function encodeOutput(array $output)
    {
        if (! isset($output['type']) || $output['type'] > 255) {
            throw new EncodeFailException('INVALID_OUTPUT_TYPE');
        }

        $this->write("\x00".chr($output['type']));
        $this->writeDecimal($output['amount']);

        $keys = $output['keys'] ?? [];
        $this->writeInt(count($keys));
        foreach ($keys as $key) {
            $this->write(hex2bin($key));
        }

        if ($output['mask'] ?? false) {
            $this->write(hex2bin($output['mask']));
        } else {
            $this->write(str_repeat(chr(0x00), 32));
        }

        if ($output['script'] ?? false) {
            $_script = hex2bin($output['script']);
            $this->writeInt(strlen($_script));
            $this->write($_script);
        } else {
            $this->writeInt(0);
        }

        if (isset($output['withdrawal'])) {
            $this->write(self::MAGIC);
            $this->writeInt(strlen($output['withdrawal']['address']));
            $this->write($output['withdrawal']['address']);

            $this->writeInt(strlen($output['withdrawal']['tag']));
            $this->write($output['withdrawal']['tag']);
        } else {
            $this->write(self::NULL);
        }
    }

    private function encodeSignatures(array $signatures)
    {
        ksort($signatures);
        $this->writeInt(count($signatures));

        foreach ($signatures as $index => $signature) {
            $this->writeUint16($index);
            $this->write(hex2bin($signature));
        }
    }
}