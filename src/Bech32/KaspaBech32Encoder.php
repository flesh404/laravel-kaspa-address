<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32;

use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Bech32\Internals\{
    Bech32Charset,
    Bech32Polymod,
    ConvertBits
};

/**
 * Kaspa-specific Bech32 encoder.
 *
 * Encodes raw payload bytes into a valid Bech32-encoded Kaspa address.
 * The encoding process follows these steps:
 *
 * 1. Prepend the version byte to the payload
 * 2. Convert the byte stream from 8-bit to 5-bit values (convertBits)
 * 3. Calculate the Bech32 checksum over HRP and payload
 * 4. Encode the final data using the Bech32 character set
 *
 * This implementation follows the Bech32 specification (BIP-0173)
 * and is compatible with Kaspa address decoding.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32
 */
final class KaspaBech32Encoder
{
    /**
     * Encodes raw payload bytes into a Bech32 Kaspa address.
     *
     * @param KaspaPrefix $prefix
     * @param string $payload
     * @param int $version
     * @return string
     */
    public static function encodeFromBytes(KaspaPrefix $prefix, string $payload, int $version = 0): string
    {
        $data = [$version, ...array_map('ord', str_split($payload))];

        $converted = ConvertBits::convert($data, 8, 5, true);
        $checksum = Bech32Polymod::calculate($prefix->value, $converted);

        return self::encodeBase32(
            $prefix->value,
            array_merge($converted, $checksum)
        );
    }

    /**
     * Encodes a sequence of 5-bit values into the Bech32 base32 string
     * and prepends the Kaspa prefix.
     *
     * This method assumes that:
     * - the input data is already encoded as valid 5-bit (uint5) values
     * - the checksum has already been calculated and appended
     *
     * It performs **no validation** and **no checksum logic** on its own.
     * Its responsibility is strictly the final serialization step:
     *
     *     <prefix> ":" <base32(data + checksum)>
     *
     * @param string $prefix Kaspa address prefix (HRP)
     * @param int[]  $data   Bech32 payload including checksum (uint5 values)
     *
     * @return string Fully encoded Bech32 address string
     */
    private static function encodeBase32(string $prefix, array $data): string
    {
        $encoded = '';
        foreach ($data as $v) {
            $encoded .= Bech32Charset::charAt($v);
        }

        return "{$prefix}:{$encoded}";
    }
}