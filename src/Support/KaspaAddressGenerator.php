<?php

namespace Flesh404\Kaspa\Laravel\Address\Support;

use Flesh404\Kaspa\Laravel\Address\{
    KaspaAddress
};
use Flesh404\Kaspa\Laravel\Address\{
    Bech32\KaspaBech32Encoder,
    Enums\KaspaPrefix
};

/**
 * Convenience factory for generating valid Kaspa addresses.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Support
 */
final class KaspaAddressGenerator
{
    /**
     * Generates a valid Kaspa address.
     *
     * @param KaspaPrefix $prefix
     * @param int $payloadLength
     * @param int $version
     * @return KaspaAddress
     */
    public static function generate(KaspaPrefix $prefix, int $payloadLength = 32, int $version = 0): KaspaAddress
    {
        $payload = random_bytes($payloadLength);

        $encoded = KaspaBech32Encoder::encodeFromBytes(
            $prefix,
            $payload,
            $version
        );

        return KaspaAddress::parse($encoded);
    }
}