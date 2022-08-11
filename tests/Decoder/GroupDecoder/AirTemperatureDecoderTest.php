<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Fabrication\Validate;

class AirTemperatureDecoderTest extends TestCase
{
    private $airTemperatureDecoder;

    protected function setUp(): void
    {
        $this->airTemperatureDecoder = new AirTemperatureDecoder('10039');
    }

    protected function tearDown(): void
    {
        unset($this->airTemperatureDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureDistNumber()
    {
        $this->assertEquals('1', $this->airTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testSuccessIsStringGetCodeFigureDistNumber()
    {
        $this->assertIsString($this->airTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testErrorGetCodeFigureDistNumber()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('21007');
        $this->assertNotEquals('1', $airTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testSuccessGetCodeFigureSignTemperature()
    {
        $this->assertEquals('0', $this->airTemperatureDecoder->getCodeFigureSignTemperature());
    }

    public function testSuccessIsStringGetCodeFigureSignTemperature()
    {
        $this->assertIsString($this->airTemperatureDecoder->getCodeFigureSignTemperature());
    }

    public function testErrorGetCodeFigureSignTemperature()
    {
        $this->assertNotEquals('1', $this->airTemperatureDecoder->getCodeFigureSignTemperature());
    }

    public function testSuccessGetCodeFigureTemperature()
    {
        $this->assertEquals('039', $this->airTemperatureDecoder->getCodeFigureTemperature());
    }

    public function testSuccessIsStringGetCodeFigureTemperature()
    {
        $this->assertIsString($this->airTemperatureDecoder->getCodeFigureTemperature());
    }

    public function testErrorGetCodeFigureTemperature()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('21007');
        $this->assertNotEquals('039', $airTemperatureDecoder->getCodeFigureTemperature());
    }

    public function testSuccessGetSignTemperature()
    {
        $this->assertEquals(0, $this->airTemperatureDecoder->getSignTemperature());
    }

    public function testSuccessIntegerGetSignTemperature()
    {
        $this->assertIsInt($this->airTemperatureDecoder->getSignTemperature());
    }

    public function testErrorGetSignTemperature()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('11039');
        $this->assertNotEquals(0, $airTemperatureDecoder->getSignTemperature());
    }

    public function testSuccessGetTemperatureValue()
    {
        $this->assertEquals(3.9, $this->airTemperatureDecoder->getTemperatureValue());
    }

    public function testFloatGetTemperatureValue()
    {
        $this->assertIsFloat($this->airTemperatureDecoder->getTemperatureValue());
    }

    public function testErrorGetTemperatureValue()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('10038');
        $this->assertNotEquals(3.9, $airTemperatureDecoder->getTemperatureValue());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->airTemperatureDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('21007');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($airTemperatureDecoder->isGroup($validate));
    }
}
