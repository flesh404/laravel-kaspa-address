<?php

namespace Flesh404\Kaspa\Laravel\Address\Enums;

/**
 * Enum KaspaNetwork.
 *
 * Represents a Kaspa network derived from the address Prefix.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Enums
 */
enum KaspaNetwork: string
{
    case Mainnet = 'mainnet';
    case Testnet = 'testnet';
    case Devnet  = 'devnet';
    case Simnet  = 'simnet';
}