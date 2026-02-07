<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Address;

use Flesh404\Kaspa\Laravel\Address\Exceptions\ErrorCodeException;

/**
 * Base class for all Kaspa address related errors
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Address
 */
abstract class AddressErrorGroupException extends ErrorCodeException
{
    /**
     * Returns the error group.
     *
     * @return string
     */
    protected function errorGroup(): string
    {
        return 'address';
    }
}