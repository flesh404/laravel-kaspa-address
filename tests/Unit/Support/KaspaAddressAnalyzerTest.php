<?php

namespace Flesh404\Kaspa\Laravel\Address\Tests\Unit\Support;

use Flesh404\Kaspa\Laravel\Address\Support\KaspaAddressAnalyzer;
use Flesh404\Kaspa\Laravel\Address\Tests\TestCase;

/**
 * Unit tests for the KaspaAddressAnalyzer helper.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Tests\Unit\Support
 */
final class KaspaAddressAnalyzerTest extends TestCase
{
    public function test_analyze_valid_address(): void
    {
        $result = KaspaAddressAnalyzer::analyze(
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e'
        );

        $this->assertTrue($result['valid']);
        $this->assertSame('kaspa', $result['prefix']);
        $this->assertSame('mainnet', $result['network']);
        $this->assertEmpty($result['errors']);
    }

    public function test_analyze_invalid_address(): void
    {
        $result = KaspaAddressAnalyzer::analyze('foo');

        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }
}
