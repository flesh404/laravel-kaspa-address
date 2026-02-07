<?php

namespace Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32;

/**
 * Thrown when a Bech32 string contains an invalid character.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32
 */
final class InvalidBech32Character extends Bech32ErrorGroupException
{
    /**
     * InvalidBech32Character constructor.
     *
     * @param string $character
     * @param \Throwable|null $previous
     */
    public function __construct(
        private readonly string $character,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            "Invalid Bech32 character '{$character}'.",
            $previous
        );
    }

    /**
     * Returns the specific error key within the group.
     *
     * @return string
     */
    public function errorKey(): string
    {
        return 'character_invalid';
    }

    /**
     * Returns the default human-readable error message.
     *
     * @return string
     */
    protected function defaultMessage(): string
    {
        return 'Invalid Bech32 character.';
    }
}
