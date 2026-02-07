<?php

namespace Tests\Unit\Bech32;

use Flesh404\Kaspa\Laravel\Address\Bech32\KaspaBech32;
use Flesh404\Kaspa\Laravel\Address\Exceptions\Bech32\{
    InvalidBech32Length,
    MixedCaseBech32String,
    MissingBech32Separator,
    InvalidBech32SeparatorPosition,
    InvalidBech32Checksum,
    InvalidBech32Character
};
use Orchestra\Testbench\TestCase;

final class KaspaBech32ExceptionsTest extends TestCase
{
    public function test_it_throws_invalid_length_exception(): void
    {
        $this->expectException(InvalidBech32Length::class);

        KaspaBech32::decode('x');
    }

    public function test_it_throws_mixed_case_exception(): void
    {
        $this->expectException(MixedCaseBech32String::class);

        KaspaBech32::decode('Kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e');
    }

    public function test_it_throws_missing_separator_exception(): void
    {
        $this->expectException(MissingBech32Separator::class);

        KaspaBech32::decode('kaspaqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4e');
    }

    public function test_it_throws_invalid_separator_position_exception(): void
    {
        $this->expectException(InvalidBech32SeparatorPosition::class);

        // separator too close to end
        KaspaBech32::decode('kaspa:qqqq');
    }

    public function test_it_throws_invalid_character_exception(): void
    {
        $this->expectException(InvalidBech32Character::class);

        KaspaBech32::decode('kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4!');
    }

    public function test_it_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidBech32Checksum::class);

        // valid structure, but checksum intentionally corrupted
        KaspaBech32::decode(
            'kaspa:qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqkx9awp4x'
        );
    }
}
