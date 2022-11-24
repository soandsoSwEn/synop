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
        $this->assertEquals('2', $this->dewPointTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testSuccessIsStringGetCodeFigureDistNumber()
    {
        $this->assertIsString($this->dewPointTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testErrorGetCodeFigureDistNumber()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals('2', $dewPointTemperatureDecoder->getCodeFigureDistNumber());
    }

    public function testSuccessGetCodeFigureSignDwPTemperature()
    {
        $this->assertEquals('1', $this->dewPointTemperatureDecoder->getCodeFigureSignDwPTemperature());
    }

    public function testSuccessIsStringGetCodeFigureSignDwPTemperature()
    {
        $this->assertIsString($this->dewPointTemperatureDecoder->getCodeFigureSignDwPTemperature());
    }

    public function testErrorGetCodeFigureSignDwPTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals('1', $dewPointTemperatureDecoder->getCodeFigureSignDwPTemperature());
    }

    public function testSuccessGetCodeFigureDwPTemperature()
    {
        $this->assertEquals('007', $this->dewPointTemperatureDecoder->getCodeFigureDwPTemperature());
    }

    public function testSuccessIsStringGetCodeFigureDwPTemperature()
    {
        $this->assertIsString($this->dewPointTemperatureDecoder->getCodeFigureDwPTemperature());
    }

    public function testErrorGetCodeFigureDwPTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals('007', $dewPointTemperatureDecoder->getCodeFigureDwPTemperature());
    }

    public function testSuccessGetDewPointTemperature()
    {
        $this->assertEquals(0.7, $this->dewPointTemperatureDecoder->getDewPointTemperature());
    }

    public function testSuccessIsStringGetDewPointTemperature()
    {
        $this->assertIsFloat($this->dewPointTemperatureDecoder->getDewPointTemperature());
    }

    public function testErrorGetDewPointTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals(0.7, $dewPointTemperatureDecoder->getDewPointTemperature());
    }

    public function testSuccessGetSignDewPointTemperature()
    {
        $this->assertEquals(1, $this->dewPointTemperatureDecoder->getSignDewPointTemperature());
    }

    public function testSuccessIsIntGetSignDewPointTemperature()
    {
        $this->assertIsInt($this->dewPointTemperatureDecoder->getSignDewPointTemperature());
    }

    public function testErrorGetSignDewPointTemperature()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $this->assertNotEquals('1', $dewPointTemperatureDecoder->getSignDewPointTemperature());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->dewPointTemperatureDecoder->isGroup($validate, '2SnTdTdTd'));
    }

    public function testErrorIsGroup()
    {
        $dewPointTemperatureDecoder = new DewPointTemperatureDecoder('10039');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($dewPointTemperatureDecoder->isGroup($validate, '2SnTdTdTd'));
    }

    public function testSuccessGetGetIndicatorGroup()
    {
        $expected = ['2' => 'Indicator'];

        $this->assertEquals($expected, $this->dewPointTemperatureDecoder->getGetIndicatorGroup());
    }

    public function testSuccessIsArrayGetGetIndicatorGroup()
    {
        $this->assertIsArray($this->dewPointTemperatureDecoder->getGetIndicatorGroup());
    }

    public function testSuccessGetSignTemperatureIndicator()
    {
        $expected = ['Sn' => 'Sign of temperature'];

        $this->assertEquals($expected, $this->dewPointTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessIsArrayGetSignTemperatureIndicator()
    {
        $this->assertIsArray($this->dewPointTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessGetDryBulbTemperatureIndicator()
    {
        $expected = ['TdTdTd' => 'Dew point temperature in tenths of a degree'];

        $this->assertEquals($expected, $this->dewPointTemperatureDecoder->getDryBulbTemperatureIndicator());
    }
}
