<?php

namespace Tests\Unit\Enums;

use App\DietEnum;
use Tests\TestCase;

class DietEnumTest extends TestCase
{
    public function test_it_has_the_expected_cases(): void
    {
        $this->assertSame(['vegan', 'vegetarian', 'classic'], array_column(DietEnum::cases(), 'value'));
    }

    public function test_it_returns_a_label_for_each_case(): void
    {
        foreach (DietEnum::cases() as $case) {
            $this->assertIsString($case->getLabel());
            $this->assertNotSame('', $case->getLabel());
        }
    }

    public function test_it_returns_an_icon_for_each_case(): void
    {
        $this->assertSame('tabler-leaf', DietEnum::VEGAN->getIcon());
        $this->assertSame('tabler-salad', DietEnum::VEGETARIAN->getIcon());
        $this->assertSame('tabler-meat', DietEnum::CLASSIC->getIcon());
    }
}
