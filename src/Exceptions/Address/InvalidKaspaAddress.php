<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Address;

/**
 * Thrown when a Kaspa address is syntactically or semantically invalid.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Address
 */
final class InvalidKaspaAddress extends AddressErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'invalid';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Invalid Kaspa address.';
    }
}