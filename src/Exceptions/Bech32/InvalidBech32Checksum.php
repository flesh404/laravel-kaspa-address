<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

/**
 * Thrown when a Bech32 checksum validation fails.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
final class InvalidBech32Checksum extends Bech32ErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'checksum_invalid';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Invalid Bech32 checksum.';
    }
}