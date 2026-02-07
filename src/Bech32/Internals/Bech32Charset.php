<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32\Internals;

/**
 * Bech32 character set definition.
 *
 * Defines the canonical Bech32 character set used for encoding
 * and decoding 5-bit values according to the Bech32 specification.
 *
 * This class is intentionally immutable and contains no behavior
 * other than providing access to the character set.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32\Internals
 */
final class Bech32Charset
{
    /**
     * Canonical Bech32 character set.
     *
     * The index of each character corresponds to its 5-bit value.
     *
     * @var string
     */
    public const CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';

    /**
     * Returns the index of a character in the Bech32 charset.
     *
     * @param string $char Single-character string
     * @return int|false Index or false if not part of the charset
     */
    public static function indexOf(string $char): int|false
    {
        return strpos(self::CHARSET, $char);
    }

    /**
     * Returns the character for a given Bech32 5-bit value.
     *
     * @param int $value 5-bit value (0–31)
     * @return string
     */
    public static function charAt(int $value): string
    {
        return self::CHARSET[$value];
    }
}