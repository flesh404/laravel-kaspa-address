<?php

namespace Flesh404\Kaspa\Laravel\Address\Tests\Feature\Console;

use Flesh404\Kaspa\Laravel\Address\Tests\TestCase;

/**
 * Feature tests for the Kaspa address Artisan command.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Tests\Feature\Console
 */
final class CheckKaspaAddressCommandTest extends TestCase
{
    public function test_it_accepts_a_valid_kaspa_address(): void
    {
        $address = 'kaspa:qpsth4earzr3fgdwu0fs40lajjwkcmfgw9cff6zezps4pt8vvygvjzds3qdsx';

        $this->artisan('kaspa:address', compact('address'))
            ->expectsOutput('✔ Address is valid')
            ->expectsOutput('Prefix:   kaspa')
            ->expectsOutput('Network:  mainnet')
            ->assertExitCode(0);
    }

    public function test_it_rejects_an_invalid_kaspa_address(): void
    {
        $address = 'kaspa:not-a-real-address';

        $this->artisan('kaspa:address', compact('address'))
            ->expectsOutput('✘ Address is invalid')
            ->assertExitCode(1);
    }
}