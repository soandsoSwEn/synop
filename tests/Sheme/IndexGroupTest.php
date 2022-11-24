<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\IndexGroup;

class IndexGroupTest extends TestCase
{
    private $indexGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->indexGroup = new IndexGroup('33837', $validate);
    }

    protected function tearDown(): void
    {
        unset($this->indexGroup);
    }

    public function testSuccessSetStationIndex()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getIndex')->once()->andReturn('33835');

        $this->indexGroup->setStationIndex($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('33835', $value);
    }

    public function testSuccessIsStringSetStationIndex()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getIndex')->once()->andReturn('33835');

        $this->indexGroup->setStationIndex($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetStationIndex()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getIndex')->once()->andReturn('33835');

        $this->indexGroup->setStationIndex($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('33837', $value);
    }

    public function testSuccessSetStationNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getNumber')->once()->andReturn('835');

        $this->indexGroup->setStationNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('835', $value);
    }

    public function testSuccessIsStringSetStationNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getNumber')->once()->andReturn('835');

        $this->indexGroup->setStationNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetStationNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getNumber')->once()->andReturn('835');

        $this->indexGroup->setStationNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('837', $value);
    }

    public function testSuccessSetAreaNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getArea')->once()->andReturn('33');

        $this->indexGroup->setAreaNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('33', $value);
    }

    public function testSuccessIsStringSetAreaNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getArea')->once()->andReturn('33');

        $this->indexGroup->setAreaNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetAreaNumber()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('getArea')->once()->andReturn('33');

        $this->indexGroup->setAreaNumber($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('34', $value);
    }

    public function testSuccessSetIndexGroup()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getArea')->once()->andReturn('33');
        $decoder->shouldReceive('getNumber')->once()->andReturn('835');
        $decoder->shouldReceive('getIndex')->once()->andReturn('33835');

        $validate = Mockery::mock(Validate::class);

        $this->indexGroup->setIndexGroup($decoder, $validate);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $propertyAreaNum = $reflector->getProperty('areaNumber');
        $propertyAreaNum->setAccessible(true);
        $valueAreaNum = $propertyAreaNum->getValue($this->indexGroup);

        $propertyStNum = $reflector->getProperty('stationNumber');
        $propertyStNum->setAccessible(true);
        $valueStNum = $propertyStNum->getValue($this->indexGroup);

        $propertyStIn = $reflector->getProperty('stationIndex');
        $propertyStIn->setAccessible(true);
        $valueStIn = $propertyStIn->getValue($this->indexGroup);

        $this->assertEquals(['33', '835', '33835'], [$valueAreaNum, $valueStNum, $valueStIn]);
    }

    public function testExceptionSetIndexGroup()
    {
        $decoder = Mockery::mock(IndexDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->indexGroup->setIndexGroup($decoder, $validate);
    }

    public function testSuccessGetStationIndexValue()
    {
        $this->assertEquals('33837', $this->indexGroup->getStationIndexValue());
    }

    public function testSuccessIsStringGetStationIndexValue()
    {
        $this->assertIsString($this->indexGroup->getStationIndexValue());
    }

    public function testErrorGetStationIndexValue()
    {
        $this->assertNotEquals('33835', $this->indexGroup->getStationIndexValue());
    }

    public function testSuccessGetStationNumberValue()
    {
        $this->assertEquals('837', $this->indexGroup->getStationNumberValue());
    }

    public function testSuccessIsStringGetStationNumberValue()
    {
        $this->assertIsString($this->indexGroup->getStationNumberValue());
    }

    public function testErrorGetStationNumberValue()
    {
        $this->assertNotEquals('835', $this->indexGroup->getStationNumberValue());
    }

    public function testSuccessGetAreaNumberValue()
    {
        $this->assertEquals('33', $this->indexGroup->getAreaNumberValue());
    }

    public function testSuccessIsStringGetAreaNumberValue()
    {
        $this->assertIsString($this->indexGroup->getAreaNumberValue());
    }

    public function testErrorGetAreaNumberValue()
    {
        $this->assertNotEquals('34', $this->indexGroup->getAreaNumberValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->indexGroup->getDecoder());
    }

    public function testSuccessSetStationIndexValue()
    {
        $this->indexGroup->setStationIndexValue('33835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('33835', $value);
    }

    public function testSuccessIsStringSetStationIndexValue()
    {
        $this->indexGroup->setStationIndexValue('33835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetStationIndexValue()
    {
        $this->indexGroup->setStationIndexValue('33835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationIndex');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('33837', $value);
    }

    public function testSuccessSetStationNumberValue()
    {
        $this->indexGroup->setStationNumberValue('835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('835', $value);
    }

    public function testSuccessIsStringSetStationNumberValue()
    {
        $this->indexGroup->setStationNumberValue('835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetStationNumberValue()
    {
        $this->indexGroup->setStationNumberValue('835');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('stationNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('837', $value);
    }

    public function testSuccessSetAreaNumberValue()
    {
        $this->indexGroup->setAreaNumberValue('34');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertEquals('34', $value);
    }

    public function testSuccessIsStringSetAreaNumberValue()
    {
        $this->indexGroup->setAreaNumberValue('34');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetAreaNumberValue()
    {
        $this->indexGroup->setAreaNumberValue('34');

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('areaNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertNotEquals('33', $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(IndexDecoder::class);

        $this->indexGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->indexGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->indexGroup->setData('33835', $validate);

        $reflector = new \ReflectionClass(IndexGroup::class);
        $propertyDec = $reflector->getProperty('decoder');
        $propertyDec->setAccessible(true);
        $valueDec = $propertyDec->getValue($this->indexGroup);

        $propertyAreaNum = $reflector->getProperty('areaNumber');
        $propertyAreaNum->setAccessible(true);
        $valueAreaNum = $propertyAreaNum->getValue($this->indexGroup);

        $propertyStNum = $reflector->getProperty('stationNumber');
        $propertyStNum->setAccessible(true);
        $valueStNum = $propertyStNum->getValue($this->indexGroup);

        $propertyStIn = $reflector->getProperty('stationIndex');
        $propertyStIn->setAccessible(true);
        $valueStIn = $propertyStIn->getValue($this->indexGroup);

        $this->assertEquals(
            [true, '33', '835', '33835'],
            [$valueDec instanceof GroupDecoderInterface,$valueAreaNum, $valueStNum, $valueStIn]
        );
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->indexGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('IIiii', $this->indexGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->indexGroup->getGroupIndicator());
    }
}
