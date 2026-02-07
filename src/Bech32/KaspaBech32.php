<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32;

use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32\{
    InvalidBech32Length,
    MixedCaseBech32String,
    MissingBech32Separator,
    InvalidBech32SeparatorPosition,
    InvalidBech32Checksum,
    InvalidBech32Character
};

/**
 * Kaspa-specific Bech32 decoder implementation.
 *
 * Decodes Bech32-encoded Kaspa addresses and verifies their checksum
 * according to the Kaspa Bech32 specification.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32
 */
final class KaspaBech32
{
    /**
     * Bech32 character set used for base32 decoding.
     *
     * @var string
     */
    private const CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';

    /**
     * Length of the Bech32 checksum in 5-bit values.
     *
     * @var int
     */
    private const CHECKSUM_LENGTH = 8;

    /**
     * Generator coefficients used for Bech32 polymod checksum calculation.
     *
     * @var int[]
     */
    private const GENERATOR = [
        0x98f2bc8e61,
        0x79b76d99e2,
        0xf33e5fb3c4,
        0xae2eabe2a8,
        0x1e4f43e470,
    ];

    // ---------------------------------------------------------------------

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
            'data'   => array_slice($decoded, 0, -self::CHECKSUM_LENGTH),
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
        if (strlen($encoded) < self::CHECKSUM_LENGTH + 2) {
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

        if ($pos < 1 || $pos + self::CHECKSUM_LENGTH + 1 > strlen($encoded)) {
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
        if (!self::verifyChecksum($prefix->value, $decoded)) {
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
            $idx = strpos(self::CHARSET, $char);
            if ($idx === false) {
                throw new InvalidBech32Character($char);
            }
            $out[] = $idx;
        }
        return $out;
    }

    /**
     * Verifies the Bech32 checksum for the given prefix and payload.
     *
     * @param string $prefix Human-readable part (HRP)
     * @param int[]  $payload Decoded data including checksum
     * @return bool
     */
    private static function verifyChecksum(string $prefix, array $payload): bool
    {
        $values = array_merge(
            self::prefixToUint5Array($prefix),
            [0],
            self::ints($payload)
        );

        return self::polyMod($values) === 0;
    }

    /**
     * Converts the prefix (HRP) into an array of 5-bit values.
     *
     * @param string $prefix Human-readable part
     * @return int[] Prefix encoded as uint5 values
     */
    private static function prefixToUint5Array(string $prefix): array
    {
        $out = [];
        foreach (str_split($prefix) as $c) {
            $out[] = ord($c) & 31;
        }
        return $out;
    }

    /**
     * Ensures all values in the array are integers.
     *
     * @param array $bytes Input values
     * @return int[] Integer-casted values
     */
    private static function ints(array $bytes): array
    {
        return array_map('intval', $bytes);
    }

    /**
     * Computes the Bech32 polymod checksum.
     *
     * @param int[] $values Combined prefix and payload values
     * @return int Calculated checksum value
     */
    private static function polyMod(array $values): int
    {
        $chk = 1;

        foreach ($values as $v) {
            $top = $chk >> 35;
            $chk = (($chk & 0x07ffffffff) << 5) ^ $v;

            foreach (self::GENERATOR as $i => $gen) {
                if ((($top >> $i) & 1) === 1) {
                    $chk ^= $gen;
                }
            }
        }

        return $chk ^ 1;
    }
}