<?php

use Flesh404\Kaspa\Laravel\Address\Bech32\KaspaBech32;
use Flesh404\Kaspa\Laravel\Address\Exceptions\InvalidBech32String;
use Orchestra\Testbench\TestCase;

/**
 * Unit tests for the KaspaBech32 decoder.
 *
 * Verifies correct decoding of valid Bech32 addresses
 * and proper exception handling for invalid inputs.
 */
final class Bech32DecodeTest extends TestCase
{
    public function test_valid_addresses_decode(): void
    {
        $cases = [
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e',
            'kaspatest:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqhqrxplya',
            'kaspa:qp0l70zd5x85ttwd6jv7g3s3a8llzj96d8dncn4zmhv4tlzx5k2jyqh70xmfj',
        ];

        foreach ($cases as $address) {
            $decoded = KaspaBech32::decode($address);

            $this->assertArrayHasKey('prefix', $decoded);
            $this->assertArrayHasKey('data', $decoded);
            $this->assertNotEmpty($decoded['data']);
        }
    }

    public function test_invalid_string_throws(): void
    {
        $this->expectException(InvalidBech32String::class);

        KaspaBech32::decode('™');
    }

}
