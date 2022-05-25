<?php

namespace Soandso\Synop\Tests\Decoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\SectionFiveDecoder;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\SectionInterface;

class SectionFiveDecoderTest extends TestCase
{
    private $sectionFiveDecoder;

    protected function setUp(): void
    {
        $this->sectionFiveDecoder = new SectionFiveDecoder(new Section('Section Five'), true, false);
    }

    protected function tearDown(): void
    {
        unset($this->sectionFiveDecoder);
        Mockery::close();
    }

    public function testIsSynop()
    {
        $reflector = new  \ReflectionClass(SectionFiveDecoder::class);
        $property = $reflector->getProperty('synop_report');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionFiveDecoder);

        $this->assertTrue($value);
    }

    public function testIsShip()
    {
        $reflector = new  \ReflectionClass(SectionFiveDecoder::class);
        $property = $reflector->getProperty('ship_report');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionFiveDecoder);

        $this->assertFalse($value);
    }

    public function testSuccessIsGroup()
    {
        $this->assertTrue($this->sectionFiveDecoder->isGroup('10178', 5));
    }

    public function testErrorIsGroup()
    {
        $this->assertFalse($this->sectionFiveDecoder->isGroup('AAXX', 5));
    }

    public function testSuccessGet1SnT24T24T24()
    {
        $report = '10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get1SnT24T24T24($rawReport));
    }

    public function testNullGet1SnT24T24T24()
    {
        $report = '1017 3/021 41022 60021 77182 92952=';
        $updatedReport = '3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get1SnT24T24T24($rawReport));
    }

    public function testErrorGet1SnT24T24T24()
    {
        $report = '555 10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get1SnT24T24T24($rawReport));
    }

    public function testSuccessGet3SnTgTg()
    {
        $report = '3/021 41022 60021 77182 92952=';
        $updatedReport = '41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get3SnTgTg($rawReport));
    }

    public function testNullGet3SnTgTg()
    {
        $report = '3/02 41022 60021 77182 92952=';
        $updatedReport = '41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get3SnTgTg($rawReport));
    }

    public function testErrorGet3SnTgTg()
    {
        $report = '10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get3SnTgTg($rawReport));
    }

    public function testSuccessGet4Esss()
    {
        $report = '41022 60021 77182 92952=';
        $updatedReport = '60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get4Esss($rawReport));
    }

    public function testNullGet4Esss()
    {
        $report = '4102 60021 77182 92952=';
        $updatedReport = '60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get4Esss($rawReport));
    }

    public function testErrorGet4Esss()
    {
        $report = '3/021 41022 60021 77182 92952=';
        $updatedReport = '60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get4Esss($rawReport));
    }

    public function testSuccessGet6RRRtr()
    {
        $report = '60021 77182 92952=';
        $updatedReport = '77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get6RRRtr($rawReport));
    }

    public function testNullGet6RRRtr()
    {
        $report = '6002 77182 92952=';
        $updatedReport = '77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get6RRRtr($rawReport));
    }

    public function testErrorGet6RRRtr()
    {
        $report = '41022 60021 77182 92952=';
        $updatedReport = '77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get6RRRtr($rawReport));
    }

    public function testSuccessGet7R24R24R24E()
    {
        $report = '77182 92952=';
        $updatedReport = '92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get7R24R24R24E($rawReport));
    }

    public function testNullGet7R24R24R24E()
    {
        $report = '7718 92952=';
        $updatedReport = '92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get7R24R24R24E($rawReport));
    }

    public function testErrorGet7R24R24R24E()
    {
        $report = '60021 77182 92952=';
        $updatedReport = '92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get7R24R24R24E($rawReport));
    }

    public function testSuccessGet9SpSpspsp()
    {
        $report = '92952=';
        $updatedReport = '';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFiveDecoder->get9SpSpspsp($rawReport));
    }

    public function testNullGet9SpSpspsp()
    {
        $report = '9295=';
        $updatedReport = '';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get9SpSpspsp($rawReport));
    }

    public function testErrorGet9SpSpspsp()
    {
        $report = '77182 92952=';
        $updatedReport = '';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFiveDecoder->get9SpSpspsp($rawReport));
    }

    public function testSuccessPutInSection()
    {
        $data = '10178 3/021 41022 60021 77182 92952=';
        $reflector = new \ReflectionClass(SectionFiveDecoder::class);
        $method = $reflector->getMethod('putInSection');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sectionFiveDecoder, [$data]);

        $this->assertTrue($result);
    }

    public function testSuccessParse()
    {
        $reflector = new \ReflectionClass(SectionFiveDecoder::class);
        $method = $reflector->getMethod('parse');
        $method->setAccessible(true);
        $result = $method->invoke($this->sectionFiveDecoder);

        $this->assertInstanceOf(SectionInterface::class, $result);
    }
}