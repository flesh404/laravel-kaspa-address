<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

/**
 * Thrown when a Bech32 string has an invalid length.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
final class InvalidBech32Length extends Bech32ErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'length_invalid';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Invalid Bech32 string length.';
    }
}