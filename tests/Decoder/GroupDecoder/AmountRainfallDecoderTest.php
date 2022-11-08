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

        $this->assertEquals('6', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals('6', $result);
    }

    public function testSuccessGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertEquals('001', $result);
    }

    public function testSuccessIsStringGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureAmount()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals('001', $result);
    }

    public function testSuccessGetCodeFigurePeriod()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertEquals('2', $result);
    }

    public function testSuccessIsStringGetCodeFigurePeriod()
    {
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($this->amountRainfallDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigurePeriod()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('52035');
        $reflector = new \ReflectionClass(AmountRainfallDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePeriod');
        $method->setAccessible(true);
        $result = $method->invoke($amountRainfallDecoder);

        $this->assertNotEquals('2', $result);
    }

    public function testSuccessGetAmountRainfall()
    {
        $this->assertEquals([null, 1], $this->amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessIsArrayGetAmountRainfall()
    {
        $this->assertIsArray($this->amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessNoPrecipitationGetAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60002');
        $this->assertEquals(['There was no precipitation', null], $amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessIsArrayNoPrecipitationGetAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60002');
        $this->assertIsArray($amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessTraceGetAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69902');
        $this->assertEquals(['Trace', null], $amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessValueWithTenthsAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69952');
        $this->assertEquals([null, 0.5], $amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessIntegerAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60152');
        $this->assertEquals([null, 15], $amountRainfallDecoder->getAmountRainfall());
    }

    public function testErrorAmountRainfall()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('60152');
        $this->assertNotEquals(null, $amountRainfallDecoder->getAmountRainfall());
    }

    public function testSuccessGetDurationPeriodNumber()
    {
        $this->assertEquals(2, $this->amountRainfallDecoder->getDurationPeriodNumber());
    }

    public function testSuccessIsIntGetDurationPeriodNumber()
    {
        $this->assertIsInt($this->amountRainfallDecoder->getDurationPeriodNumber());
    }

    public function testErrorGetDurationPeriodNumber()
    {
        $amountRainfallDecoder = new AmountRainfallDecoder('69951');
        $this->assertNotEquals('2', $amountRainfallDecoder->getDurationPeriodNumber());
    }

    public function testSuccessGetDurationPeriod()
    {
        $this->assertEquals('At 0600 and 1800 GMT', $this->amountRainfallDecoder->getDurationPeriod());
    }

    public function testSuccessIsStringGetDurationPeriod()
    {
        $this->assertIsString($this->amountRainfallDecoder->getDurationPeriod());
    }

    public function testErrorGetDurationPeriod()
    {
        $this->assertNotEquals('At 0001 and 1200 GMT', $this->amountRainfallDecoder->getDurationPeriod());
    }

    public function testSuccessValueWithTenths()
    {
        $this->assertEquals([null, 0.5], $this->amountRainfallDecoder->valueWithTenths('995'));
    }

    public function testSuccessIsArrayValueWithTenths()
    {
        $this->assertIsArray($this->amountRainfallDecoder->valueWithTenths('995'));
    }

    public function testErrorValueWithTenths()
    {
        $this->assertNotEquals([null, 0.5], $this->amountRainfallDecoder->valueWithTenths('001'));
    }

    public function testSuccessTraceValueWithTenths()
    {
        $this->assertEquals(['Trace', null], $this->amountRainfallDecoder->valueWithTenths('990'));
    }

    public function testSuccessIsArrayTraceValueWithTenths()
    {
        $this->assertIsArray($this->amountRainfallDecoder->valueWithTenths('990'));
    }

    public function testErrorTraceValueWithTenths()
    {
        $this->assertNotEquals(['Trace', null], $this->amountRainfallDecoder->valueWithTenths('995'));
    }

    public function testSuccessNoPrecipitationIntegerValues()
    {
        $this->assertEquals(
            ['There was no precipitation', null],
            $this->amountRainfallDecoder->integerValues('000')
        );
    }

    public function testSuccessIsArrayNoPrecipitationIntegerValues()
    {
        $this->assertIsArray($this->amountRainfallDecoder->integerValues('000'));
    }

    public function testErrorNoPrecipitationIntegerValues()
    {
        $this->assertNotEquals(
            ['There was no precipitation', null],
            $this->amountRainfallDecoder->integerValues('001')
        );
    }

    public function testSuccessIntegerValues()
    {
        $this->assertEquals(
            [null, 1],
            $this->amountRainfallDecoder->integerValues('001')
        );
    }

    public function testSuccessIsArrayIntegerValues()
    {
        $this->assertIsArray($this->amountRainfallDecoder->integerValues('001'));
    }

    public function testErrorIntegerValues()
    {
        $this->assertNotEquals(
            [null, 1],
            $this->amountRainfallDecoder->integerValues('018')
        );
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

    public function testSuccessGetIndicatorGroup()
    {
        $expected = ['6' => 'Indicator'];

        $this->assertEquals($expected, $this->amountRainfallDecoder->getIndicatorGroup());
    }

    public function testSuccessIsArrayGetIndicatorGroup()
    {
        $this->assertIsArray($this->amountRainfallDecoder->getIndicatorGroup());
    }

    public function testSuccessGetAmountRainfallIndicator()
    {
        $expected = ['RRR' => 'Amount of rainfall'];

        $this->assertEquals($expected, $this->amountRainfallDecoder->getAmountRainfallIndicator());
    }

    public function testSuccessIsArrayGetAmountRainfallIndicator()
    {
        $this->assertIsArray($this->amountRainfallDecoder->getAmountRainfallIndicator());
    }

    public function testSuccessGetDurationPeriodIndicator()
    {
        $expected = ['tr' => 'Duration period of RRR'];

        $this->assertEquals($expected, $this->amountRainfallDecoder->getDurationPeriodIndicator());
    }

    public function testSuccessIsArrayGetDurationPeriodIndicator()
    {
        $this->assertIsArray($this->amountRainfallDecoder->getDurationPeriodIndicator());
    }
}
