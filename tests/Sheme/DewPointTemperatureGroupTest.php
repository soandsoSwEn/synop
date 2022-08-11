<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\DewPointTemperatureGroup;

class DewPointTemperatureGroupTest extends TestCase
{
    private $dewPointTemperatureGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->dewPointTemperatureGroup = new DewPointTemperatureGroup('21007', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->dewPointTemperatureGroup);
        Mockery::close();
    }

    public function testSuccessBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(1, 8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(-8, $value);
    }

    public function testSuccessPositiveBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(0, 8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(8, $value);
    }

    public function testSuccessIsIntBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(1, 8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testNullBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(null, null);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNull($value);
    }

    public function testNullSignBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(null, 8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNull($value);
    }

    public function testNullDewPointBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(1, null);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNull($value);
    }

    public function testSErrorIsIntBuildDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->buildDewPointTemperature(1, 8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNotEquals(8, $value);
    }

    public function testSuccessSetDewPointTemperature()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('getDewPointTemperature')->once()->andReturn(8.5);

        $this->dewPointTemperatureGroup->setDewPointTemperature($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPoint');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(8.5, $value);
    }

    public function testNullSetDewPointTemperature()
    {
        $this->dewPointTemperatureGroup->setDewPointTemperature(null);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPoint');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNull($value);
    }

    public function testErrorSetDewPointTemperature()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('getDewPointTemperature')->once()->andReturn(8.5);

        $this->dewPointTemperatureGroup->setDewPointTemperature($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPoint');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNotEquals(0.5, $value);
    }

    public function testSuccessSetSign()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('getSignDewPointTemperature')->once()->andReturn(1);

        $this->dewPointTemperatureGroup->setSign($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(1, $value);
    }

    public function testSuccessIsIntSetSign()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('getSignDewPointTemperature')->once()->andReturn(1);

        $this->dewPointTemperatureGroup->setSign($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetSign()
    {
        $this->dewPointTemperatureGroup->setSign(null);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNull($value);
    }

    public function testErrorSetSign()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('getSignDewPointTemperature')->once()->andReturn(1);

        $this->dewPointTemperatureGroup->setSign($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertNotEquals(0, $value);
    }

    public function testSuccessIsDwPtTemperatureGroup()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->dewPointTemperatureGroup->isDwPtTemperatureGroup($decoder, $validate));
    }

    public function testErrorIsDwPtTemperatureGroup()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->dewPointTemperatureGroup->isDwPtTemperatureGroup($decoder, $validate));
    }

    public function testSuccessSetDwPtTemperatureGroup()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getSignDewPointTemperature')->once()->andReturn(1);
        $decoder->shouldReceive('getDewPointTemperature')->once()->andReturn(8.5);

        $validate = Mockery::mock(Validate::class);

        $this->dewPointTemperatureGroup->setDwPtTemperatureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->dewPointTemperatureGroup);

        $propertyDewPoint = $reflector->getProperty('dewPoint');
        $propertyDewPoint->setAccessible(true);
        $valueDewPoint = $propertyDewPoint->getValue($this->dewPointTemperatureGroup);

        $propertyDewPointValue = $reflector->getProperty('dewPointValue');
        $propertyDewPointValue->setAccessible(true);
        $valueDewPointValue = $propertyDewPointValue->getValue($this->dewPointTemperatureGroup);

        $actual = [1, 8.5, -8.5];

        $this->assertEquals([$valueSign, $valueDewPoint, $valueDewPointValue], $actual);
    }

    public function testNullSetDwPtTemperatureGroup()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->dewPointTemperatureGroup->setDwPtTemperatureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->dewPointTemperatureGroup);

        $propertyDewPoint = $reflector->getProperty('dewPoint');
        $propertyDewPoint->setAccessible(true);
        $valueDewPoint = $propertyDewPoint->getValue($this->dewPointTemperatureGroup);

        $propertyDewPointValue = $reflector->getProperty('dewPointValue');
        $propertyDewPointValue->setAccessible(true);
        $valueDewPointValue = $propertyDewPointValue->getValue($this->dewPointTemperatureGroup);

        $actual = [null, null, null];

        $this->assertEquals([$valueSign, $valueDewPoint, $valueDewPointValue], $actual);
    }

    public function testSuccessGetResultDewPointValue()
    {
        $this->assertEquals($this->dewPointTemperatureGroup->getResultDewPointValue(), -0.7);
    }

    public function testSuccessIsFloatGetResultDewPointValue()
    {
        $this->assertIsFloat($this->dewPointTemperatureGroup->getResultDewPointValue());
    }

    public function testErrorGetResultDewPointValue()
    {
        $this->assertNotEquals($this->dewPointTemperatureGroup->getResultDewPointValue(), 0.7);
    }

    public function testSuccessGetDewPointValue()
    {
        $this->assertEquals($this->dewPointTemperatureGroup->getDewPointValue(), 0.7);
    }

    public function testSuccessIsFloatGetDewPointValue()
    {
        $this->assertIsFloat($this->dewPointTemperatureGroup->getDewPointValue());
    }

    public function testSuccessGetSignValue()
    {
        $this->assertEquals($this->dewPointTemperatureGroup->getSignValue(), 1);
    }

    public function testSuccessIsIntGetSignValue()
    {
        $this->assertIsInt($this->dewPointTemperatureGroup->getSignValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->dewPointTemperatureGroup->getDecoder());
    }

    public function testSuccessSetResultDewPointValue()
    {
        $this->dewPointTemperatureGroup->setResultDewPointValue(-0.8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(-0.8, $value);
    }

    public function testSuccessIsFloatSetResultDewPointValue()
    {
        $this->dewPointTemperatureGroup->setResultDewPointValue(-0.8);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPointValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessSetDewPointValue()
    {
        $this->dewPointTemperatureGroup->setDewPointValue(0.7);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPoint');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(0.7, $value);
    }

    public function testSuccessIsFloatSetDewPointValue()
    {
        $this->dewPointTemperatureGroup->setDewPointValue(0.7);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('dewPoint');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessSetSignValue()
    {
        $this->dewPointTemperatureGroup->setSignValue(1);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertEquals(1, $value);
    }

    public function testSuccessIsIntSetSignValue()
    {
        $this->dewPointTemperatureGroup->setSignValue(1);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertIsInt($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $this->dewPointTemperatureGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->dewPointTemperatureGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->dewPointTemperatureGroup->setData('20085', $validate);

        $reflector = new \ReflectionClass(DewPointTemperatureGroup::class);
        $propertyDec = $reflector->getProperty('decoder');
        $propertyDec->setAccessible(true);
        $valueDec = $propertyDec->getValue($this->dewPointTemperatureGroup);

        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->dewPointTemperatureGroup);

        $propertyDewPoint = $reflector->getProperty('dewPoint');
        $propertyDewPoint->setAccessible(true);
        $valueDewPoint = $propertyDewPoint->getValue($this->dewPointTemperatureGroup);

        $propertyDewPointValue = $reflector->getProperty('dewPointValue');
        $propertyDewPointValue->setAccessible(true);
        $valueDewPointValue = $propertyDewPointValue->getValue($this->dewPointTemperatureGroup);

        $expected = [true, 0, 8.5, 8.5];

        $this->assertEquals($expected, [$valueDec instanceof GroupDecoderInterface, $valueSign, $valueDewPoint, $valueDewPointValue]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->dewPointTemperatureGroup->setData('', $validate);
    }
}
