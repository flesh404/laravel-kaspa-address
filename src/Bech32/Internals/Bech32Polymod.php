<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32\Internals;

/**
 * Low-level Bech32 polymod checksum implementation.
 *
 * Implements the Bech32 checksum algorithm as specified in
 * BIP-0173 (Bech32 Address Format).
 *
 * This class is chain-agnostic and operates purely on:
 * - the human-readable part (HRP / prefix)
 * - the Bech32 data payload encoded as 5-bit values (uint5)
 *
 * Kaspa uses this algorithm unchanged with a Kaspa-specific prefix.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32\Internals
 */
final class Bech32Polymod
{
    /**
     * Length of the Bech32 checksum in 5-bit values.
     *
     * Bech32 uses an 8-character checksum (40 bits total).
     *
     * @var int
     */
    public const CHECKSUM_LENGTH = 8;

    /**
     * Generator coefficients used for the Bech32 polymod calculation.
     *
     * Defined by the Bech32 specification (BIP-0173).
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

    /**
     * Calculates the Bech32 checksum for a given HRP and data payload.
     *
     * The input data must already be encoded as 5-bit (uint5) values.
     * The returned checksum consists of {@see CHECKSUM_LENGTH} uint5 values
     * that must be appended to the data before encoding.
     *
     * @param string $hrp  Human-readable part (prefix before the separator)
     * @param int[]  $data Bech32 payload encoded as uint5 values
     *
     * @return int[] Calculated checksum as uint5 values
     */
    public static function calculate(string $hrp, array $data): array
    {
        $values = array_merge(
            self::hrpExpand($hrp),
            [0],
            $data,
            array_fill(0, self::CHECKSUM_LENGTH, 0)
        );

        $polymod = self::polymod($values);

        $checksum = [];
        for ($i = 0; $i < self::CHECKSUM_LENGTH; $i++) {
            $checksum[] = ($polymod >> (5 * (self::CHECKSUM_LENGTH - 1 - $i))) & 31;
        }

        return $checksum;
    }

    /**
     * Verifies the Bech32 checksum for a given HRP and data payload.
     *
     * The payload must include the checksum values at the end.
     *
     * @param string $hrp  Human-readable part (prefix before the separator)
     * @param int[]  $data Bech32 payload including checksum (uint5 values)
     *
     * @return bool True if the checksum is valid, false otherwise
     */
    public static function verify(string $hrp, array $data): bool
    {
        $values = array_merge(
            self::hrpExpand($hrp),
            [0],
            $data
        );

        return self::polymod($values) === 0;
    }

    /**
     * Expands the human-readable part (HRP) into 5-bit values.
     *
     * This is part of the Bech32 checksum preimage construction
     * and applies a bitmask of {@code 0x1f} to each character.
     *
     * @param string $hrp Human-readable part
     *
     * @return int[] Expanded HRP as uint5 values
     */
    private static function hrpExpand(string $hrp): array
    {
        return array_map(
            static fn (string $c): int => ord($c) & 31,
            str_split($hrp)
        );
    }

    /**
     * Computes the Bech32 polymod value.
     *
     * This function implements the BCH code over GF(2^5) used by Bech32
     * to detect errors in the encoded address.
     *
     * @param int[] $values Combined HRP and payload values (uint5)
     *
     * @return int Calculated polymod value
     */
    private static function polymod(array $values): int
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