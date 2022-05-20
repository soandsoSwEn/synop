<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;
use Soandso\Synop\Fabrication\Validate;

class DewPointTemperatureDecoderTest extends TestCase
{
    private $dewPointTemperatureDecoder;

    protected function setUp(): void
    {
        $this->dewPointTemperatureDecoder = new DewPointTemperatureDecoder('21007');
    }

    protected function tearDown(): void
    {
        unset($this->dewPointTemperatureDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureDistNumber()
    {
        $this->assertEquals($this->dewPointTemperatureDecoder->getCodeFigureDistNumber(), '2');
    }

    public function testErrorGetCodeFigureDistNumber()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals($dewPointTemperatureDecoder->getCodeFigureDistNumber(), '2');
    }

    public function testSuccessGetCodeFigureSignDwPTemperature()
    {
        $this->assertEquals($this->dewPointTemperatureDecoder->getCodeFigureSignDwPTemperature(), '1');
    }

    public function testErrorGetCodeFigureSignDwPTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals($dewPointTemperatureDecoder->getCodeFigureSignDwPTemperature(), '1');
    }

    public function testSuccessGetCodeFigureDwPTemperature()
    {
        $this->assertEquals($this->dewPointTemperatureDecoder->getCodeFigureDwPTemperature(), '007');
    }

    public function testErrorGetCodeFigureDwPTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals($dewPointTemperatureDecoder->getCodeFigureDwPTemperature(), '007');
    }

    public function testSuccessGetDewPointTemperature()
    {
        $this->assertEquals($this->dewPointTemperatureDecoder->getDewPointTemperature(), 0.7);
    }

    public function testErrorGetDewPointTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals($dewPointTemperatureDecoder->getDewPointTemperature(), 0.7);
    }

    public function testSuccessGetSignDewPointTemperature()
    {
        $this->assertEquals($this->dewPointTemperatureDecoder->getSignDewPointTemperature(), '1');
    }

    public function testErrorGetSignDewPointTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals($dewPointTemperatureDecoder->getSignDewPointTemperature(), '1');
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->dewPointTemperatureDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($dewPointTemperatureDecoder->isGroup($validate));
    }
}