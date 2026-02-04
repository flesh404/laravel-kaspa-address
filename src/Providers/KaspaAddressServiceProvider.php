<?php

namespace Flesh404\Kaspa\Laravel\Address\Providers;

use Flesh404\Kaspa\Laravel\Address\Console\Commands\CheckKaspaAddressCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Kaspa Address package.
 *
 * Registers console commands when running in CLI context.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Providers
 */
final class KaspaAddressServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     *
     * No bindings are required as the package consists of pure value objects.
     */
    public function register(): void
    {
        // nothing to bind (pure value objects)
    }

    /**
     * Bootstrap package services.
     *
     * Registers Artisan commands when running in the console.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckKaspaAddressCommand::class,
            ]);
        }
    }
}