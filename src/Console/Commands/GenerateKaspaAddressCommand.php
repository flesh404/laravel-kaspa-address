<?php

namespace Flesh404\Kaspa\Laravel\Address\Console\Commands;

use Illuminate\Console\Command;
use Flesh404\Kaspa\Laravel\Address\{
    Support\KaspaAddressGenerator,
    Enums\KaspaPrefix
};
use Flesh404\Kaspa\Laravel\Address\Exceptions\Address\UnknownKaspaAddressPrefix;

/**
 * Artisan command for generating Kaspa addresses.
 *
 * Generates a valid Bech32-encoded Kaspa address
 * for the given prefix (network).
 *
 * @package Flesh404\Kaspa\Laravel\Address\Console\Commands
 */
final class GenerateKaspaAddressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kaspa:address:generate {prefix=kaspa : Kaspa address prefix (kaspa, kaspatest, kaspadev, kaspasim)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a valid Kaspa address';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $prefixInput = strtolower((string) $this->argument('prefix'));

        try {
            $prefix = KaspaPrefix::parse($prefixInput);
        } catch (UnknownKaspaAddressPrefix) {
            $this->error("✘ Unknown Kaspa prefix: {$prefixInput}");
            $this->line('Supported prefixes: kaspa, kaspatest, kaspadev, kaspasim');

            return self::FAILURE;
        }

        $address = KaspaAddressGenerator::generate($prefix);

        $this->line((string) $address);

        return self::SUCCESS;
    }
}