<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\BaricTendencyGroup;

class BaricTendencyGroupTest extends TestCase
{
    private $baricTendencyGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->baricTendencyGroup = new BaricTendencyGroup('52035', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->baricTendencyGroup);
        Mockery::close();
    }

    public function testSuccessSetBaricTendency()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('getBaricTendency')->once()->andReturn(3.5);

        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, 2);

        $this->baricTendencyGroup->setBaricTendency($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendencyValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(3.5, $value);
    }

    public function testSuccessNegativeSetBaricTendency()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('getBaricTendency')->once()->andReturn(3.5);

        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, 5);

        $this->baricTendencyGroup->setBaricTendency($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendencyValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(-3.5, $value);
    }

    public function testSuccessIsFloatSetBaricTendency()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('getBaricTendency')->once()->andReturn(3.5);

        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, 5);

        $this->baricTendencyGroup->setBaricTendency($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendencyValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertIsFloat($value);
    }

    public function testNullDecoderSetBaricTendency()
    {
        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, 5);

        $this->baricTendencyGroup->setBaricTendency(null);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendency');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertNull($value);
    }

    public function testChangeNullSetBaricTendency()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);

        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, null);

        $this->baricTendencyGroup->setBaricTendency($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendency');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertNull($value);
    }

    public function testNullSetBaricTendency()
    {
        $reflectorProperty = new \ReflectionProperty(BaricTendencyGroup::class, 'characteristicChange');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->baricTendencyGroup, null);

        $this->baricTendencyGroup->setBaricTendency(null);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendency');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetCharacteristicChange()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('getCharacteristicChange')->once()->andReturn(1);

        $this->baricTendencyGroup->setCharacteristicChange($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('characteristicChange');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(1, $value);
    }

    public function testSuccessIsIntegerSetCharacteristicChange()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('getCharacteristicChange')->once()->andReturn(1);

        $this->baricTendencyGroup->setCharacteristicChange($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('characteristicChange');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetCharacteristicChange()
    {
        $this->baricTendencyGroup->setCharacteristicChange(null);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('characteristicChange');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsBaricTendencyGroup()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->baricTendencyGroup->isBaricTendencyGroup($decoder, $validate));
    }

    public function testErrorIsBaricTendencyGroup()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->baricTendencyGroup->isBaricTendencyGroup($decoder, $validate));
    }

    public function testSuccessSetBaricTendencyGroup()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCharacteristicChange')->once()->andReturn(2);
        $decoder->shouldReceive('getBaricTendency')->once()->andReturn(4.2);

        $validate = Mockery::mock(Validate::class);

        $this->baricTendencyGroup->setBaricTendencyGroup($decoder, $validate);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $propertyCharacteristic = $reflector->getProperty('characteristicChange');
        $propertyCharacteristic->setAccessible(true);
        $valueCharacteristic = $propertyCharacteristic->getValue($this->baricTendencyGroup);

        $propertyTendency = $reflector->getProperty('tendencyValue');
        $propertyTendency->setAccessible(true);
        $valueTendency = $propertyTendency->getValue($this->baricTendencyGroup);

        $this->assertEquals([2, 4.2], [$valueCharacteristic, $valueTendency]);
    }

    public function testSuccessNegativeSetBaricTendencyGroup()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCharacteristicChange')->once()->andReturn(5);
        $decoder->shouldReceive('getBaricTendency')->once()->andReturn(4.2);

        $validate = Mockery::mock(Validate::class);

        $this->baricTendencyGroup->setBaricTendencyGroup($decoder, $validate);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $propertyCharacteristic = $reflector->getProperty('characteristicChange');
        $propertyCharacteristic->setAccessible(true);
        $valueCharacteristic = $propertyCharacteristic->getValue($this->baricTendencyGroup);

        $propertyTendency = $reflector->getProperty('tendencyValue');
        $propertyTendency->setAccessible(true);
        $valueTendency = $propertyTendency->getValue($this->baricTendencyGroup);

        $this->assertEquals([5, -4.2], [$valueCharacteristic, $valueTendency]);
    }

    public function testNullSetBaricTendencyGroup()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->baricTendencyGroup->setBaricTendencyGroup($decoder, $validate);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $propertyCharacteristic = $reflector->getProperty('characteristicChange');
        $propertyCharacteristic->setAccessible(true);
        $valueCharacteristic = $propertyCharacteristic->getValue($this->baricTendencyGroup);

        $propertyTendency = $reflector->getProperty('tendency');
        $propertyTendency->setAccessible(true);
        $valueTendency = $propertyTendency->getValue($this->baricTendencyGroup);

        $this->assertEquals([null, null], [$valueCharacteristic, $valueTendency]);
    }

    public function testSuccessGetTendencyValueData()
    {
        $this->assertEquals('3.5', $this->baricTendencyGroup->getTendencyValueData());
    }

    public function testSuccessGetTendencyValue()
    {
        $this->assertEquals(3.5, $this->baricTendencyGroup->getTendencyValue());
    }

    public function testSuccessIsFloatGetTendencyValue()
    {
        $this->assertIsFloat($this->baricTendencyGroup->getTendencyValue());
    }

    public function testSuccessGetCharacteristicChangeValue()
    {
        $this->assertEquals(2, $this->baricTendencyGroup->getCharacteristicChangeValue());
    }

    public function testSuccessIsIntGetCharacteristicChangeValue()
    {
        $this->assertIsInt($this->baricTendencyGroup->getCharacteristicChangeValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->baricTendencyGroup->getDecoder());
    }

    public function testSuccesssetTendencyValueData()
    {
        $this->baricTendencyGroup->setTendencyValueData(-5.5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendencyValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(-5.5, $value);
    }

    public function testSuccessSetIsFloatTendencyValueData()
    {
        $this->baricTendencyGroup->setTendencyValueData(-5.5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendencyValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertIsFloat(-5.5, $value);
    }

    public function testSuccessSetTendencyValue()
    {
        $this->baricTendencyGroup->setTendencyValue(5.5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendency');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(5.5, $value);
    }

    public function testSuccessIsFloatSetTendencyValue()
    {
        $this->baricTendencyGroup->setTendencyValue(5.5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('tendency');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertIsFloat($value);
    }

    public function testSuccessSetCharacteristicChangeValue()
    {
        $this->baricTendencyGroup->setCharacteristicChangeValue(5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('characteristicChange');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertEquals(5, $value);
    }

    public function testSuccessIsIntegerSetCharacteristicChangeValue()
    {
        $this->baricTendencyGroup->setCharacteristicChangeValue(5);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('characteristicChange');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertIsInt($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(BaricTendencyDecoder::class);
        $this->baricTendencyGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->baricTendencyGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->baricTendencyGroup->setData('55042', $validate);

        $reflector = new \ReflectionClass(BaricTendencyGroup::class);
        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->baricTendencyGroup);

        $propertyCharacteristic = $reflector->getProperty('characteristicChange');
        $propertyCharacteristic->setAccessible(true);
        $valueCharacteristic = $propertyCharacteristic->getValue($this->baricTendencyGroup);

        $propertyTendency = $reflector->getProperty('tendencyValue');
        $propertyTendency->setAccessible(true);
        $valueTendency = $propertyTendency->getValue($this->baricTendencyGroup);

        $expected = [true, 5, -4.2];

        $this->assertEquals($expected, [$valueDecoder instanceof GroupDecoderInterface, $valueCharacteristic, $valueTendency]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->baricTendencyGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('5appp', $this->baricTendencyGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->baricTendencyGroup->getGroupIndicator());
    }
}
