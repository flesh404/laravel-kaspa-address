<?php

namespace Flesh404\Kaspa\Laravel\Address\Address;

use Flesh404\Kaspa\Laravel\Address\{
    Bech32\KaspaBech32,
    Exceptions\InvalidKaspaAddress
};
use Flesh404\Kaspa\Laravel\Address\Enums\{
    KaspaPrefix,
    KaspaNetwork
};

/**
 * Value object representing a validated Kaspa address.
 *
 * Parses, validates, and exposes network and prefix information
 * derived from a Bech32-encoded Kaspa address.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Address
 */
final class KaspaAddress
{
    /**
     * Original Bech32-encoded address string.
     *
     * @var string
     */
    private string $address;

    /**
     * Parsed Kaspa address prefix.
     *
     * @var KaspaPrefix
     */
    private KaspaPrefix $prefix;

    /**
     * Network derived from the address prefix.
     *
     * @var KaspaNetwork
     */
    private KaspaNetwork $network;

    /**
     * Creates a new KaspaAddress instance.
     *
     * @param string       $address Original address string
     * @param KaspaPrefix  $prefix  Parsed address prefix
     */
    private function __construct(string $address, KaspaPrefix $prefix)
    {
        $this->address = $address;
        $this->prefix  = $prefix;
        $this->network = $prefix->network();
    }

    /**
     * Parses and validates a Kaspa address.
     *
     * @param string $input Bech32-encoded Kaspa address
     * @return self
     *
     * @throws InvalidKaspaAddress If the address is invalid
     */
    public static function parse(string $input): self
    {
        try {
            $decoded = KaspaBech32::decode($input);
        } catch (\Throwable $e) {
            throw new InvalidKaspaAddress('Invalid Kaspa address.', previous: $e);
        }

        return new self($input, KaspaPrefix::parse($decoded['prefix']));
    }

    /**
     * Checks whether the given string is a valid Kaspa address.
     *
     * @param string $input
     * @return bool
     */
    public static function isValid(string $input): bool
    {
        try {
            self::parse($input);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Returns the Kaspa network of the address.
     *
     * @return KaspaNetwork
     */
    public function network(): KaspaNetwork
    {
        return $this->network;
    }

    /**
     * Returns the address prefix.
     *
     * @return KaspaPrefix
     */
    public function prefix(): KaspaPrefix
    {
        return $this->prefix;
    }

    /**
     * Returns the original address string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->address;
    }
}