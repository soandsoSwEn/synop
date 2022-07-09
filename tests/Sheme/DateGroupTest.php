<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\DateDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\DateGroup;

class DateGroupTest extends TestCase
{
    private $dateGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->dateGroup = new DateGroup('07181', $validate);
    }

    protected function tearDown(): void
    {
        unset($this->dateGroup);
        Mockery::close();
    }

    public function testSuccessIsDateGroup()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->dateGroup->isDateGroup($decoder, $validate));
    }

    public function testErrorIsDateGroup()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->dateGroup->isDateGroup($decoder, $validate));
    }

    public function testSuccessSetIw()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getIw')->once()->andReturn(['Instrumental', 'm/s']);

        $this->dateGroup->setIw($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('iw');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals(['Instrumental', 'm/s'], $value);
    }

    public function testSuccessIsArraySetIw()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getIw')->once()->andReturn(['Instrumental', 'm/s']);

        $this->dateGroup->setIw($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('iw');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsArray($value);
    }

    public function testSuccessSetHour()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getHour')->once()->andReturn('15');

        $this->dateGroup->setHour($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('hour');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals('15', $value);
    }

    public function testSuccessIsStringSetHour()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getHour')->once()->andReturn('15');

        $this->dateGroup->setHour($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('hour');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetDay()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getDay')->once()->andReturn('07');

        $this->dateGroup->setDay($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('day');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals('07', $value);
    }

    public function testSuccessIsStringSetDay()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('getDay')->once()->andReturn('07');

        $this->dateGroup->setDay($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('day');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetDateGroup()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getDay')->once()->andReturn('08');
        $decoder->shouldReceive('getHour')->once()->andReturn('15');
        $decoder->shouldReceive('getIw')->once()->andReturn(['Instrumental', 'm/s']);

        $validate = Mockery::mock(Validate::class);

        $this->dateGroup->setDateGroup($decoder, $validate);

        $reflector = new \ReflectionClass(DateGroup::class);
        $propertyDay = $reflector->getProperty('day');
        $propertyDay->setAccessible(true);
        $valueDay = $propertyDay->getValue($this->dateGroup);

        $propertyHour = $reflector->getProperty('hour');
        $propertyHour->setAccessible(true);
        $valueHour = $propertyHour->getValue($this->dateGroup);

        $propertyIw = $reflector->getProperty('iw');
        $propertyIw->setAccessible(true);
        $valueIw = $propertyIw->getValue($this->dateGroup);

        $expected = ['08', '15', ['Instrumental', 'm/s']];

        $this->assertEquals($expected, [$valueDay, $valueHour, $valueIw]);
    }

    public function testSuccessGetIwValue()
    {
        $this->assertEquals(['Instrumental', 'm/s'], $this->dateGroup->getIwValue());
    }

    public function testSuccessIsArrayGetIwValue()
    {
        $this->assertIsArray($this->dateGroup->getIwValue());
    }

    public function testSuccessGetHourValue()
    {
        $this->assertEquals('18', $this->dateGroup->getHourValue());
    }

    public function testSuccessIsStringGetHourValue()
    {
        $this->assertIsString($this->dateGroup->getHourValue());
    }

    public function testSuccessGetDayValue()
    {
        $this->assertEquals('07', $this->dateGroup->getDayValue());
    }

    public function testSuccessIsStringGetDayValue()
    {
        $this->assertIsString($this->dateGroup->getDayValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->dateGroup->getDecoder());
    }

    public function testSuccessSetIwValue()
    {
        $this->dateGroup->setIwValue(['Visual', 'knot']);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('iw');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals(['Visual', 'knot'], $value);
    }

    public function testSuccessIsArraySetIwValue()
    {
        $this->dateGroup->setIwValue(['Visual', 'knot']);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('iw');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsArray($value);
    }

    public function testSuccessSetHourValue()
    {
        $this->dateGroup->setHourValue('12');

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('hour');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals('12', $value);
    }

    public function testSuccessIsStringSetHourValue()
    {
        $this->dateGroup->setHourValue('12');

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('hour');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetDayValue()
    {
        $this->dateGroup->setDayValue('05');

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('day');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertEquals('05', $value);
    }

    public function testSuccessIsStringSetDayValue()
    {
        $this->dateGroup->setDayValue('05');

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('day');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(DateDecoder::class);
        $this->dateGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(DateGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->dateGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->dateGroup->setData('15090', $validate);

        $reflector = new \ReflectionClass(DateGroup::class);
        $propertyDec = $reflector->getProperty('decoder');
        $propertyDec->setAccessible(true);
        $valueDec = $propertyDec->getValue($this->dateGroup);

        $propertyDay = $reflector->getProperty('day');
        $propertyDay->setAccessible(true);
        $valueDay = $propertyDay->getValue($this->dateGroup);

        $propertyHour = $reflector->getProperty('hour');
        $propertyHour->setAccessible(true);
        $valueHour = $propertyHour->getValue($this->dateGroup);

        $propertyIw = $reflector->getProperty('iw');
        $propertyIw->setAccessible(true);
        $valueIw = $propertyIw->getValue($this->dateGroup);

        $expected = [true, '15', '09', ['Visual', 'm/s']];

        $this->assertEquals($expected, [$valueDec instanceof DateDecoder, $valueDay, $valueHour, $valueIw]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->dateGroup->setData('', $validate);
    }
}
