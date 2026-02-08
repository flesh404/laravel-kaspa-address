<?php

namespace Flesh404\Kaspa\Laravel\Address\Tests\Unit\Bech32;

use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Bech32\{
    KaspaBech32Decoder,
    KaspaBech32Encoder,
    Internals\ConvertBits
};
use Flesh404\Kaspa\Laravel\Address\Tests\TestCase;

/**
 * Tests for the KaspaBech32 encoder.
 *
 * Verifies correct Bech32 encoding, round-trip decoding,
 * and proper prefix handling.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Tests\Unit\Bech32
 */
final class KaspaBech32EncoderTest extends TestCase
{
    public function test_it_encodes_and_decodes_roundtrip_correctly(): void
    {
        $payload = random_bytes(32);

        $encoded = KaspaBech32Encoder::encodeFromBytes(
            KaspaPrefix::Mainnet,
            $payload,
            0
        );

        $decoded = KaspaBech32Decoder::decode($encoded);

        // convert full uint5 payload back to bytes
        $decodedBytes = ConvertBits::convert(
            $decoded['data'],
            5,
            8,
            false
        );

        // first byte = version
        $version = array_shift($decodedBytes);

        // remaining bytes = payload
        $this->assertSame(
            array_values(unpack('C*', $payload)),
            $decodedBytes
        );

        $this->assertSame(0, $version);
        $this->assertSame(KaspaPrefix::Mainnet, $decoded['prefix']);

        $this->assertSame(
            array_values(unpack('C*', $payload)),
            $decodedBytes
        );
    }

    public function test_it_uses_the_correct_prefix(): void
    {
        $encoded = KaspaBech32Encoder::encodeFromBytes(
            KaspaPrefix::Testnet,
            random_bytes(32),
            0
        );

        $this->assertStringStartsWith('kaspatest:', $encoded);
    }
}
