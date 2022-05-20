<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Soandso\Synop\Fabrication\Validate;

class AmountRainfallDecoderTest extends TestCase
{
    private $amountRainfallDecoder;

    protected function setUp(): void
    {
        $this->amountRainfallDecoder = new AmountRainfallDecoder('60012');
    }

    protected function tearDown(): void
    {
        unset($this->amountRainfallDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertEquals($result, '6');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals($result, '6');
    }

    public function testSuccessGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertEquals($result, '001');
    }

    public function testErrorGetCodeFigureAmount()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals($result, '001');
    }

    public function testSuccessGetCodeFigurePeriod()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertEquals($result, '2');
    }

    public function testErrorGetCodeFigurePeriod()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals($result, '2');
    }

    public function testSuccessGetAmountRainfall()
    {
        $this->assertEquals($this->amountRainfallDecoder->getAmountRainfall(), [null, 1]);
    }

    public function testSuccessNoPrecipitationGetAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60002');
        $this->assertEquals($amountRainfallDecoder->getAmountRainfall(), ['There was no precipitation', null]);
    }

    public function testSuccessTraceGetAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69902');
        $this->assertEquals($amountRainfallDecoder->getAmountRainfall(), ['Trace', null]);
    }

    public function testSuccessValueWithTenthsAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69952');
        $this->assertEquals($amountRainfallDecoder->getAmountRainfall(), [null, 0.5]);
    }

    public function testSuccessIntegerAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60152');
        $this->assertEquals($amountRainfallDecoder->getAmountRainfall(), [null, 15]);
    }

    public function testErrorAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60152');
        $this->assertNotEquals($amountRainfallDecoder->getAmountRainfall(), null);
    }

    public function testSuccessGetDurationPeriodNumber()
    {
        $this->assertEquals($this->amountRainfallDecoder->getDurationPeriodNumber(), '2');
    }

    public function testErrorGetDurationPeriodNumber()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69951');
        $this->assertNotEquals($amountRainfallDecoder->getDurationPeriodNumber(), '2');
    }

    public function testSuccessGetDurationPeriod()
    {
        $this->assertEquals($this->amountRainfallDecoder->getDurationPeriod(), 'At 0600 and 1800 GMT');
    }

    public function testErrorGetDurationPeriod()
    {
        $this->assertNotEquals($this->amountRainfallDecoder->getDurationPeriod(), 'At 0001 and 1200 GMT');
    }

    public function testSuccessValueWithTenths()
    {
        $this->assertEquals($this->amountRainfallDecoder->valueWithTenths('995'), [null, 0.5]);
    }

    public function testErrorValueWithTenths()
    {
        $this->assertNotEquals($this->amountRainfallDecoder->valueWithTenths('001'), [null, 0.5]);
    }

    public function testSuccessTraceValueWithTenths()
    {
        $this->assertEquals($this->amountRainfallDecoder->valueWithTenths('990'), ['Trace', null]);
    }

    public function testErrorTraceValueWithTenths()
    {
        $this->assertNotEquals($this->amountRainfallDecoder->valueWithTenths('995'), ['Trace', null]);
    }

    public function testSuccessNoPrecipitationIntegerValues()
    {
        $this->assertEquals($this->amountRainfallDecoder->integerValues('000'), ['There was no precipitation', null]);
    }

    public function testErrorNoPrecipitationIntegerValues()
    {
        $this->assertNotEquals($this->amountRainfallDecoder->integerValues('001'), ['There was no precipitation', null]);
    }

    public function testSuccessIntegerValues()
    {
        $this->assertEquals($this->amountRainfallDecoder->integerValues('001'), [null, 1]);
    }

    public function testErrorIntegerValues()
    {
        $this->assertNotEquals($this->amountRainfallDecoder->integerValues('018'), [null, 1]);
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->amountRainfallDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('AAXX');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($amountRainfallDecoder->isGroup($validate));
    }
}