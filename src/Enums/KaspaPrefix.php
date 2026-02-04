<?php

namespace Flesh404\Kaspa\Laravel\Address\Enums;

use Flesh404\Kaspa\Laravel\Address\Exceptions\InvalidKaspaAddress;

/**
 * Kaspa address prefix enum.
 *
 * Represents the Bech32 human-readable prefix (HRP) of a Kaspa address
 * and allows resolving the corresponding network.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Enums
 */
enum KaspaPrefix: string
{
    case Mainnet = 'kaspa';
    case Testnet = 'kaspatest';
    case Devnet  = 'kaspadev';
    case Simnet  = 'kaspasim';

    /**
     * Parses a Kaspa address prefix value.
     *
     * @throws InvalidKaspaAddress
     */
    public static function parse(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new InvalidKaspaAddress(
                "Unknown Kaspa address prefix: {$value}"
            );
    }

    /**
     * Returns the network associated with this prefix.
     *
     * @return KaspaNetwork
     */
    public function network(): KaspaNetwork
    {
        return match ($this) {
            self::Mainnet => KaspaNetwork::Mainnet,
            self::Testnet => KaspaNetwork::Testnet,
            self::Devnet  => KaspaNetwork::Devnet,
            self::Simnet  => KaspaNetwork::Simnet,
        };
    }
}