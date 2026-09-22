<?php

namespace Tests\Unit\Enums;

use App\MealTypeEnum;
use Tests\TestCase;

class MealTypeEnumTest extends TestCase
{
    public function test_it_returns_an_icon_for_every_case(): void
    {
        foreach (MealTypeEnum::cases() as $case) {
            $this->assertIsString($case->getIcon());
            $this->assertNotSame('', $case->getIcon());
        }
    }

    public function test_drink_and_aperitive_share_the_same_icon(): void
    {
        $this->assertSame(MealTypeEnum::DRINK->getIcon(), MealTypeEnum::APERITIVE->getIcon());
    }
}
