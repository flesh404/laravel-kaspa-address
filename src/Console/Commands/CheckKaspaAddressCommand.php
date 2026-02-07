<?php

namespace Flesh404\Kaspa\Laravel\Address\Console\Commands;

use Illuminate\Console\Command;
use Flesh404\Kaspa\Laravel\Address\Support\KaspaAddressAnalyzer;

/**
 * Artisan command for validating and inspecting Kaspa addresses.
 *
 * Displays validation status, prefix, and network information.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Console\Commands
 */
final class CheckKaspaAddressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kaspa:address {address : Kaspa address to validate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate and analyze a Kaspa address';

    /**
     * Executes the Kaspa address validation command.
     *
     * @return int Command exit code
     */
    public function handle(): int
    {
        $input = $this->argument('address');

        $result = KaspaAddressAnalyzer::analyze($input);

        if (! $result['valid']) {
            $this->error('✘ Address is invalid');

            foreach ($result['errors'] as $error) {
                $this->line(" - {$error['message']} ({$error['code']})");
            }

            return self::FAILURE;
        }

        $this->info('✔ Address is valid');
        $this->newLine();

        $this->line('<info>Prefix:</info>   ' . $result['prefix']);
        $this->line('<info>Network:</info>  ' . $result['network']);

        return self::SUCCESS;
    }
}