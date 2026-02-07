<?php

namespace Flesh404\Kaspa\Laravel\Address\Bech32\Internals;

/**
 * Bit-group conversion utility used by Bech32 encoding.
 *
 * Converts a stream of values encoded with one bit resolution
 * (e.g. 8-bit bytes) into another resolution (e.g. 5-bit Bech32 values).
 *
 * This implementation is a direct, bit-exact port of the reference
 * Bech32 convertBits algorithm as defined in BIP-0173.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Bech32\Internals
 */
final class ConvertBits
{
    /**
     * Converts data between different bit resolutions.
     *
     * This method is used for:
     * - encoding: converting 8-bit bytes to 5-bit Bech32 values (with padding)
     * - decoding: converting 5-bit Bech32 values back to 8-bit bytes (without padding)
     *
     * @param int[] $data     Input values
     * @param int   $fromBits Bit size of input values
     * @param int   $toBits   Bit size of output values
     * @param bool  $pad      Whether to pad remaining bits (encoding only)
     *
     * @return int[] Converted values
     *
     * @throws \InvalidArgumentException If an invalid value is encountered
     */
    public static function convert(array $data, int $fromBits, int $toBits, bool $pad): array
    {
        $result = [];
        $acc = 0;
        $bits = 0;
        $maxv = (1 << $toBits) - 1;

        foreach ($data as $value) {
            if ($value < 0 || ($value >> $fromBits) !== 0) {
                throw new \InvalidArgumentException('Invalid value for convertBits');
            }

            $value <<= (8 - $fromBits);
            $remaining = $fromBits;

            while ($remaining > 0) {
                $toExtract = min($toBits - $bits, $remaining);

                $acc = ($acc << $toExtract) | ($value >> (8 - $toExtract));
                $value <<= $toExtract;
                $remaining -= $toExtract;
                $bits += $toExtract;

                if ($bits === $toBits) {
                    $result[] = $acc & $maxv;
                    $acc = 0;
                    $bits = 0;
                }
            }
        }

        if ($pad && $bits > 0) {
            $result[] = ($acc << ($toBits - $bits)) & $maxv;
        }

        return $result;
    }
}