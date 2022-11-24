<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\CloudWindGroup;

class CloudWindGroupTest extends TestCase
{
    private $cloudWindGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->cloudWindGroup = new CloudWindGroup('83102', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->cloudWindGroup);
        Mockery::close();
    }

    public function testSuccessSetWindSpeed()
    {
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $decoder->shouldReceive('getVv')->once()->andReturn('4');

        $this->cloudWindGroup->setWindSpeed($decoder);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('windSpeed');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('4', $value);
    }

    public function testSuccessSetDirectionWind()
    {
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $decoder->shouldReceive('getDd')->once()->andReturn('320');

        $this->cloudWindGroup->setDirectionWind($decoder);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('directionWind');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('320', $value);
    }

    public function testSuccessSetTotalClouds()
    {
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $decoder->shouldReceive('getN')->once()->andReturn('10');

        $this->cloudWindGroup->setTotalClouds($decoder);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('totalClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('10', $value);
    }

    public function testSuccessSetCloudWind()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getN')->once()->andReturn('10');
        $decoder->shouldReceive('getDd')->once()->andReturn(320);
        $decoder->shouldReceive('getVv')->once()->andReturn(4);

        $this->cloudWindGroup->setCloudWind($decoder, $validate);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $propertyTotalClouds = $reflector->getProperty('totalClouds');
        $propertyTotalClouds->setAccessible(true);
        $valueTotalClouds = $propertyTotalClouds->getValue($this->cloudWindGroup);

        $propertyDirectionWind = $reflector->getProperty('directionWind');
        $propertyDirectionWind->setAccessible(true);
        $valueDirectionWind = $propertyDirectionWind->getValue($this->cloudWindGroup);

        $propertyWindSpeed = $reflector->getProperty('windSpeed');
        $propertyWindSpeed->setAccessible(true);
        $valueWindSpeed = $propertyWindSpeed->getValue($this->cloudWindGroup);

        $expected = ['10', 320, 4];

        $this->assertEquals($expected, [$valueTotalClouds, $valueDirectionWind, $valueWindSpeed]);
    }

    public function testExceptionSetCloudWind()
    {
        $validate = Mockery::mock(Validate::class);
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $this->expectException(\Exception::class);

        $this->cloudWindGroup->setCloudWind($decoder, $validate);
    }

    public function testSuccessGetWindSpeedValue()
    {
        $this->assertEquals('2', $this->cloudWindGroup->getWindSpeedValue());
    }

    public function testSuccessGetDirectionWindValue()
    {
        $this->assertEquals('310', $this->cloudWindGroup->getDirectionWindValue());
    }

    public function testSuccessGetTotalCloudsValue()
    {
        $this->assertEquals('10', $this->cloudWindGroup->getTotalCloudsValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->cloudWindGroup->getDecoder());
    }

    public function testSuccessSetWindSpeedValue()
    {
        $this->cloudWindGroup->setWindSpeedValue('5');

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('windSpeed');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('5', $value);
    }

    public function testSuccessSetDirectionWindValue()
    {
        $this->cloudWindGroup->setDirectionWindValue('150');

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('directionWind');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('150', $value);
    }

    public function testSuccessSetTotalCloudsValue()
    {
        $this->cloudWindGroup->setTotalCloudsValue('8');

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('totalClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertEquals('8', $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(CloudWindDecoder::class);
        $this->cloudWindGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudWindGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);
        $this->cloudWindGroup->setData('40207', $validate);

        $reflector = new \ReflectionClass(CloudWindGroup::class);
        $propertyDec = $reflector->getProperty('decoder');
        $propertyDec->setAccessible(true);
        $valueDec = $propertyDec->getValue($this->cloudWindGroup);

        $propertyTotalClouds = $reflector->getProperty('totalClouds');
        $propertyTotalClouds->setAccessible(true);
        $valueTotalClouds = $propertyTotalClouds->getValue($this->cloudWindGroup);

        $propertyDirectionWind = $reflector->getProperty('directionWind');
        $propertyDirectionWind->setAccessible(true);
        $valueDirectionWind = $propertyDirectionWind->getValue($this->cloudWindGroup);

        $propertyWindSpeed = $reflector->getProperty('windSpeed');
        $propertyWindSpeed->setAccessible(true);
        $valueWindSpeed = $propertyWindSpeed->getValue($this->cloudWindGroup);

        $expected = [true, '5', '20', '7'];

        $this->assertEquals($expected, [$valueDec instanceof GroupDecoderInterface, $valueTotalClouds, $valueDirectionWind, $valueWindSpeed]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->cloudWindGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('Nddff', $this->cloudWindGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->cloudWindGroup->getGroupIndicator());
    }
}
