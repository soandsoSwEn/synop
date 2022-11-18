<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\MslPressureGroup;

class MslPressureGroupTest extends TestCase
{
    private $mslPressureGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->mslPressureGroup = new MslPressureGroup('40101', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->mslPressureGroup);
        Mockery::close();
    }

    public function testSuccessSetMslPressure()
    {
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('getMslPressure')->once()->andReturn(1010.1);
        $this->mslPressureGroup->setMslPressure($decoder);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertEquals(1010.1, $value);
    }

    public function testSuccessIsFloatSetMslPressure()
    {
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('getMslPressure')->once()->andReturn(1010.1);
        $this->mslPressureGroup->setMslPressure($decoder);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessNullSetMslPressure()
    {
        $this->mslPressureGroup->setMslPressure(null);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsMslPressureGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('isGroup')->andReturn(true);

        $this->assertTrue($this->mslPressureGroup->isMslPressureGroup($decoder, $validate));
    }

    public function testErrorIsMslPressureGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('isGroup')->andReturn(false);

        $this->assertFalse($this->mslPressureGroup->isMslPressureGroup($decoder, $validate));
    }

    public function testSuccessSetMslPressureGroup()
    {
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('getMslPressure')->once()->andReturn(1010.1);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->mslPressureGroup->setMslPressureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertEquals(1010.1, $value);
    }

    public function testErrorSetMslPressureGroup()
    {
        $decoder = Mockery::mock(MslPressureDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->mslPressureGroup->setMslPressureGroup($decoder, $validate);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertNull($value);
    }

    public function testSuccessGetPressureValue()
    {
        $this->assertEquals($this->mslPressureGroup->getPressureValue(), 1010.1);
    }

    public function testSuccessIsFloatGetPressureValue()
    {
        $this->assertIsFloat($this->mslPressureGroup->getPressureValue());
    }

    public function testErrorGetPressureValue()
    {
        $this->assertNotEquals(995.5, $this->mslPressureGroup->getPressureValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->mslPressureGroup->getDecoder());
    }

    public function testSuccessSetPressureValue()
    {
        $this->mslPressureGroup->setPressureValue(995.2);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertEquals(995.2, $value);
    }

    public function testErrorSetPressureValue()
    {
        $this->mslPressureGroup->setPressureValue(995.2);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('pressure');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertNotEquals(1010.1, $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(MslPressureDecoder::class);

        $this->mslPressureGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->mslPressureGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->mslPressureGroup->setData('40105', $validate);

        $reflector = new \ReflectionClass(MslPressureGroup::class);
        $propertyRawData = $reflector->getProperty('rawMlsPressure');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->mslPressureGroup);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->mslPressureGroup);

        $propertyPressure = $reflector->getProperty('pressure');
        $propertyPressure->setAccessible(true);
        $valuePressure = $propertyPressure->getValue($this->mslPressureGroup);

        $this->assertEquals(['40105', true, 1010.5], [$valueRawData, $valueDecoder instanceof MslPressureDecoder,$valuePressure]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->mslPressureGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('4PPPP', $this->mslPressureGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->mslPressureGroup->getGroupIndicator());
    }
}
