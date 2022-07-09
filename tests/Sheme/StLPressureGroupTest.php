<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\StLPressureGroup;

class StLPressureGroupTest extends TestCase
{
    private $stLPressureGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $unit = Mockery::mock(Unit::class);

        $this->stLPressureGroup = new StLPressureGroup('30049', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->stLPressureGroup);
        Mockery::close();
    }

    public function testSuccessSetStLPressure()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('getStLPressure')->once()->andReturn(1004.9);

        $this->stLPressureGroup->setStLPressure($decoder);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertEquals(1004.9, $value);
    }

    public function testNullSetStLPressure()
    {
        $this->stLPressureGroup->setStLPressure(null);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertNull($value);
    }

    public function testIsFloatSetStLPressure()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('getStLPressure')->once()->andReturn(1004.9);

        $this->stLPressureGroup->setStLPressure($decoder);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertIsFloat($value);
    }

    public function testErrorSetStLPressure()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('getStLPressure')->once()->andReturn(998.5);

        $this->stLPressureGroup->setStLPressure($decoder);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertNotEquals(1004.9, $value);
    }

    public function testSuccessIsStLPressureGroup()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->stLPressureGroup->isStLPressureGroup($decoder, $validate));
    }

    public function testErrorIsStLPressureGroup()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->stLPressureGroup->isStLPressureGroup($decoder, $validate));
    }

    public function testSuccessSetStLPressureGroup()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('getStLPressure')->once()->andReturn(1004.9);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validate = Mockery::mock(Validate::class);

        $this->stLPressureGroup->setStLPressureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertEquals(1004.9, $value);
    }

    public function testNullSetStLPressureGroup()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $decoder->shouldReceive('getStLPressure')->andReturn(1004.9);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->stLPressureGroup->setStLPressureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertNull($value);
    }

    public function testSuccessGetPressureValue()
    {
        $this->assertEquals(1004.9, $this->stLPressureGroup->getPressureValue());
    }

    public function testIsFloatGetPressureValue()
    {
        $this->assertIsFloat($this->stLPressureGroup->getPressureValue());
    }

    public function testErrorGetPressureValue()
    {
        $this->assertNotEquals(998.5, $this->stLPressureGroup->getPressureValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->stLPressureGroup->getDecoder());
    }

    public function testSuccessSetPressureValue()
    {
        $this->stLPressureGroup->setPressureValue(998.5);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertEquals(998.5, $value);
    }

    public function testSuccessIsFloatSetPressureValue()
    {
        $this->stLPressureGroup->setPressureValue(998.5);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertIsFloat($value);
    }

    public function testErrorSetPressureValue()
    {
        $this->stLPressureGroup->setPressureValue(998.5);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertNotEquals(1004.9, $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(StLPressureDecoder::class);
        $this->stLPressureGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->stLPressureGroup);

        $this->assertInstanceOf(StLPressureDecoder::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->stLPressureGroup->setData('39985', $validate);

        $reflector = new \ReflectionClass(StLPressureGroup::class);
        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->stLPressureGroup);

        $propertyPressure = $reflector->getProperty('pressure');
        $propertyPressure->setAccessible(true);
        $valuePressure = $propertyPressure->getValue($this->stLPressureGroup);

        $this->assertEquals([true, 998.5], [$valueDecoder instanceof StLPressureDecoder, $valuePressure]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->stLPressureGroup->setData('', $validate);
    }
}
