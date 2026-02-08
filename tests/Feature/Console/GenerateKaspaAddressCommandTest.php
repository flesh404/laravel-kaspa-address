<?php

use Illuminate\Support\Facades\Artisan;
use Flesh404\Kaspa\Laravel\Address\{
    KaspaAddress,
    Providers\KaspaAddressServiceProvider
};
use Orchestra\Testbench\TestCase;

/**
 * Tests for the kaspa:address:generate Artisan command.
 */
final class GenerateKaspaAddressCommandTest extends TestCase
{
    /**
     * Register the package service provider for the test environment.
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            KaspaAddressServiceProvider::class,
        ];
    }

    public function test_it_generates_a_valid_mainnet_address(): void
    {
        $exitCode = Artisan::call('kaspa:address:generate');

        $this->assertSame(0, $exitCode);

        $output = trim(Artisan::output());

        $this->assertTrue(
            KaspaAddress::isValid($output),
            'Generated address should be valid'
        );
    }

    public function test_it_generates_a_valid_testnet_address(): void
    {
        $exitCode = Artisan::call('kaspa:address:generate', [
            'prefix' => 'kaspatest',
        ]);

        $this->assertSame(0, $exitCode);

        $output = trim(Artisan::output());

        $this->assertTrue(
            KaspaAddress::isValid($output),
            'Generated testnet address should be valid'
        );
    }

    public function test_it_fails_for_unknown_prefix(): void
    {
        $this->artisan('kaspa:address:generate foo')
            ->expectsOutputToContain('Unknown Kaspa prefix')
            ->assertExitCode(1);
    }
}