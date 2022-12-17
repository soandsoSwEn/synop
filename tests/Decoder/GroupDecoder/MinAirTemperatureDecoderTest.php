<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\MinAirTemperatureDecoder;
use Soandso\Synop\Fabrication\Validate;

class MinAirTemperatureDecoderTest extends TestCase
{
    private $minAirTemperatureDecoder;

    public function setUp(): void
    {
        $this->minAirTemperatureDecoder = new MinAirTemperatureDecoder('21065', true);
    }

    public function tearDown(): void
    {
        unset($this->minAirTemperatureDecoder);
        Mockery::close();
    }

    public function testSuccessGetIndicatorGroup()
    {
        $this->assertEquals(['2' => 'Indicator'], $this->minAirTemperatureDecoder->getIndicatorGroup());
    }

    public function testSuccessIsArrayGetIndicatorGroup()
    {
        $this->assertIsArray($this->minAirTemperatureDecoder->getIndicatorGroup());
    }

    public function testSuccessGetSignTemperatureIndicator()
    {
        $this->assertEquals(['Sn' => 'Sign of temperature'], $this->minAirTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessIsArrayGetSignTemperatureIndicator()
    {
        $this->assertIsArray($this->minAirTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessGetDryBulbTemperatureIndicator()
    {
        $this->assertEquals(
            ['TnTnTn' => 'Minimum temperature in tenths of a degree'],
            $this->minAirTemperatureDecoder->getDryBulbTemperatureIndicator()
        );
    }

    public function testSuccessIsArrayGetDryBulbTemperatureIndicator()
    {
        $this->assertIsArray($this->minAirTemperatureDecoder->getDryBulbTemperatureIndicator());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->minAirTemperatureDecoder->isGroup($validate, '2SnTnTnTn'));
    }

    public function testErrorIsGroup()
    {
        $minAirTemperatureGroup = new MinAirTemperatureDecoder('11011', true);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($minAirTemperatureGroup->isGroup($validate, '2SnTnTnTn'));
    }
}
