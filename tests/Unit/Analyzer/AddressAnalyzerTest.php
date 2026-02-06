<?php

use Flesh404\Kaspa\Laravel\Address\Analyzer\AddressAnalyzer;
use Orchestra\Testbench\TestCase;

/**
 * Unit tests for the AddressAnalyzer helper.
 */
final class AddressAnalyzerTest extends TestCase
{
    public function test_analyze_valid_address(): void
    {
        $result = AddressAnalyzer::analyze(
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e'
        );

        $this->assertTrue($result['valid']);
        $this->assertSame('kaspa', $result['prefix']);
        $this->assertSame('mainnet', $result['network']);
        $this->assertEmpty($result['errors']);
    }

    public function test_analyze_invalid_address(): void
    {
        $result = AddressAnalyzer::analyze('foo');

        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']['technical']);
    }
}
