<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\AirTemperatureGroup;

class AirTemperatureGroupTest extends TestCase
{
    private $airTemperatureGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->airTemperatureGroup = new AirTemperatureGroup('10039', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->airTemperatureGroup);
        Mockery::close();
    }

    public function testSuccessBuildAirTemperature()
    {
        $this->airTemperatureGroup->buildAirTemperature(0, 15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(15.8, $value);
    }

    public function testSuccessNegativeBuildAirTemperature()
    {
        $this->airTemperatureGroup->buildAirTemperature(1, 15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(-15.8, $value);
    }

    public function testNullSignBuildAirTemperature()
    {
        $this->airTemperatureGroup->buildAirTemperature(null, 15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertNull($value);
    }

    public function testNullAirTempBuildAirTemperature()
    {
        $this->airTemperatureGroup->buildAirTemperature(1, null);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertNull($value);
    }

    public function testNullBuildAirTemperature()
    {
        $this->airTemperatureGroup->buildAirTemperature(null, null);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetTemperature()
    {
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);

        $this->airTemperatureGroup->setTemperature($decoder);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(3.9, $value);
    }

    public function testSuccessIsFloatSetTemperature()
    {
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);

        $this->airTemperatureGroup->setTemperature($decoder);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testNullSetTemperature()
    {
        $this->airTemperatureGroup->setTemperature(null);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetSign()
    {
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('getSignTemperature')->once()->andReturn(1);

        $this->airTemperatureGroup->setSign($decoder);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(1, $value);
    }

    public function testNullSetSign()
    {
        $this->airTemperatureGroup->setSign(null);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsAirTempGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $this->assertTrue($this->airTemperatureGroup->isAirTempGroup($decoder, $validate));
    }

    public function testErrorIsAirTempGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $this->assertFalse($this->airTemperatureGroup->isAirTempGroup($decoder, $validate));
    }

    public function testSuccessSetAirTempGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getSignTemperature')->once()->andReturn(1);
        $decoder->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);

        $this->airTemperatureGroup->setAirTempGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->airTemperatureGroup);

        $propertyTemperature = $reflector->getProperty('temperature');
        $propertyTemperature->setAccessible(true);
        $valueTemperature = $propertyTemperature->getValue($this->airTemperatureGroup);

        $propertyTemperatureValue = $reflector->getProperty('temperatureValue');
        $propertyTemperatureValue->setAccessible(true);
        $valueTemperatureValue = $propertyTemperatureValue->getValue($this->airTemperatureGroup);

        $this->assertEquals([1, 3.9, -3.9], [$valueSign, $valueTemperature, $valueTemperatureValue]);
    }

    public function testNullSetAirTempGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $this->airTemperatureGroup->setAirTempGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->airTemperatureGroup);

        $propertyTemperature = $reflector->getProperty('temperature');
        $propertyTemperature->setAccessible(true);
        $valueTemperature = $propertyTemperature->getValue($this->airTemperatureGroup);

        $propertyTemperatureValue = $reflector->getProperty('temperatureValue');
        $propertyTemperatureValue->setAccessible(true);
        $valueTemperatureValue = $propertyTemperatureValue->getValue($this->airTemperatureGroup);

        $this->assertEquals([null, null, null], [$valueSign, $valueTemperature, $valueTemperatureValue]);
    }

    public function testSuccessGetTemperatureValue()
    {
        $this->assertEquals(3.9, $this->airTemperatureGroup->getTemperatureValue());
    }

    public function testSuccessIsFloatGetTemperatureValue()
    {
        $this->assertIsFloat($this->airTemperatureGroup->getTemperatureValue());
    }

    public function testSuccessGetTemperatureData()
    {
        $this->assertEquals(3.9, $this->airTemperatureGroup->getTemperatureData());
    }

    public function testSuccessIsFloatGetTemperatureData()
    {
        $this->assertIsFloat($this->airTemperatureGroup->getTemperatureData());
    }

    public function testSuccessGetSignValue()
    {
        $this->assertEquals(0, $this->airTemperatureGroup->getSignValue());
    }

    public function testSuccessIsIntGetSignValue()
    {
        $this->assertIsInt($this->airTemperatureGroup->getSignValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->airTemperatureGroup->getDecoder());
    }

    public function testSuccessGetRawAirTemperature()
    {
        $this->assertEquals('10039', $this->airTemperatureGroup->getRawAirTemperature());
    }

    public function testSuccessIsStringGetRawAirTemperature()
    {
        $this->assertIsString($this->airTemperatureGroup->getRawAirTemperature());
    }

    public function testSuccessSetTemperatureValue()
    {
        $this->airTemperatureGroup->setTemperatureValue(-15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(-15.8, $value);
    }

    public function testSuccessIsFloatSetTemperatureValue()
    {
        $this->airTemperatureGroup->setTemperatureValue(-15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessSetTemperatureData()
    {
        $this->airTemperatureGroup->setTemperatureData(15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(15.8, $value);
    }

    public function testSuccessIsFloatSetTemperatureData()
    {
        $this->airTemperatureGroup->setTemperatureData(15.8);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('temperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessSetSignValue()
    {
        $this->airTemperatureGroup->setSignValue(1);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals(1, $value);
    }

    public function testSuccessIsIntSetSignValue()
    {
        $this->airTemperatureGroup->setSignValue(1);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertIsInt($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(AirTemperatureDecoder::class);
        $this->airTemperatureGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetRawAirTemperature()
    {
        $this->airTemperatureGroup->setRawAirTemperature('11058');

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $property = $reflector->getProperty('rawAirTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->airTemperatureGroup);

        $this->assertEquals('11058', $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->airTemperatureGroup->setData('11058', $validate);

        $reflector = new \ReflectionClass(AirTemperatureGroup::class);
        $propertyRawData = $reflector->getProperty('rawAirTemperature');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->airTemperatureGroup);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->airTemperatureGroup);

        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->airTemperatureGroup);

        $propertyTemperature = $reflector->getProperty('temperature');
        $propertyTemperature->setAccessible(true);
        $valueTemperature = $propertyTemperature->getValue($this->airTemperatureGroup);

        $propertyTemperatureValue = $reflector->getProperty('temperatureValue');
        $propertyTemperatureValue->setAccessible(true);
        $valueTemperatureValue = $propertyTemperatureValue->getValue($this->airTemperatureGroup);

        $expected = ['11058', true, 1, 5.8, -5.8];

        $this->assertEquals($expected, [$valueRawData, $valueDecoder instanceof GroupDecoderInterface, $valueSign, $valueTemperature, $valueTemperatureValue]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->airTemperatureGroup->setData('', $validate);
    }
}
