<?php

namespace Flesh404\Kaspa\Laravel\Address\Analyzer;

use Flesh404\Kaspa\Laravel\Address\Address\KaspaAddress;

/**
 * Lightweight helper for analyzing Kaspa address strings.
 *
 * Provides validation and extracts prefix and network information
 * without throwing exceptions to the caller.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Analyzer
 */
final class AddressAnalyzer
{
    /**
     * Analyzes a Kaspa address string.
     *
     * Returns validation status and, if valid, the detected
     * prefix and network values.
     *
     * @param string $input
     * @return array{
     *     valid: bool,
     *     prefix?: string,
     *     network?: string,
     *     errors: string[]
     * }
     */
    public static function analyze(string $input): array
    {
        try {
            $address = KaspaAddress::parse($input);

            return [
                'valid'   => true,
                'prefix'  => $address->prefix()->value,
                'network' => $address->network()->value,
                'errors'  => [],
            ];
        } catch (\Throwable $e) {
            return [
                'valid'  => false,
                'errors' => [$e->getPrevious()->getMessage()],
            ];
        }
    }
}
