<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Soandso\Synop\Fabrication\Validate;

class BaricTendencyDecoderTest extends TestCase
{
    private $baricTendencyDecoder;

    protected function setUp(): void
    {
        $this->baricTendencyDecoder = new BaricTendencyDecoder('52035');
    }

    protected function tearDown(): void
    {
        unset($this->baricTendencyDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals('5', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals('A', $result);
    }

    public function testSuccessGetCodeFigureCharacteristic()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureCharacteristic');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals('2', $result);
    }

    public function testSuccessIsStringGetCodeFigureCharacteristic()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureCharacteristic');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureCharacteristic()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureCharacteristic');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals('10', $result);
    }

    public function testSuccessGetCodeFigureChange()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureChange');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals('035', $result);
    }

    public function testSuccessIsStringGetCodeFigureChange()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureChange');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureChange()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureChange');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals('35', $result);
    }

    public function testSuccessGetCharacteristicChange()
    {
        $this->assertEquals(2, $this->baricTendencyDecoder->getCharacteristicChange());
    }

    public function testSuccessIsIntGetCharacteristicChange()
    {
        $this->assertIsInt($this->baricTendencyDecoder->getCharacteristicChange());
    }

    public function testErrorGetCharacteristicChange()
    {
        $this->assertNotEquals('', $this->baricTendencyDecoder->getCharacteristicChange());
    }

    public function testSuccessGetBaricTendency()
    {
        $this->assertEquals(3.5, $this->baricTendencyDecoder->getBaricTendency());
    }

    public function testSuccessIsFloatGetBaricTendency()
    {
        $this->assertIsFloat($this->baricTendencyDecoder->getBaricTendency());
    }

    public function testErrorGetBaricTendency()
    {
        $this->assertNotEquals('035', $this->baricTendencyDecoder->getBaricTendency());
    }

    public function testSuccessIsGroup()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->baricTendencyDecoder->isGroup($validator, '5appp'));
    }

    public function testErrorIsGroup()
    {
        $baricTendencyDecoder = new BaricTendencyDecoder('52035');
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertTrue($baricTendencyDecoder->isGroup($validator, '5appp'));
    }

    public function testSuccessGetGetIndicatorGroup()
    {
        $expected = ['5' => 'Indicator'];

        $this->assertEquals($expected, $this->baricTendencyDecoder->getGetIndicatorGroup());
    }

    public function testSuccessIsArrayGetGetIndicatorGroup()
    {
        $this->assertIsArray($this->baricTendencyDecoder->getGetIndicatorGroup());
    }

    public function testSuccessGetCharacteristicChangeIndicator()
    {
        $expected = ['a' => 'Characteristic of pressure change'];

        $this->assertEquals($expected, $this->baricTendencyDecoder->getCharacteristicChangeIndicator());
    }

    public function testSuccessIsArrayGetCharacteristicChangeIndicator()
    {
        $this->assertIsArray($this->baricTendencyDecoder->getCharacteristicChangeIndicator());
    }

    public function testSuccessGetPressureChangeIndicator()
    {
        $expected = ['ppp' => 'Pressure change over last three hours in millibars and tenths'];

        $this->assertEquals($expected, $this->baricTendencyDecoder->getPressureChangeIndicator());
    }

    public function testSuccessIsArrayGetPressureChangeIndicator()
    {
        $this->assertIsArray($this->baricTendencyDecoder->getPressureChangeIndicator());
    }
}
