# laravel-kaspa-address

Laravel package for validating and analyzing Kaspa addresses.

## Features

- Validate Kaspa addresses (mainnet / testnet / devnet / simnet)
- Analyze address prefix & network
- Based on official Kaspa Go / Rust implementations
- Zero dependencies, framework-agnostic core

## Installation

```bash
composer require flesh404/laravel-kaspa-address
```

## Usage

### Validate address
```php
KaspaAddress::isValid($address);
```

### Parse address
```php
$address = KaspaAddress::parse($address);

$address->prefix();   // kaspa
$address->network();  // KaspaNetwork::Mainnet
```

### Analyze (for APIs / checker sites)
```php
AddressAnalyzer::analyze($input);
```

