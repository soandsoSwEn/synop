<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\PresentWeatherGroup;

class PresentWeatherGroupTest extends TestCase
{
    private $presentWeatherGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);
        $this->presentWeatherGroup = new PresentWeatherGroup('70282', $validate);
    }

    protected function tearDown(): void
    {
        unset($this->presentWeatherGroup);
        Mockery::close();
    }

    public function testSuccessSetPastWeather()
    {
        $expected = [
            'W1' => 'Shower(s)',
            'W2' => 'Cloud covering more than 1/2 of the sky during part of the appropriate period and covering 1/2 or less during part of the period',
        ];

        $decoder = Mockery::mock(PresentWeatherDecoder::class);
        $decoder->shouldReceive('getPastWeather')->once()->andReturn($expected);

        $this->presentWeatherGroup->setPastWeather($decoder);

        $reflector = new \ReflectionClass(PresentWeatherGroup::class);
        $property = $reflector->getProperty('pastWeather');
        $property->setAccessible(true);
        $value = $property->getValue($this->presentWeatherGroup);

        $this->assertEquals($expected, $value);
    }
}