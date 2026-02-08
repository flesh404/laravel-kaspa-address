<?php

namespace Flesh404\Kaspa\Laravel\Address\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Flesh404\Kaspa\Laravel\Address\{
    KaspaAddress,
    Enums\KaspaPrefix
};

/**
 * Laravel validation rule for Kaspa addresses.
 *
 * Validates that a given value is a syntactically and semantically
 * correct Kaspa address and optionally restricts the allowed
 * Kaspa networks (prefixes).
 *
 * By default, all supported Kaspa prefixes are accepted.
 *
 * @package Flesh404\Kaspa\Laravel\Address\Support\Rules
 */
final class KaspaAddressRule implements ValidationRule
{
    /**
     * List of allowed Kaspa prefixes.
     *
     * @var KaspaPrefix[]
     */
    private array $allowedPrefixes;

    /**
     * Creates a new KaspaAddress validation rule.
     *
     * If no prefixes are provided, all known Kaspa prefixes
     * (mainnet, testnet, devnet, simnet) are allowed.
     *
     * @param KaspaPrefix|KaspaPrefix[]|null $allowedPrefixes
     */
    public function __construct(KaspaPrefix|array|null $allowedPrefixes = null)
    {
        // Default: allow all Kaspa networks
        $this->allowedPrefixes = $allowedPrefixes === null
            ? KaspaPrefix::cases()
            : ($allowedPrefixes instanceof KaspaPrefix
                ? [$allowedPrefixes]
                : $allowedPrefixes);
    }

    /**
     * Validate the given attribute.
     *
     * Ensures that the value:
     * - is a string
     * - represents a valid Kaspa address
     * - matches one of the allowed Kaspa prefixes (if restricted)
     *
     * @param string  $attribute Attribute name
     * @param mixed   $value     Value being validated
     * @param Closure $fail      Callback to report validation failure
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a valid Kaspa address.');
            return;
        }

        try {
            $address = KaspaAddress::parse($value);
        } catch (\Throwable) {
            $fail('The :attribute must be a valid Kaspa address.');
            return;
        }

        if (! in_array($address->prefix(), $this->allowedPrefixes, true)) {
            $allowed = implode(
                ', ',
                array_map(
                    static fn (KaspaPrefix $p) => $p->value,
                    $this->allowedPrefixes
                )
            );

            $fail("The :attribute must be a Kaspa address for one of the following networks: {$allowed}.");
        }
    }
}
