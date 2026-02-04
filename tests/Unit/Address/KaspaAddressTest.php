<?php

use Flesh404\Kaspa\Laravel\Address\Address\KaspaAddress;
use Flesh404\Kaspa\Laravel\Address\Enums\KaspaNetwork;
use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Exceptions\InvalidKaspaAddress;
use Orchestra\Testbench\TestCase;

/**
 * Unit tests for the KaspaAddress value object.
 */
final class KaspaAddressTest extends TestCase
{
    public function test_valid_address_parses(): void
    {
        $address = KaspaAddress::parse(
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e'
        );

        $this->assertSame(KaspaPrefix::Mainnet, $address->prefix());
        $this->assertSame(KaspaNetwork::Mainnet, $address->network());
    }

    public function test_invalid_address_throws(): void
    {
        $this->expectException(InvalidKaspaAddress::class);

        KaspaAddress::parse('kaspa:invalidaddress');
    }

    public function isValid_works(): void
    {
        $this->assertTrue(
            KaspaAddress::isValid(
                'kaspatest:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqhqrxplya'
            )
        );

        $this->assertFalse(
            KaspaAddress::isValid('not-an-address')
        );
    }


    public function test_mixed_case_is_rejected(): void
    {
        $this->assertFalse(
            KaspaAddress::isValid('Kaspa:qqqq...')
        );
    }
}
