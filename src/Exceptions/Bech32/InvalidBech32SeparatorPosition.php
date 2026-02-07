<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

/**
 * Thrown when the Bech32 separator is at an invalid position.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
final class InvalidBech32SeparatorPosition extends Bech32ErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'separator_position_invalid';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Invalid ":" separator position in Bech32 string.';
    }
}