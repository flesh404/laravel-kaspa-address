<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

use Flesh404\Kaspa\Laravel\Address\Exceptions\ErrorCodeException;

/**
 *Base class for all Bech32-related address errors.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
abstract class Bech32ErrorGroupException extends ErrorCodeException
{
    /**
     * Returns the error group.
     *
     * @return string
     */
    protected function errorGroup(): string
    {
        return 'bech32';
    }
}
