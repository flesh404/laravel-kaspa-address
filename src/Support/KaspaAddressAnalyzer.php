<?php

namespace Flesh404\Kaspa\Laravel\Address\Support;

use Flesh404\Kaspa\Laravel\Address\{
    KaspaAddress,
    Exceptions\ErrorCodeException
};

/**
 * Lightweight helper for analyzing Kaspa address strings.
 *
 * Provides validation and extracts prefix and network information
 * without throwing exceptions to the caller.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Support
 */
final class KaspaAddressAnalyzer
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
        } catch (ErrorCodeException $e) {
            return [
                'valid'  => false,
                'errors' => self::collectErrors($e),
            ];
        }
    }

    /**
     * Collects all error-code exceptions from an exception chain.
     *
     * @param \Throwable $e
     * @return array<int, array{code: string, message: string}>
     */
    private static function collectErrors(\Throwable $e): array
    {
        $errors = [];

        while ($e) {
            if ($e instanceof ErrorCodeException) {
                $errors[] = [
                    'code'    => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                ];
            }

            $e = $e->getPrevious();
        }

        return $errors;
    }
}
