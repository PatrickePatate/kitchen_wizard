<?php

namespace Tests\Unit\Enums;

use App\MealDifficultyEnum;
use Tests\TestCase;

class MealDifficultyEnumTest extends TestCase
{
    public function test_it_returns_a_translated_label_for_every_case(): void
    {
        foreach (MealDifficultyEnum::cases() as $case) {
            $this->assertIsString($case->getLabel());
            $this->assertNotSame('', $case->getLabel());
        }
    }
}
