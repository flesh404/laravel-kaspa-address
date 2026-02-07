<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32;

use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Bech32\Internals\{
    Bech32Charset,
    Bech32Polymod
};
use Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32\{
    InvalidBech32Length,
    MixedCaseBech32String,
    MissingBech32Separator,
    InvalidBech32SeparatorPosition,
    InvalidBech32Checksum,
    InvalidBech32Character
};

/**
 * Kaspa-specific Bech32 decoder.
 *
 * This class is responsible for parsing and validating Bech32-encoded
 * Kaspa addresses. It performs:
 *
 * - structural validation (length, casing, separator)
 * - prefix (HRP) validation against supported Kaspa prefixes
 * - base32 decoding
 * - checksum verification using the Bech32 polymod algorithm
 *
 * The underlying Bech32 checksum and encoding rules follow the
 * Bech32 specification (BIP-0173), while the prefix semantics
 * are Kaspa-specific.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32
 */
final class KaspaBech32Decoder
{
    /**
     * Decodes a Kaspa Bech32 address into prefix and payload data.
     *
     * @param string $encoded
     * @return array{prefix: KaspaPrefix, data: int[]}
     */
    public static function decode(string $encoded): array
    {
        $encoded = trim($encoded);

        self::assertValidLength($encoded);
        self::assertValidCasing($encoded);

        $encoded = strtolower($encoded);

        [$prefix, $dataPart] = self::splitAddress($encoded);

        $decoded = self::decodeFromBase32($dataPart);

        self::assertValidChecksum($prefix, $decoded);

        // strip checksum
        return [
            'prefix' => $prefix,
            'data'   => array_slice($decoded, 0, -Bech32Polymod::CHECKSUM_LENGTH),
        ];
    }

    // ---------------------------------------------------------------------
    // Internals
    // ---------------------------------------------------------------------

    /**
     * Ensures the encoded string is long enough to contain a prefix, separator and checksum.
     *
     * @param string $encoded
     * @return void
     */
    private static function assertValidLength(string $encoded): void
    {
        if (strlen($encoded) < Bech32Polymod::CHECKSUM_LENGTH + 2) {
            throw new InvalidBech32Length();
        }
    }

    /**
     * Ensures the Bech32 string does not use mixed casing.
     * Bech32 strings must be either all lowercase or all uppercase.
     *
     * @param string $encoded
     * @return void
     */
    private static function assertValidCasing(string $encoded): void
    {
        if ($encoded !== strtolower($encoded) && $encoded !== strtoupper($encoded)) {
            throw new MixedCaseBech32String();
        }
    }

    /**
     * Splits a Bech32-encoded Kaspa address into prefix and data part.
     * Also validates that the prefix is a supported Kaspa prefix.
     *
     * @param string $encoded
     * @return array{0: KaspaPrefix, 1: string}
     */
    private static function splitAddress(string $encoded): array
    {
        $pos = strrpos($encoded, ':');

        if ($pos === false) {
            throw new MissingBech32Separator();
        }

        if ($pos < 1 || $pos + Bech32Polymod::CHECKSUM_LENGTH + 1 > strlen($encoded)) {
            throw new InvalidBech32SeparatorPosition();
        }

        $prefixString = substr($encoded, 0, $pos);
        $prefix = KaspaPrefix::parse($prefixString);

        return [
            $prefix,
            substr($encoded, $pos + 1),
        ];
    }

    /**
     * Validates the Bech32 checksum for the given prefix and payload.
     *
     * @param KaspaPrefix $prefix
     * @param array $decoded
     * @return void
     * @throws InvalidBech32Checksum
     */
    private static function assertValidChecksum(KaspaPrefix $prefix, array $decoded): void
    {
        if (!Bech32Polymod::verify($prefix->value, $decoded)) {
            throw new InvalidBech32Checksum();
        }
    }

    /**
     * Decodes a Bech32 base32 string into its integer values.
     *
     * @param string $input Base32-encoded data part
     * @return int[] Array of 5-bit integer values
     *
     * @throws InvalidBech32Character
     */
    private static function decodeFromBase32(string $input): array
    {
        $out = [];
        foreach (str_split($input) as $char) {
            $idx = Bech32Charset::indexOf($char);
            if ($idx === false) {
                throw new InvalidBech32Character($char);
            }
            $out[] = $idx;
        }
        return $out;
    }
}