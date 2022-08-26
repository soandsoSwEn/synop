<?php

namespace Soandso\Synop\Tests\Decoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\SectionFourDecoder;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\SectionInterface;

class SectionFourDecoderTest extends TestCase
{
    private $sectionFourDecoder;

    protected function setUp(): void
    {
        $this->sectionFourDecoder = new SectionFourDecoder(new Section('Section Four'), true, false);
    }

    protected function tearDown(): void
    {
        unset($this->sectionFourDecoder);
        Mockery::close();
    }

    public function testIsSynopTypeReport()
    {
        $reflector = new \ReflectionClass(SectionFourDecoder::class);
        $property = $reflector->getProperty('synopReport');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionFourDecoder);

        $this->assertTrue($value);
    }

    public function testIsShipTypeReport()
    {
        $reflector = new \ReflectionClass(SectionFourDecoder::class);
        $property = $reflector->getProperty('shipReport');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionFourDecoder);

        $this->assertFalse($value);
    }

    public function testSuccessIsGroup()
    {
        $this->assertTrue($this->sectionFourDecoder->isGroup('92952', 5));
    }

    public function testErrorIsGroup()
    {
        $this->assertFalse($this->sectionFourDecoder->isGroup('ISE', 5));
    }

    public function testSuccessGetNCHHCt()
    {
        $report = '92952 555 10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '555 10178 3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $this->assertTrue($this->sectionFourDecoder->getNCHHCt($rawReport));
    }

    public function testNullGetNCHHCt()
    {
        $report = '9295 555 10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '555 10178 3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFourDecoder->getNCHHCt($rawReport));
    }

    public function testFalseGetNCHHCt()
    {
        $report = '444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $updatedReport = '555 10178 3/021 41022 60021 77182 92952=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertNull($this->sectionFourDecoder->getNCHHCt($rawReport));
    }

    public function testSuccessPutInSection()
    {
        $data = '555 10178 3/021 41022 60021 77182 92952=';
        $reflector = new \ReflectionClass(SectionFourDecoder::class);
        $method = $reflector->getMethod('putInSection');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sectionFourDecoder, [$data]);

        $this->assertTrue($result);
    }

    public function testSuccessParse()
    {
        $reflector = new \ReflectionClass(SectionFourDecoder::class);
        $method = $reflector->getMethod('parse');
        $method->setAccessible(true);
        $result = $method->invoke($this->sectionFourDecoder);

        $this->assertInstanceOf(SectionInterface::class, $result);
    }
}
