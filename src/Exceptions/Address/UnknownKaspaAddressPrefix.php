<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Address;

/**
 * Thrown when the address prefix is not a supported Kaspa prefix.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Address
 */
final class UnknownKaspaAddressPrefix extends AddressErrorGroupException
{
    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    protected function errorKey(): string
    {
        return 'prefix_unknown';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Unknown Kaspa address prefix.';
    }
}