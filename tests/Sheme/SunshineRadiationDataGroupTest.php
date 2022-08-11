<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\SunshineRadiationDataGroup;

class SunshineRadiationDataGroupTest extends TestCase
{
    private $sunshineRadiationData;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $unit = Mockery::mock(Unit::class);

        $this->sunshineRadiationData = new SunshineRadiationDataGroup('55118', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->sunshineRadiationData);
        Mockery::close();
    }

    public function testSuccessSetSunshine()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('getSunshineData')->once()->andReturn(11.8);

        $this->sunshineRadiationData->setSunshine($decoder);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $property = $reflector->getProperty('sunshine');
        $property->setAccessible(true);
        $value = $property->getValue($this->sunshineRadiationData);

        $this->assertEquals(11.8, $value);
    }

    public function testNullSetSunshine()
    {
        $this->sunshineRadiationData->setSunshine(null);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $property = $reflector->getProperty('sunshine');
        $property->setAccessible(true);
        $value = $property->getValue($this->sunshineRadiationData);

        $this->assertNull($value);
    }

    public function testSuccessSetCodeSunshine()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('getCodeSunshineData')->once()->andReturn('118');

        $this->sunshineRadiationData->setCodeSunshine($decoder);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $property = $reflector->getProperty('codeSunshine');
        $property->setAccessible(true);
        $value = $property->getValue($this->sunshineRadiationData);

        $this->assertEquals('118', $value);
    }

    public function testNullSetCodeSunshine()
    {
        $this->sunshineRadiationData->setCodeSunshine(null);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $property = $reflector->getProperty('codeSunshine');
        $property->setAccessible(true);
        $value = $property->getValue($this->sunshineRadiationData);

        $this->assertNull($value);
    }

    public function testSuccessIsSunshineRadiationGroup()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validator = Mockery::mock(Validate::class);

        $this->assertTrue($this->sunshineRadiationData->isSunshineRadiationGroup($decoder, $validator));
    }

    public function testErrorIsSunshineRadiationGroup()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validator = Mockery::mock(Validate::class);

        $this->assertFalse($this->sunshineRadiationData->isSunshineRadiationGroup($decoder, $validator));
    }

    public function testSuccessSetSunshineRadiationGroup()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCodeSunshineData')->once()->andReturn('118');
        $decoder->shouldReceive('getSunshineData')->once()->andReturn(11.8);

        $validator = Mockery::mock(Validate::class);

        $this->sunshineRadiationData->setSunshineRadiationGroup($decoder, $validator);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $codeSunshineValue = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertEquals(['118', 11.8], [$codeSunshineValue, $valueSunshine]);
    }

    public function testNullSetSunshineRadiationGroup()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validator = Mockery::mock(Validate::class);

        $this->sunshineRadiationData->setSunshineRadiationGroup($decoder, $validator);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $codeSunshineValue = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertEquals([null, null], [$codeSunshineValue, $valueSunshine]);
    }

    public function testSuccessGetSunshineValue()
    {
        $this->assertEquals(11.8, $this->sunshineRadiationData->getSunshineValue());
    }

    public function testNullGetSunshineValue()
    {
        $reflectionProperty = new \ReflectionProperty(SunshineRadiationDataGroup::class, 'sunshine');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->sunshineRadiationData, null);

        $this->assertNull($this->sunshineRadiationData->getSunshineValue());
    }

    public function testSuccessGetCodeSunshineValue()
    {
        $this->assertEquals('118', $this->sunshineRadiationData->getCodeSunshineValue());
    }

    public function testSuccessStringGetCodeSunshineValue()
    {
        $this->assertIsString($this->sunshineRadiationData->getCodeSunshineValue());
    }

    public function testNullGetCodeSunshineValue()
    {
        $reflectionProperty = new \ReflectionProperty(SunshineRadiationDataGroup::class, 'codeSunshine');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->sunshineRadiationData, null);

        $this->assertNull($this->sunshineRadiationData->getCodeSunshineValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->sunshineRadiationData->getDecoder());
    }

    public function testSuccessGetRawSunshineRadiation()
    {
        $this->assertEquals('55118', $this->sunshineRadiationData->getRawSunshineRadiation());
    }

    public function testSuccessIsStringGetRawSunshineRadiation()
    {
        $this->assertIsString($this->sunshineRadiationData->getRawSunshineRadiation());
    }

    public function testSuccessSetSunshineValue()
    {
        $this->sunshineRadiationData->setSunshineValue(11.5);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertEquals(11.5, $valueSunshine);
    }

    public function testErrorSetSunshineValue()
    {
        $this->sunshineRadiationData->setSunshineValue(11.5);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertNotEquals(11.8, $valueSunshine);
    }

    public function testNullSetSunshineValue()
    {
        $this->sunshineRadiationData->setSunshineValue(null);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertNull($valueSunshine);
    }

    public function testSuccessSetCodeSunshineValue()
    {
        $this->sunshineRadiationData->setCodeSunshineValue('115');

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $valueCodeSunshine = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $this->assertEquals('115', $valueCodeSunshine);
    }

    public function testErrorSetCodeSunshineValue()
    {
        $this->sunshineRadiationData->setCodeSunshineValue('118');

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $valueCodeSunshine = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $this->assertNotEquals('115', $valueCodeSunshine);
    }

    public function testSuccessIsStringSetCodeSunshineValue()
    {
        $this->sunshineRadiationData->setCodeSunshineValue('115');

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $valueCodeSunshine = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $this->assertIsString($valueCodeSunshine);
    }

    public function testNullSetCodeSunshineValue()
    {
        $this->sunshineRadiationData->setCodeSunshineValue(null);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $valueCodeSunshine = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $this->assertNull($valueCodeSunshine);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $this->sunshineRadiationData->setDecoder($decoder);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->sunshineRadiationData);

        $this->assertInstanceOf(GroupDecoderInterface::class, $valueDecoder);
    }

    public function testSuccessSetRawSunshineRadiation()
    {
        $this->sunshineRadiationData->setRawSunshineRadiation('115');

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyRawData = $reflector->getProperty('rawSunshineRadiation');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->sunshineRadiationData);

        $this->assertEquals($valueRawData, '115');
    }

    public function testSuccessIsStringSetRawSunshineRadiation()
    {
        $this->sunshineRadiationData->setRawSunshineRadiation('115');

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyRawData = $reflector->getProperty('rawSunshineRadiation');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->sunshineRadiationData);

        $this->assertIsString($valueRawData);
    }

    public function testSuccessSetData()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->sunshineRadiationData->setData('55115', $validator);

        $reflector = new \ReflectionClass(SunshineRadiationDataGroup::class);
        $propertyRawData = $reflector->getProperty('rawSunshineRadiation');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->sunshineRadiationData);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->sunshineRadiationData);

        $propertyCodeSunshine = $reflector->getProperty('codeSunshine');
        $propertyCodeSunshine->setAccessible(true);
        $valueCodeSunshine = $propertyCodeSunshine->getValue($this->sunshineRadiationData);

        $propertySunshine = $reflector->getProperty('sunshine');
        $propertySunshine->setAccessible(true);
        $valueSunshine = $propertySunshine->getValue($this->sunshineRadiationData);

        $this->assertEquals(['55115', true, '115', 11.5], [$valueRawData, $valueDecoder instanceof SunshineRadiationDataDecoder, $valueCodeSunshine, $valueSunshine]);
    }

    public function testExceptionSetData()
    {
        $validator = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->sunshineRadiationData->setData('', $validator);
    }
}