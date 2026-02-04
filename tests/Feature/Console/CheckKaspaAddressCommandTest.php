<?php

use Orchestra\Testbench\TestCase;
use Flesh404\Kaspa\Laravel\Address\Providers\KaspaAddressServiceProvider;

/**
 * Feature tests for the Kaspa address Artisan command.
 */
final class CheckKaspaAddressCommandTest extends TestCase
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

    public function test_it_accepts_a_valid_kaspa_address(): void
    {
        $address = 'kaspa:qpsth4earzr3fgdwu0fs40lajjwkcmfgw9cff6zezps4pt8vvygvjzds3qdsx';

        $this->artisan('kaspa:address', [
            'address' => $address,
        ])
            ->expectsOutput('✔ Address is valid')
            ->expectsOutput('Prefix:   kaspa')
            ->expectsOutput('Network:  mainnet')
            ->assertExitCode(0);
    }

    public function test_it_rejects_an_invalid_kaspa_address(): void
    {
        $address = 'kaspa:not-a-real-address';

        $this->artisan('kaspa:address', [
            'address' => $address,
        ])
            ->expectsOutput('✘ Address is invalid')
            ->assertExitCode(1);
    }
}