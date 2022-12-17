<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\AmountRainfallGroup;

class AmountRainfallGroupTest extends TestCase
{
    private $amountRainfallGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);
        $unit = Mockery::mock(Unit::class);

        $this->amountRainfallGroup = new AmountRainfallGroup('60012', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->amountRainfallGroup);
        Mockery::close();
    }

    public function testSuccessSetDurationPeriod()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getDurationPeriod')->once()->andReturn('At 0600 and 1800 GMT');

        $this->amountRainfallGroup->setDurationPeriod($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals('At 0600 and 1800 GMT', $value);
    }

    public function testSuccessIsStringSetDurationPeriod()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getDurationPeriod')->once()->andReturn('At 0600 and 1800 GMT');

        $this->amountRainfallGroup->setDurationPeriod($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsString($value);
    }

    public function testNullSetDurationPeriod()
    {
        $this->amountRainfallGroup->setDurationPeriod(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDurationPeriodNumber()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getDurationPeriodNumber')->once()->andReturn(2);

        $this->amountRainfallGroup->setDurationPeriodNumber($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals(2, $value);
    }

    public function testSuccessIsStringSetDurationPeriodNumber()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getDurationPeriodNumber')->once()->andReturn(2);

        $this->amountRainfallGroup->setDurationPeriodNumber($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetDurationPeriodNumber()
    {
        $this->amountRainfallGroup->setDurationPeriodNumber(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetAmountRainfall()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getAmountRainfall')->once()->andReturn([null, 1]);

        $this->amountRainfallGroup->setAmountRainfall($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals([null, 1], $value);
    }

    public function testSuccessIsArraySetAmountRainfall()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('getAmountRainfall')->once()->andReturn([null, 1]);

        $this->amountRainfallGroup->setAmountRainfall($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsArray($value);
    }

    public function testNullSetAmountRainfall()
    {
        $this->amountRainfallGroup->setAmountRainfall(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsAmountRainfallGroup()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->amountRainfallGroup->isAmountRainfallGroup($decoder, $validate));
    }

    public function testErrorIsAmountRainfallGroup()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->amountRainfallGroup->isAmountRainfallGroup($decoder, $validate));
    }

    public function testSuccessGetAmountRainfallValue()
    {
        $this->assertEquals([null, 1], $this->amountRainfallGroup->getAmountRainfallValue());
    }

    public function testSuccessIsArrayGetAmountRainfallValue()
    {
        $this->assertIsArray($this->amountRainfallGroup->getAmountRainfallValue());
    }

    public function testSuccessGetDurationPeriodValue()
    {
        $this->assertEquals(
            'Total precipitation during the 12 hours preceding the observation',
            $this->amountRainfallGroup->getDurationPeriodValue()
        );
    }

    public function testSuccessIsStringGetDurationPeriodValue()
    {
        $this->assertIsString($this->amountRainfallGroup->getDurationPeriodValue());
    }

    public function testSuccessGetDurationPeriodNumberValue()
    {
        $this->assertEquals(2, $this->amountRainfallGroup->getDurationPeriodNumberValue());
    }

    public function testSuccessIsIntGetDurationPeriodNumberValue()
    {
        $this->assertIsInt($this->amountRainfallGroup->getDurationPeriodNumberValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->amountRainfallGroup->getDecoder());
    }

    public function testSuccessGetRawAmountRainfall()
    {
        $this->assertEquals('60012', $this->amountRainfallGroup->getRawAmountRainfall());
    }

    public function testSuccessIsStringGetRawAmountRainfall()
    {
        $this->assertIsString($this->amountRainfallGroup->getRawAmountRainfall());
    }

    public function testSuccessSetAmountRainfallValue()
    {
        $this->amountRainfallGroup->setAmountRainfallValue([null, 1]);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals([null, 1], $value);
    }

    public function testSuccessIsArraySetAmountRainfallValue()
    {
        $this->amountRainfallGroup->setAmountRainfallValue([null, 1]);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsArray($value);
    }

    public function testNullSetAmountRainfallValue()
    {
        $this->amountRainfallGroup->setAmountRainfallValue(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('amountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDurationPeriodValue()
    {
        $this->amountRainfallGroup->setDurationPeriodValue('At 0600 and 1800 GMT');

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals('At 0600 and 1800 GMT', $value);
    }

    public function testSuccessIsStringSetDurationPeriodValue()
    {
        $this->amountRainfallGroup->setDurationPeriodValue('At 0600 and 1800 GMT');

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsString($value);
    }

    public function testNullStringSetDurationPeriodValue()
    {
        $this->amountRainfallGroup->setDurationPeriodValue(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriod');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDurationPeriodNumberValue()
    {
        $this->amountRainfallGroup->setDurationPeriodNumberValue(2);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals(2, $value);
    }

    public function testSuccessIsIntSetDurationPeriodNumberValue()
    {
        $this->amountRainfallGroup->setDurationPeriodNumberValue(2);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetDurationPeriodNumberValue()
    {
        $this->amountRainfallGroup->setDurationPeriodNumberValue(null);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('durationPeriodNumber');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $this->amountRainfallGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetRawAmountRainfall()
    {
        $this->amountRainfallGroup->setRawAmountRainfall('60021');

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('rawAmountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertEquals('60021', $value);
    }

    public function testSuccessIsStringSetRawAmountRainfall()
    {
        $this->amountRainfallGroup->setRawAmountRainfall('60021');

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $property = $reflector->getProperty('rawAmountRainfall');
        $property->setAccessible(true);
        $value = $property->getValue($this->amountRainfallGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetAmountRainfallGroup()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getAmountRainfall')->once()->andReturn([null, 2]);
        $decoder->shouldReceive('getDurationPeriodNumber')->once()->andReturn(2);
        $decoder->shouldReceive('getDurationPeriod')->once()->andReturn('At 0600 and 1800 GMT');

        $validate = Mockery::mock(Validate::class);

        $this->amountRainfallGroup->setAmountRainfallGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $propertyAmount = $reflector->getProperty('amountRainfall');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->amountRainfallGroup);

        $propertyPeriodNumber = $reflector->getProperty('durationPeriodNumber');
        $propertyPeriodNumber->setAccessible(true);
        $valuePeriodNumber = $propertyPeriodNumber->getValue($this->amountRainfallGroup);

        $propertyPeriod = $reflector->getProperty('durationPeriod');
        $propertyPeriod->setAccessible(true);
        $valuePeriod = $propertyPeriod->getValue($this->amountRainfallGroup);

        $expected = [
            [null, 2],
            2,
            'At 0600 and 1800 GMT',
        ];

        $this->assertEquals($expected, [$valueAmount, $valuePeriodNumber, $valuePeriod]);
    }

    public function testNullSetAmountRainfallGroup()
    {
        $decoder = Mockery::mock(AmountRainfallDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->amountRainfallGroup->setAmountRainfallGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $propertyAmount = $reflector->getProperty('amountRainfall');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->amountRainfallGroup);

        $propertyPeriodNumber = $reflector->getProperty('durationPeriodNumber');
        $propertyPeriodNumber->setAccessible(true);
        $valuePeriodNumber = $propertyPeriodNumber->getValue($this->amountRainfallGroup);

        $propertyPeriod = $reflector->getProperty('durationPeriod');
        $propertyPeriod->setAccessible(true);
        $valuePeriod = $propertyPeriod->getValue($this->amountRainfallGroup);

        $expected = [null, null, null];

        $this->assertEquals($expected, [$valueAmount, $valuePeriodNumber, $valuePeriod]);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->amountRainfallGroup->setData('60025', $validate);

        $reflector = new \ReflectionClass(AmountRainfallGroup::class);
        $propertyData = $reflector->getProperty('rawAmountRainfall');
        $propertyData->setAccessible(true);
        $valueData = $propertyData->getValue($this->amountRainfallGroup);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->amountRainfallGroup);

        $propertyAmount = $reflector->getProperty('amountRainfall');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->amountRainfallGroup);

        $propertyPeriodNumber = $reflector->getProperty('durationPeriodNumber');
        $propertyPeriodNumber->setAccessible(true);
        $valuePeriodNumber = $propertyPeriodNumber->getValue($this->amountRainfallGroup);

        $propertyPeriod = $reflector->getProperty('durationPeriod');
        $propertyPeriod->setAccessible(true);
        $valuePeriod = $propertyPeriod->getValue($this->amountRainfallGroup);

        $expected = [
            '60025',
            true,
            [null, 2],
            '5',
            'Total precipitation during the 1 hour preceding the observation',
        ];

        $this->assertEquals($expected, [$valueData, $valueDecoder instanceof GroupDecoderInterface, $valueAmount, $valuePeriodNumber, $valuePeriod]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->amountRainfallGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('6RRRtr', $this->amountRainfallGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->amountRainfallGroup->getGroupIndicator());
    }
}
