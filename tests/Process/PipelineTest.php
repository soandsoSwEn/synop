<?php

namespace Soandso\Synop\Tests\Process;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\SectionThreeDecoder;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Process\Pipeline;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\SectionInterface;

class PipelineTest extends TestCase
{
    private $pipeline;

    protected function setUp(): void
    {
        $this->pipeline = new Pipeline();
    }

    protected function tearDown(): void
    {
        unset($this->pipeline);
        Mockery::close();
    }

    public function testSuccessPipe()
    {
        $pipes = [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];

        $this->pipeline->pipe($pipes);

        $reflector = new \ReflectionClass(Pipeline::class);
        $property = $reflector->getProperty('pipes');
        $property->setAccessible(true);
        $value = $property->getValue($this->pipeline);

        $this->assertEquals($value, $pipes);
    }

    public function testErrorPipe()
    {
        $pipes = [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];

        $actual = [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];

        $this->pipeline->pipe($pipes);

        $reflector = new \ReflectionClass(Pipeline::class);
        $property = $reflector->getProperty('pipes');
        $property->setAccessible(true);
        $value = $property->getValue($this->pipeline);

        $this->assertNotEquals($value, $actual);
    }

    public function testSuccessProcess()
    {
        $pipesValue = [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];

        $reflectionPipe = new \ReflectionProperty(Pipeline::class, 'pipes');
        $reflectionPipe->setAccessible(true);
        $reflectionPipe->setValue($this->pipeline, $pipesValue);

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $decoder = Mockery::mock(SectionThreeDecoder::class);
        $decoder->shouldReceive('parse')->once()->andReturn(new Section('Section Three'));
        $decoder->shouldReceive('get1SnTxTxTx')->once();
        $decoder->shouldReceive('get2SnTnTnTn')->once();
        $decoder->shouldReceive('get3ESnTgTg')->once();
        $decoder->shouldReceive('get4Esss')->once();
        $decoder->shouldReceive('get55SSS')->once();
        $decoder->shouldReceive('get6RRRtr')->once();
        $decoder->shouldReceive('get8NsChshs')->once();
        $decoder->shouldReceive('get9SpSpspsp')->once();

        $reflectionRawReport = new \ReflectionClass(SectionThreeDecoder::class);
        $rawReportSectionTitleProperty = $reflectionRawReport->getProperty('section');
        $rawReportSectionTitleProperty->setAccessible(true);
        $rawReportSectionTitleProperty->setValue($decoder, new Section('Section Three'));
        $rawReportSynopProperty = $reflectionRawReport->getProperty('synop_report');
        $rawReportSynopProperty->setAccessible(true);
        $rawReportSynopProperty->setValue($decoder, true);
        $rawReportShipProperty = $reflectionRawReport->getProperty('ship_report');
        $rawReportShipProperty->setAccessible(true);
        $rawReportShipProperty->setValue($decoder, false);
        $rawReportUnitProperty = $reflectionRawReport->getProperty('unit');
        $rawReportUnitProperty->setAccessible(true);
        $rawReportUnitProperty->setValue($decoder, new Unit());

        $this->assertInstanceOf(SectionInterface::class, $this->pipeline->process($rawReport, $decoder, $validate));
    }

    public function testSuccessStep()
    {
        $pipesValue = [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];

        $reflectionPipe = new \ReflectionProperty(Pipeline::class, 'pipes');
        $reflectionPipe->setAccessible(true);
        $reflectionPipe->setValue($this->pipeline, $pipesValue);

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $decoder = Mockery::mock(SectionThreeDecoder::class);
        $decoder->shouldReceive('get1SnTxTxTx')->once();
        $decoder->shouldReceive('get2SnTnTnTn')->once();
        $decoder->shouldReceive('get3ESnTgTg')->once();
        $decoder->shouldReceive('get4Esss')->once();
        $decoder->shouldReceive('get55SSS')->once();
        $decoder->shouldReceive('get6RRRtr')->once();
        $decoder->shouldReceive('get8NsChshs')->once();
        $decoder->shouldReceive('get9SpSpspsp')->once();

        $reflectionRawReport = new \ReflectionClass(SectionThreeDecoder::class);
        $rawReportSectionTitleProperty = $reflectionRawReport->getProperty('section');
        $rawReportSectionTitleProperty->setAccessible(true);
        $rawReportSectionTitleProperty->setValue($decoder, new Section('Section Three'));
        $rawReportSynopProperty = $reflectionRawReport->getProperty('synop_report');
        $rawReportSynopProperty->setAccessible(true);
        $rawReportSynopProperty->setValue($decoder, true);
        $rawReportShipProperty = $reflectionRawReport->getProperty('ship_report');
        $rawReportShipProperty->setAccessible(true);
        $rawReportShipProperty->setValue($decoder, false);
        $rawReportUnitProperty = $reflectionRawReport->getProperty('unit');
        $rawReportUnitProperty->setAccessible(true);
        $rawReportUnitProperty->setValue($decoder, new Unit());

        $reflectionPipeline = new \ReflectionClass(Pipeline::class);
        $method = $reflectionPipeline->getMethod('step');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->pipeline, [$rawReport, $decoder, $validate]);

        $this->assertFalse($result);
    }
}
