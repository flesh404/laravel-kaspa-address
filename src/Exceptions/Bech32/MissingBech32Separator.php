<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

/**
 * Thrown when a Bech32 string is missing the required separator.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
final class MissingBech32Separator extends Bech32ErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'separator_missing';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Missing ":" separator in Bech32 string.';
    }
}