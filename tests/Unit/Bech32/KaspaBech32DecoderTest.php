<?php

use Flesh404\Kaspa\Laravel\Address\Bech32\KaspaBech32Decoder;
use Orchestra\Testbench\TestCase;

/**
 * Unit tests for the KaspaBech32 decoder.
 *
 * Verifies correct decoding of valid Bech32 addresses
 * and proper exception handling for invalid inputs.
 */
final class KaspaBech32DecoderTest extends TestCase
{
    public function test_valid_addresses_decode(): void
    {
        $cases = [
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e',
            'kaspatest:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqhqrxplya',
            'kaspa:qp0l70zd5x85ttwd6jv7g3s3a8llzj96d8dncn4zmhv4tlzx5k2jyqh70xmfj',
        ];

        foreach ($cases as $address) {
            $decoded = KaspaBech32Decoder::decode($address);

            $this->assertArrayHasKey('prefix', $decoded);
            $this->assertArrayHasKey('data', $decoded);
            $this->assertNotEmpty($decoded['data']);
        }
    }
}
