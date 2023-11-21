<?php

namespace ExinOne\MixinSDK\Tests\Feature;

use ExinOne\MixinSDK\Utils\Blake3;
use PHPUnit\Framework\TestCase;

class Blake3Test extends TestCase
{
    public function test_blake3_hash()
    {
        $b = new Blake3();
        $hash = $b->hash("test123check2this3up4");
        self::assertEquals('ea3f45404f093b61179be42275979e659c66da63a443ceb5be347f15cc0a4af4', $hash);
    }
}