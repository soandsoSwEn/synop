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
        $this->assertEquals($this->airTemperatureDecoder->getCodeFigureDistNumber(), '1');
    }

    public function testErrorGetCodeFigureDistNumber()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('21007');
        $this->assertNotEquals($airTemperatureDecoder->getCodeFigureDistNumber(), '1');
    }

    public function testSuccessGetCodeFigureSignTemperature()
    {
        $this->assertEquals($this->airTemperatureDecoder->getCodeFigureSignTemperature(), '0');
    }

    public function testErrorGetCodeFigureSignTemperature()
    {
        $this->assertNotEquals($this->airTemperatureDecoder->getCodeFigureSignTemperature(), '1');
    }

    public function testSuccessGetCodeFigureTemperature()
    {
        $this->assertEquals($this->airTemperatureDecoder->getCodeFigureTemperature(), '039');
    }

    public function testErrorGetCodeFigureTemperature()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('21007');
        $this->assertNotEquals($airTemperatureDecoder->getCodeFigureTemperature(), '039');
    }

    public function testSuccessGetSignTemperature()
    {
        $this->assertEquals($this->airTemperatureDecoder->getSignTemperature(), 0);
    }

    public function testSuccessIntegerGetSignTemperature()
    {
        $this->assertIsInt(0, $this->airTemperatureDecoder->getSignTemperature());
    }

    public function testErrorGetSignTemperature()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('11039');
        $this->assertNotEquals($airTemperatureDecoder->getSignTemperature(), 0);
    }

    public function testSuccessGetTemperatureValue()
    {
        $this->assertEquals($this->airTemperatureDecoder->getTemperatureValue(), 3.9);
    }

    public function testFloatGetTemperatureValue()
    {
        $this->assertIsFloat($this->airTemperatureDecoder->getTemperatureValue(), 3.9);
    }

    public function testErrorGetTemperatureValue()
    {
        $airTemperatureDecoder = new AirTemperatureDecoder('10038');
        $this->assertNotEquals($airTemperatureDecoder->getTemperatureValue(), 3.9);
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