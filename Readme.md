# laravel-kaspa-address

Laravel package for validating and analyzing Kaspa addresses.

**Disclaimer:**  
This project is not officially affiliated with, endorsed by, or connected to Kaspa or the Kaspa Foundation.

## Features

- Validate Kaspa addresses (mainnet / testnet / devnet / simnet)
- Extract prefix and network information
- Kaspa-specific Bech32 checksum verification
- Artisan command for CLI validation
- Based on the **official Kaspa Go / Rust reference implementations**
- Zero dependencies

## Installation

```bash
composer require flesh404/laravel-kaspa-address
```
Laravel will auto-discover the service provider.
## Usage

### Validate an address
```php
use Flesh404\Kaspa\Laravel\Address\KaspaAddress;

KaspaAddress::isValid('kaspa:...');
```
Returns `true` or `false`.

### Parse and inspect an address
```php
use Flesh404\Kaspa\Laravel\Address\KaspaAddress;

$address = KaspaAddress::parse('kaspa:qp...');

$address->prefix()->value;   // "kaspa"
$address->network()->value;  // "mainnet"
```
Throws `InvalidKaspaAddress` if invalid.

### Analyzer (recommended for APIs & checker websites)
The KaspaAddressAnalyzer never throws and always returns a structured result.
```php
use Flesh404\Kaspa\Laravel\Address\Support\KaspaAddressAnalyzer

$result = KaspaAddressAnalyzer::analyze('kaspa:qp...');
```
**Result format**
```php
[
    'valid'   => true,
    'prefix'  => 'kaspa',
    'network' => 'mainnet',
    'errors'  => [],
]
```
For invalid addresses:
```php
[
    'valid'  => false,
    'errors' => [
        [
            'code' => 'address.invalid',
            'message' => 'Invalid Kaspa address.'
        ],
        [
            'code' => 'bech32.checksum_invalid',
            'message' => 'Invalid Bech32 checksum.'
        ]
    ],
]
```

### Address Generator

The KaspaAddressGenerator provides a convenient way to generate
valid random Kaspa addresses for testing and development purposes.

```php
use Flesh404\Kaspa\Laravel\Address\Support\KaspaAddressGenerator;
use Flesh404\Kaspa\Laravel\Address\Enums\KaspaPrefix;

$address = KaspaAddressGenerator::generate(KaspaPrefix::Mainnet);

(string) $address;          // "kaspa:qp..."
$address->prefix()->value;  // "kaspa"
$address->network()->value; // "mainnet"
```
You can also generate addresses for other networks:
```php
KaspaAddressGenerator::generate(KaspaPrefix::Testnet);
KaspaAddressGenerator::generate(KaspaPrefix::Devnet);
KaspaAddressGenerator::generate(KaspaPrefix::Simnet);
```

**Note:**
The generator is intended for testing, fixtures, and tooling.
It does not derive keys or represent real wallet ownership.

### Artisan Command
The package ships with a CLI command for validating Kaspa addresses.

**Usage:**
```bash
php artisan kaspa:address kaspa:qp...
```

**Example Output:**
```bash
✔ Address is valid

Prefix:   kaspa
Network:  mainnet
```
For invalid addresses:
```bash
✘ Address is invalid
 - Invalid Kaspa address. (address.invalid)
 - Invalid Bech32 checksum. (bech32.checksum_invalid)
```

Exit codes:
- 0 → valid
- 1 → invalid

## Supported Networks & Prefixes
| Network | Prefix      |
| ------- | ----------- |
| Mainnet | `kaspa`     |
| Testnet | `kaspatest` |
| Devnet  | `kaspadev`  |
| Simnet  | `kaspasim`  |

## Testing
```bash
./vendor/bin/phpunit
```

Includes:
- Bech32 decoding tests 
- Address parsing tests 
- Analyzer tests 
- Artisan command tests

## References
- Kaspa Go implementation (kaspad)  
  https://github.com/kaspanet/kaspad
- Rusty-Kaspa (Rust reference implementation)  
  https://github.com/kaspanet/rusty-kaspa

