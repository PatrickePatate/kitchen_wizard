<?php

namespace Tests\Unit\Support;

use App\Support\DurationParser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DurationParserTest extends TestCase
{
    #[DataProvider('durationStringsProvider')]
    public function test_it_converts_duration_strings_to_minutes(?string $raw, ?int $expectedMinutes): void
    {
        $this->assertSame($expectedMinutes, DurationParser::toMinutes($raw));
    }

    public static function durationStringsProvider(): array
    {
        return [
            'null' => [null, null],
            'empty string' => ['', null],
            'dash (not specified)' => ['-', null],
            'minutes only' => ['30 min', 30],
            'hours only' => ['10 h', 600],
            'compact hours and minutes' => ['2h30', 150],
            'compact hours and minutes, padded' => ['10h05', 605],
            'minutes and seconds' => ['13 min 30 sec', 14],
            'hours, minutes and seconds' => ['1 h 10 min 10 sec', 70],
            'days only' => ['1 j', 1440],
            'days and hours' => ['1 j 15 h', 2340],
            'days, hours and minutes' => ['1 j 17 h 30 min', 2490],
            'days and minutes' => ['1 j 8 min', 1448],
            'days, minutes and seconds' => ['1 j 29 min 59 sec', 1470],
            'seconds only' => ['20 sec', 0],
        ];
    }
}
