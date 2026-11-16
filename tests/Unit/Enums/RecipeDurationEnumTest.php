<?php

namespace Tests\Unit\Enums;

use App\RecipeDurationEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RecipeDurationEnumTest extends TestCase
{
    public function test_it_returns_a_label_for_each_case(): void
    {
        foreach (RecipeDurationEnum::cases() as $case) {
            $this->assertIsString($case->getLabel());
            $this->assertNotSame('', $case->getLabel());
        }
    }

    public function test_from_minutes_returns_null_when_minutes_are_unknown(): void
    {
        $this->assertNull(RecipeDurationEnum::fromMinutes(null));
    }

    #[DataProvider('bucketBoundariesProvider')]
    public function test_from_minutes_buckets_correctly(int $minutes, RecipeDurationEnum $expected): void
    {
        $this->assertSame($expected, RecipeDurationEnum::fromMinutes($minutes));
    }

    public static function bucketBoundariesProvider(): array
    {
        return [
            [0, RecipeDurationEnum::UP_TO_30_MIN],
            [30, RecipeDurationEnum::UP_TO_30_MIN],
            [31, RecipeDurationEnum::UP_TO_1_HOUR],
            [60, RecipeDurationEnum::UP_TO_1_HOUR],
            [61, RecipeDurationEnum::UP_TO_2_HOURS],
            [120, RecipeDurationEnum::UP_TO_2_HOURS],
            [121, RecipeDurationEnum::OVER_2_HOURS],
            [500, RecipeDurationEnum::OVER_2_HOURS],
        ];
    }
}
