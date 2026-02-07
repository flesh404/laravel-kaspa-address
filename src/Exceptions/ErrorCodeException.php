<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions;

/**
 * Base class for all errors exposing a structured error code.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions
 */
abstract class ErrorCodeException extends \RuntimeException
{
    /**
     * ErrorCodeException constructor.
     *
     * @param string|null $message
     * @param \Throwable|null $previous
     */
    public function __construct(?string $message = null, ?\Throwable $previous = null)
    {
        parent::__construct(
            $message ?? $this->defaultMessage(),
            0,
            $previous
        );
    }

    /**
     * Returns a stable, machine-readable error code.
     *
     * This value is intended for APIs, events, logs and UI mapping.
     */
    final public function getErrorCode(): string
    {
        return $this->errorGroup() . '.' . $this->errorKey();
    }

    /**
     * Returns the error group (e.g. "bech32", "address").
     *
     * @return string
     */
    abstract protected function errorGroup(): string;

    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    abstract protected function errorKey(): string;

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    abstract protected function defaultMessage(): string;
}