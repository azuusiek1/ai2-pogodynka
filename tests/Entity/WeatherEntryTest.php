<?php

namespace App\Tests\Entity;

use App\Entity\WeatherEntry;
use PHPUnit\Framework\TestCase;

class WeatherEntryTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            [0, 32],
            [-100, -148],
            [100, 212],
            [0.5, 32.9],
            [-40, -40],
            [37, 98.6],
            [20, 68],
            [-17.8, 0],
            [25.3, 77.54],
            [50, 122],
        ];
    }
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new WeatherEntry();

        $measurement->setTemperature($celsius);

        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit(), "{$celsius}°C should be {$expectedFahrenheit}°F");
    }
}
