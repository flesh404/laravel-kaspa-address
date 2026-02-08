<?php

namespace Flesh404\Kaspa\Laravel\Address\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Flesh404\Kaspa\Laravel\Address\Providers\KaspaAddressServiceProvider;

/**
 * Base test case for all package tests.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Tests
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Register the package service provider for all tests.
     */
    protected function getPackageProviders($app): array
    {
        return [
            KaspaAddressServiceProvider::class,
        ];
    }
}