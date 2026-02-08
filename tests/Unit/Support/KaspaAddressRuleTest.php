<?php

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use Flesh404\Kaspa\Laravel\Address\Support\{
    KaspaAddressGenerator,
    Rules\KaspaAddressRule
};
use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;
use Flesh404\Kaspa\Laravel\Address\Providers\KaspaAddressServiceProvider;

/**
 * Unit tests for the KaspaAddressRule.
 */
final class KaspaAddressRuleTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            KaspaAddressServiceProvider::class,
        ];
    }

    public function test_it_accepts_a_valid_kaspa_address(): void
    {
        $address = (string) KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);

        $validator = Validator::make(
            ['address' => $address],
            ['address' => [new KaspaAddressRule()]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_it_rejects_an_invalid_address(): void
    {
        $validator = Validator::make(
            ['address' => 'kaspa:not-a-real-address'],
            ['address' => [new KaspaAddressRule()]]
        );

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('address', $validator->errors()->toArray());
    }

    public function test_it_rejects_non_string_values(): void
    {
        $validator = Validator::make(
            ['address' => 12345],
            ['address' => [new KaspaAddressRule()]]
        );

        $this->assertFalse($validator->passes());
    }

    public function test_it_allows_only_mainnet_when_restricted(): void
    {
        $mainnet = (string) KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);
        $testnet = (string) KaspaAddressGenerator::generate(KaspaPrefix::Testnet);

        $rule = new KaspaAddressRule(KaspaPrefix::Mainnet);

        $validatorMainnet = Validator::make(
            ['address' => $mainnet],
            ['address' => [$rule]]
        );

        $validatorTestnet = Validator::make(
            ['address' => $testnet],
            ['address' => [$rule]]
        );

        $this->assertTrue($validatorMainnet->passes());
        $this->assertFalse($validatorTestnet->passes());
    }

    public function test_it_allows_multiple_networks_when_configured(): void
    {
        $mainnet = (string) KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);
        $testnet = (string) KaspaAddressGenerator::generate(KaspaPrefix::Testnet);

        $rule = new KaspaAddressRule([
            KaspaPrefix::Mainnet,
            KaspaPrefix::Testnet,
        ]);

        $validator = Validator::make(
            ['address' => $mainnet],
            ['address' => [$rule]]
        );

        $this->assertTrue($validator->passes());

        $validator = Validator::make(
            ['address' => $testnet],
            ['address' => [$rule]]
        );

        $this->assertTrue($validator->passes());
    }
}