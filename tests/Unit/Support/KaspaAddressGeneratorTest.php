<?php

namespace Flesh404\Kaspa\Laravel\Address\Tests\Unit\Support;

use Flesh404\Kaspa\Laravel\Address\KaspaAddress;
use Flesh404\Kaspa\Laravel\Address\{
    Enums\KaspaPrefix,
    Support\KaspaAddressGenerator
};
use Flesh404\Kaspa\Laravel\Address\Tests\TestCase;

/**
 * Tests for the KaspaAddressGenerator.
 *
 * Ensures that generated addresses are valid, unique,
 * and use the correct Kaspa prefix.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Tests\Unit\Support
 */
final class KaspaAddressGeneratorTest extends TestCase
{
    public function test_it_generates_a_valid_kaspa_address(): void
    {
        $address = KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);

        $this->assertInstanceOf(KaspaAddress::class, $address);
        $this->assertSame(KaspaPrefix::Mainnet, $address->prefix());
    }

    public function test_it_generates_addresses_for_different_networks(): void
    {
        $mainnet = KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);
        $testnet = KaspaAddressGenerator::generate(KaspaPrefix::Testnet);

        $this->assertSame(KaspaPrefix::Mainnet, $mainnet->prefix());
        $this->assertSame(KaspaPrefix::Testnet, $testnet->prefix());
    }

    public function test_generated_addresses_are_unique(): void
    {
        $a = (string) KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);
        $b = (string) KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);

        $this->assertNotSame($a, $b);
    }

    public function test_it_generates_a_valid_testnet_address(): void
    {
        $address = KaspaAddressGenerator::generate(
            KaspaPrefix::Testnet
        );

        $this->assertTrue(
            KaspaAddress::isValid((string) $address)
        );
        $this->assertSame(
            KaspaPrefix::Testnet,
            $address->prefix()
        );
    }
}
