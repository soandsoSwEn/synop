<?php

namespace Soandso\Synop\Tests;

use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\PartData;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Fabrication\RawReportInterface;
use Soandso\Synop\Fabrication\ValidateInterface;
use Soandso\Synop\Report;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Sheme\TypeGroup;

class ReportTest extends TestCase
{
    private $reportData = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

    private $report;

    protected function setUp(): void
    {
        $this->report = new Report($this->reportData);
    }

    protected function tearDown(): void
    {
        unset($this->report);
    }

    public function testSuccessSetReport()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->report->setReport($reportData);

        $reflector = new \ReflectionClass(Report::class);
        $propertyReportData = $reflector->getProperty('report');
        $propertyReportData->setAccessible(true);
        $propertyRawReport = $reflector->getProperty('rawReport');
        $propertyRawReport->setAccessible(true);

        $valueReportData = $propertyReportData->getValue($this->report);
        $valueRawReport = $propertyRawReport->getValue($this->report);

        $expected = [$reportData, true];

        $this->assertEquals($expected, [$valueReportData, $valueRawReport instanceof RawReportInterface]);
    }

    public function testNullSetReport()
    {
        $reportData = '';

        $this->expectException(Exception::class);

        $this->report->setReport($reportData);
    }

    public function testSuccessInitValidator()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->report->initValidator($reportData);

        $reflector = new \ReflectionClass(Report::class);
        $property = $reflector->getProperty('validate');
        $property->setAccessible(true);

        $value = $property->getValue($this->report);

        $this->assertInstanceOf(ValidateInterface::class, $value);
    }

    public function testExceptionInitValidator()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';

        $this->expectException(Exception::class);

        $this->report->initValidator($reportData);
    }

    public function testSuccessPrepareReport()
    {
        $reportData = 'AAXX  08181 33835    11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091  555 1/004=';

        $reflectorProperty = new \ReflectionProperty(RawReport::class, 'report');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report->getRawReport(), $reportData);

        $this->report->prepareReport();

        $reflector = new \ReflectionClass(RawReport::class);
        $property = $reflector->getProperty('report');
        $property->setAccessible(true);
        $value = $property->getValue($this->report->getRawReport());

        $expected = 'AAXX 08181 33835 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $value);
    }

    public function testSuccessValidate()
    {
        $this->assertTrue($this->report->validate());
    }

    public function testErrorValidate()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 28007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $report = new Report($reportData);
        $report->parse();

        $this->assertFalse($report->validate());
    }

    public function testExceptionValidate()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';

        $this->expectException(Exception::class);

        new Report($reportData);
    }

    public function testSuccessGetErrors()
    {
        $this->assertFalse($this->report->getErrors());
    }

    public function testErrorGetErrors()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 28007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $report = new Report($reportData);
        $report->parse();

        $expected = [0 => [
            'indicator_group' => '2SnTdTdTd',
            'description_indicator' => 'Sign of temperature',
            'code_figure' => 'Sn',
            'description_error' => 'Wrong sign of dew point temperature group 2SnTdTdTd - 8',
            ]
        ];

        $this->assertEquals($expected, $report->getErrors());
    }

    public function testErrorIsArrayGetErrors()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 28007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $report = new Report($reportData);
        $report->parse();

        $this->assertIsArray($report->getErrors());
    }

    public function testExceptionGetErrors()
    {
        $reflectorProperty = new \ReflectionProperty(Report::class, 'validate');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, null);

        $this->expectException(Exception::class);

        $this->report->getErrors();
    }

    public function testSuccessGetReportList()
    {
        $this->assertFalse($this->report->getReportList());
    }

    public function testErrorGetReportList()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 28007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $report = new Report($reportData);
        $report->parse();

        $expected = [0 => 'Wrong sign of dew point temperature group 2SnTdTdTd - 8'];

        $this->assertEquals($expected, $report->getReportList());
    }

    public function testErrorIsArrayGetReportList()
    {
        $reportData = 'AAXX 08181 33835 11583 83102 10039 28007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $report = new Report($reportData);
        $report->parse();

        $this->assertIsArray($report->getReportList());
    }

    public function testExceptionGetReportList()
    {
        $reflectorProperty = new \ReflectionProperty(Report::class, 'validate');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, null);

        $this->expectException(Exception::class);

        $this->report->getReportList();
    }

    public function testSuccessGetReport()
    {
        $this->assertEquals($this->reportData, $this->report->getReport());
    }

    public function testSuccessIsStringGetReport()
    {
        $this->assertIsString($this->report->getReport());
    }

    public function testSuccessGetRawReport()
    {
        $this->assertInstanceOf(RawReportInterface::class, $this->report->getRawReport());
    }

    public function testSuccessGetType()
    {
        $this->report->parse();

        $this->assertEquals('AAXX', $this->report->getType());
    }

    public function testSuccessIsStringGetType()
    {
        $this->report->parse();

        $this->assertIsString($this->report->getType());
    }

    public function testExceptionGetType()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStation')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->expectException(Exception::class);

        $this->report->getType();
    }

    public function testSuccessGetWmo()
    {
        $this->report->parse();

        $this->assertEquals('33837', $this->report->getWmo());
    }

    public function testSuccessIsStringGetWmo()
    {
        $this->report->parse();

        $this->assertIsString($this->report->getWmo());
    }

    public function testExceptionGetWmo()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationIndexReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->expectException(Exception::class);

        $this->report->getWmo();
    }

    public function testSuccessParse()
    {
        $this->report->parse();

        $reflector = new \ReflectionClass(Report::class);
        $property = $reflector->getProperty('rawBlocksData');
        $property->setAccessible(true);
        $value = $property->getValue($this->report);

        $this->assertInstanceOf(SectionInterface::class, $value);
    }

    public function testSuccessGetPipes()
    {
        $pipes = [
            'type',
            'ShipSign',
            'YYGGiw',
            'IIiii',
            '99LaLaLa',
            'QcL0L0L0L0',
            'irixhVV',
            'Nddff',
            '1SnTTT',
            '2SnTdTdTd',
            '3P0P0P0P0',
            '4PPPP',
            '5appp',
            '6RRRtr',
            '7wwW1W2',
            '8NhClCmCh',
            '9hh//',
            '222DsVs',
            '333',
            '444',
            '555',
        ];

        $reflector = new \ReflectionClass(Report::class);
        $method = $reflector->getMethod('getPipes');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->report, [$pipes]);

        $this->assertEquals($pipes, $result);
    }

    public function testSuccessIsArrayGetPipes()
    {
        $pipes = [
            'type',
            'ShipSign',
            'YYGGiw',
            'IIiii',
            '99LaLaLa',
            'QcL0L0L0L0',
            'irixhVV',
            'Nddff',
            '1SnTTT',
            '2SnTdTdTd',
            '3P0P0P0P0',
            '4PPPP',
            '5appp',
            '6RRRtr',
            '7wwW1W2',
            '8NhClCmCh',
            '9hh//',
            '222DsVs',
            '333',
            '444',
            '555',
        ];

        $reflector = new \ReflectionClass(Report::class);
        $method = $reflector->getMethod('getPipes');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->report, [$pipes]);

        $this->assertIsArray($result);
    }

    public function testSuccessCountPipesGetPipes()
    {
        $pipes = [
            'type',
            'ShipSign',
            'YYGGiw',
            'IIiii',
            '99LaLaLa',
            'QcL0L0L0L0',
            'irixhVV',
            'Nddff',
            '1SnTTT',
            '2SnTdTdTd',
            '3P0P0P0P0',
            '4PPPP',
            '5appp',
            '6RRRtr',
            '7wwW1W2',
            '8NhClCmCh',
            '9hh//',
            '222DsVs',
            '333',
            '444',
            '555',
        ];

        $reflector = new \ReflectionClass(Report::class);
        $method = $reflector->getMethod('getPipes');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->report, [$pipes]);

        $this->assertEquals(count($pipes), count($result));
    }

    public function testSuccessGetTypeStation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStation')->once()->andReturn('AAXX');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('AAXX', $this->report->getTypeStation());
    }

    public function testSuccessIsStringGetTypeStation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStation')->once()->andReturn('AAXX');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getTypeStation());
    }

    public function testNullGetTypeStation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStation')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getTypeStation());
    }

    public function testSuccessGetDay()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDayReport')->once()->andReturn('08');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('08', $this->report->getDay());
    }

    public function testSuccessIsStringGetDay()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDayReport')->once()->andReturn('08');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDay());
    }

    public function testNullGetDay()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDayReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDay());
    }

    public function testSuccessGetTime()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHourReport')->once()->andReturn('15');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('15', $this->report->getTime());
    }

    public function testSuccessIsStringGetTime()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHourReport')->once()->andReturn('15');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('15', $this->report->getTime());
    }

    public function testNullGetTime()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHourReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getTime());
    }

    public function testSuccessGetUnitWind()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getUnitWindReport')->once()->andReturn('m/s');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('m/s', $this->report->getUnitWind());
    }

    public function testSuccessIsStringGetUnitWind()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getUnitWindReport')->once()->andReturn('m/s');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getUnitWind());
    }

    public function testNullGetUnitWind()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getUnitWindReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getUnitWind());
    }

    public function testSuccessGetWindDetection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDetectionReport')->once()->andReturn('Instrumental');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Instrumental', $this->report->getWindDetection());
    }

    public function testSuccessIsStringGetWindDetection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDetectionReport')->once()->andReturn('Instrumental');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getWindDetection());
    }

    public function testNullGetWindDetection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDetectionReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getWindDetection());
    }

    public function testSuccessGetAreaNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAreaNumberReport')->once()->andReturn('33');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('33', $this->report->getAreaNumber());
    }

    public function testSuccessIsStringGetAreaNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAreaNumberReport')->once()->andReturn('33');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getAreaNumber());
    }

    public function testNullGetAreaNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAreaNumberReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAreaNumber());
    }

    public function testSuccessGetStationNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationNumberReport')->once()->andReturn('837');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('837', $this->report->getStationNumber());
    }

    public function testSuccessIsStringGetStationNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationNumberReport')->once()->andReturn('837');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getStationNumber());
    }

    public function testNullGetStationNumber()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationNumberReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStationNumber());
    }

    public function testSuccessGetStationIndex()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationIndexReport')->once()->andReturn('33837');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('33837', $this->report->getStationIndex());
    }

    public function testSuccessIsStringGetStationIndex()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationIndexReport')->once()->andReturn('33837');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getStationIndex());
    }

    public function testNullGetStationIndex()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationIndexReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStationIndex());
    }

    public function testSuccessGetInclusionPrecipitation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionPrecipitationReport')->once()->andReturn('Included in section 1');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Included in section 1', $this->report->getInclusionPrecipitation());
    }

    public function testSuccessIsStringGetInclusionPrecipitation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionPrecipitationReport')->once()->andReturn('Included in section 1');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getInclusionPrecipitation());
    }

    public function testNullGetInclusionPrecipitation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionPrecipitationReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getInclusionPrecipitation());
    }

    public function testSuccessGetInclusionWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionWeatherReport')->once()->andReturn('Included');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Included', $this->report->getInclusionWeather());
    }

    public function testSuccessIsStringGetInclusionWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionWeatherReport')->once()->andReturn('Included');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getInclusionWeather());
    }

    public function testNullGetInclusionWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getInclusionWeatherReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getInclusionWeather());
    }

    public function testSuccessGetTypeStationOperation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStationOperationReport')->once()->andReturn('Manned');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Manned', $this->report->getTypeStationOperation());
    }

    public function testSuccessIsStringGetTypeStationOperation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStationOperationReport')->once()->andReturn('Manned');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getTypeStationOperation());
    }

    public function testNullGetTypeStationOperation()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTypeStationOperationReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getTypeStationOperation());
    }

    public function testSuccessGetHeightLowCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudReport')->once()->andReturn('600-1000');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('600-1000', $this->report->getHeightLowCloud());
    }

    public function testSuccessIsStringGetHeightLowCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudReport')->once()->andReturn('600-1000');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getHeightLowCloud());
    }

    public function testNullGetHeightLowCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getHeightLowCloud());
    }

    public function testSuccessGetHeightLowCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudUnitReport')->once()->andReturn('m');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('m', $this->report->getHeightLowCloudUnit());
    }

    public function testSuccessIsStringGetHeightLowCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudUnitReport')->once()->andReturn('m');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getHeightLowCloudUnit());
    }

    public function testNullGetHeightLowCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightLowCloudUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getHeightLowCloudUnit());
    }

    public function testSuccessGetVisibility()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityReport')->once()->andReturn('45');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('45', $this->report->getVisibility());
    }

    public function testSuccessIsStringGetVisibility()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityReport')->once()->andReturn('45');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getVisibility());
    }

    public function testNullGetVisibility()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getVisibility());
    }

    public function testSuccessGetVisibilityUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityUnitReport')->once()->andReturn('km');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('km', $this->report->getVisibilityUnit());
    }

    public function testSuccessIsStringGetVisibilityUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityUnitReport')->once()->andReturn('km');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getVisibilityUnit());
    }

    public function testNullGetVisibilityUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getVisibilityUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getVisibilityUnit());
    }

    public function testSuccessGetTotalAmountCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTotalAmountCloudReport')->once()->andReturn('10');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('10', $this->report->getTotalAmountCloud());
    }

    public function testSuccessIsStringGetTotalAmountCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTotalAmountCloudReport')->once()->andReturn('10');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getTotalAmountCloud());
    }

    public function testNullGetTotalAmountCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getTotalAmountCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getTotalAmountCloud());
    }

    public function testSuccessGetWindDirection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionReport')->once()->andReturn('310');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('310', $this->report->getWindDirection());
    }

    public function testSuccessIsStringGetWindDirection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionReport')->once()->andReturn('310');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getWindDirection());
    }

    public function testNullGetWindDirection()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getWindDirection());
    }

    public function testSuccessGetWindDirectionUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionUnitReport')->once()->andReturn('degrees');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degrees', $this->report->getWindDirectionUnit());
    }

    public function testSuccessIsStringGetWindDirectionUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionUnitReport')->once()->andReturn('degrees');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getWindDirectionUnit());
    }

    public function testNullGetWindDirectionUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindDirectionUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getWindDirectionUnit());
    }

    public function testSuccessGetWindSpeed()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedReport')->once()->andReturn('2');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('2', $this->report->getWindSpeed());
    }

    public function testSuccessIsStringGetWindSpeed()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedReport')->once()->andReturn('2');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getWindSpeed());
    }

    public function testNullGetWindSpeed()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getWindSpeed());
    }

    public function testSuccessGetWindSpeedUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedUnitReport')->once()->andReturn('m/s');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('m/s', $this->report->getWindSpeedUnit());
    }

    public function testSuccessIsStringGetWindSpeedUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedUnitReport')->once()->andReturn('m/s');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getWindSpeedUnit());
    }

    public function testNullGetWindSpeedUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getWindSpeedUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getWindSpeedUnit());
    }

    public function testSuccessGetAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureReport')->once()->andReturn(3.9);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(3.9, $this->report->getAirTemperature());
    }

    public function testSuccessIsFloatGetAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureReport')->once()->andReturn(3.9);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat(3.9, $this->report->getAirTemperature());
    }

    public function testNullGetAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAirTemperature());
    }

    public function testSuccessGetAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degree C', $this->report->getAirTemperatureUnit());
    }

    public function testSuccessIsStringGetAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getAirTemperatureUnit());
    }

    public function testNullGetAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAirTemperatureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAirTemperatureUnit());
    }

    public function testSuccessGetDewPointTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureReport')->once()->andReturn(-0.7);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(-0.7, $this->report->getDewPointTemperature());
    }

    public function testSuccessIsFloatGetDewPointTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureReport')->once()->andReturn(-0.7);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getDewPointTemperature());
    }

    public function testNullGetDewPointTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDewPointTemperature());
    }

    public function testSuccessGetDewPointTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degree C', $this->report->getDewPointTemperatureUnit());
    }

    public function testSuccessIsStringGetDewPointTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDewPointTemperatureUnit());
    }

    public function testNullGetDewPointTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDewPointTemperatureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDewPointTemperatureUnit());
    }

    public function testSuccessGetStationLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureReport')->once()->andReturn(1004.9);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(1004.9, $this->report->getStationLevelPressure());
    }

    public function testSuccessIsFloatGetStationLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureReport')->once()->andReturn(1004.9);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getStationLevelPressure());
    }

    public function testNullGetStationLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStationLevelPressure());
    }

    public function testSuccessGetStationLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('hPa', $this->report->getStationLevelPressureUnit());
    }

    public function testSuccessIsStringGetStationLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getStationLevelPressureUnit());
    }

    public function testNullGetStationLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStationLevelPressureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStationLevelPressureUnit());
    }

    public function testSuccessGetSeaLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureReport')->once()->andReturn(1010.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(1010.1, $this->report->getSeaLevelPressure());
    }

    public function testSuccessIsFloatGetSeaLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureReport')->once()->andReturn(1010.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getSeaLevelPressure());
    }

    public function testNullGetSeaLevelPressure()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getSeaLevelPressure());
    }

    public function testSuccessGetSeaLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('hPa', $this->report->getSeaLevelPressureUnit());
    }

    public function testSuccessIsStringGetSeaLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getSeaLevelPressureUnit());
    }

    public function testNullGetSeaLevelPressureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getSeaLevelPressureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getSeaLevelPressureUnit());
    }

    public function testSuccessGetBaricTendency()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyReport')->once()->andReturn(3.5);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(3.5, $this->report->getBaricTendency());
    }

    public function testSuccessIsStringGetBaricTendency()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyReport')->once()->andReturn('3.5');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getBaricTendency());
    }

    public function testNullGetBaricTendency()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getBaricTendency());
    }

    public function testSuccessGetBaricTendencyUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('hPa', $this->report->getBaricTendencyUnit());
    }

    public function testSuccessIsStringGetBaricTendencyUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyUnitReport')->once()->andReturn('hPa');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getBaricTendencyUnit());
    }

    public function testNullGetBaricTendencyUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getBaricTendencyUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getBaricTendencyUnit());
    }

    public function testSuccessGetAmountRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountRainfallReport')->once()->andReturn(1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(1, $this->report->getAmountRainfall());
    }

    public function testNullGetAmountRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountRainfallReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAmountRainfall());
    }

    public function testSuccessGetAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountRainfallUnitReport')->once()->andReturn('mm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('mm', $this->report->getAmountRainfallUnit());
    }

    public function testSuccessIsStringGetAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountRainfallUnitReport')->once()->andReturn('mm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getAmountRainfallUnit());
    }

    public function testNullGetAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountRainfallUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAmountRainfallUnit());
    }

    public function testSuccessGetDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationPeriodRainfallReport')->once()->andReturn('At 0600 and 1800 GMT');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('At 0600 and 1800 GMT', $this->report->getDurationPeriodRainfall());
    }

    public function testSuccessIsStringGetDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationPeriodRainfallReport')->once()->andReturn('At 0600 and 1800 GMT');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDurationPeriodRainfall());
    }

    public function testNullGetDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationPeriodRainfallReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDurationPeriodRainfall());
    }

    public function testSuccessGetPresentWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPresentWeatherReport')->once()->andReturn('State of sky on the whole unchanged');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('State of sky on the whole unchanged', $this->report->getPresentWeather());
    }

    public function testSuccessIsStringGetPresentWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPresentWeatherReport')->once()->andReturn('State of sky on the whole unchanged');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getPresentWeather());
    }

    public function testNullGetPresentWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPresentWeatherReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getPresentWeather());
    }

    public function testSuccessGetPastWeather()
    {
        $this->report->parse();

        $pastWeather = [
            'W1' => 'Shower(s)', 'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period'
        ];

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPastWeatherReport')->once()->andReturn($pastWeather);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals($pastWeather, $this->report->getPastWeather());
    }

    public function testSuccessIsArrayGetPastWeather()
    {
        $this->report->parse();

        $pastWeather = [
            'W1' => 'Shower(s)', 'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period'
        ];

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPastWeatherReport')->once()->andReturn($pastWeather);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsArray($this->report->getPastWeather());
    }

    public function testNullGetPastWeather()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getPastWeatherReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getPastWeather());
    }

    public function testSuccessGetAmountLowOrMiddleCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountLowOrMiddleCloudReport')->once()->andReturn('2 eight of sky covered');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('2 eight of sky covered', $this->report->getAmountLowOrMiddleCloud());
    }

    public function testSuccessIsStringGetAmountLowOrMiddleCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountLowOrMiddleCloudReport')->once()->andReturn('2 eight of sky covered');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getAmountLowOrMiddleCloud());
    }

    public function testNullGetAmountLowOrMiddleCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountLowOrMiddleCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAmountLowOrMiddleCloud());
    }

    public function testSuccessGetFormLowCloud()
    {
        $this->report->parse();

        $formCloud = 'Stratocumulus not resulting from the spreading out of Cumulus';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormLowCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals($formCloud, $this->report->getFormLowCloud());
    }

    public function testSuccessIsStringGetFormLowCloud()
    {
        $this->report->parse();

        $formCloud = 'Stratocumulus not resulting from the spreading out of Cumulus';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormLowCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getFormLowCloud());
    }

    public function testNullGetFormLowCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormLowCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getFormLowCloud());
    }

    public function testSuccessGetFormMediumCloud()
    {
        $this->report->parse();

        $formCloud = 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormMediumCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals($formCloud, $this->report->getFormMediumCloud());
    }

    public function testSuccessIsStringGetFormMediumCloud()
    {
        $this->report->parse();

        $formCloud = 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormMediumCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getFormMediumCloud());
    }

    public function testNullGetFormMediumCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormMediumCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getFormMediumCloud());
    }

    public function testSuccessGetFormHighCloud()
    {
        $this->report->parse();

        $formCloud = 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormHighCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals($formCloud, $this->report->getFormHighCloud());
    }

    public function testSuccessIsStringGetFormHighCloud()
    {
        $this->report->parse();

        $formCloud = 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormHighCloudReport')->once()->andReturn($formCloud);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($formCloud, $this->report->getFormHighCloud());
    }

    public function testNullGetFormHighCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormHighCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getFormHighCloud());
    }

    public function testSuccessGetMaxAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureReport')->once()->andReturn(9.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(9.1, $this->report->getMaxAirTemperature());
    }

    public function testSuccessIsFloatGetMaxAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureReport')->once()->andReturn(9.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getMaxAirTemperature());
    }

    public function testNullGetMaxAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMaxAirTemperature());
    }

    public function testSuccessGetMaxAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degree C', $this->report->getMaxAirTemperatureUnit());
    }

    public function testSuccessIsStringGetMaxAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getMaxAirTemperatureUnit());
    }

    public function testNullGetMaxAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMaxAirTemperatureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMaxAirTemperatureUnit());
    }

    public function testSuccessGetMinAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureReport')->once()->andReturn(2.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(2.1, $this->report->getMinAirTemperature());
    }

    public function testSuccessIsFloatGetMinAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureReport')->once()->andReturn(2.1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getMinAirTemperature());
    }

    public function testNullGetMinAirTemperature()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMinAirTemperature());
    }

    public function testSuccessGetMinAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degree C', $this->report->getMinAirTemperatureUnit());
    }

    public function testSuccessIsStringGetMinAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureUnitReport')->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getMinAirTemperatureUnit());
    }

    public function testNullGetMinAirTemperatureUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinAirTemperatureUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMinAirTemperatureUnit());
    }

    public function testSuccessGetStateGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithoutSnowReport')->once()->andReturn('Surface of ground frozen');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Surface of ground frozen', $this->report->getStateGroundWithoutSnow());
    }

    public function testSuccessIsStringGetStateGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithoutSnowReport')->once()->andReturn('Surface of ground frozen');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getStateGroundWithoutSnow());
    }

    public function testNullGetStateGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithoutSnowReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStateGroundWithoutSnow());
    }

    public function testSuccessGetMinTemperatureOfGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowReport')
            ->once()->andReturn(-3);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(-3, $this->report->getMinTemperatureOfGroundWithoutSnow());
    }

    public function testSuccessIsIntGetMinTemperatureOfGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowReport')
            ->once()->andReturn(-3);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsInt($this->report->getMinTemperatureOfGroundWithoutSnow());
    }

    public function testNullGetMinTemperatureOfGroundWithoutSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowReport')
            ->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMinTemperatureOfGroundWithoutSnow());
    }

    public function testSuccessGetMinTemperatureOfGroundWithoutSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowUnitReport')
            ->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('degree C', $this->report->getMinTemperatureOfGroundWithoutSnowUnit());
    }

    public function testSuccessIsStringGetMinTemperatureOfGroundWithoutSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowUnitReport')
            ->once()->andReturn('degree C');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getMinTemperatureOfGroundWithoutSnowUnit());
    }

    public function testNullGetMinTemperatureOfGroundWithoutSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getMinTemperatureOfGroundWithoutSnowUnitReport')
            ->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getMinTemperatureOfGroundWithoutSnowUnit());
    }

    public function testSuccessGetStateGroundWithSnow()
    {
        $this->report->parse();

        $stateGround = 'Loose dry snow covering less than one-half of the ground';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithSnowReport')
            ->once()->andReturn($stateGround);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals($stateGround, $this->report->getStateGroundWithSnow());
    }

    public function testSuccessIsStringGetStateGroundWithSnow()
    {
        $this->report->parse();

        $stateGround = 'Loose dry snow covering less than one-half of the ground';

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithSnowReport')
            ->once()->andReturn($stateGround);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getStateGroundWithSnow());
    }

    public function testNullGetStateGroundWithSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getStateGroundWithSnowReport')
            ->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getStateGroundWithSnow());
    }

    public function testSuccessGetDepthSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowReport')->once()->andReturn('Less than 0.5 cm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Less than 0.5 cm', $this->report->getDepthSnow());
    }

    public function testSuccessIsStringGetDepthSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowReport')->once()->andReturn('Less than 0.5 cm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDepthSnow());
    }

    public function testNullGetDepthSnow()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDepthSnow());
    }

    public function testSuccessGetDepthSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowUnitReport')->once()->andReturn('cm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('cm', $this->report->getDepthSnowUnit());
    }

    public function testSuccessIsStringGetDepthSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowUnitReport')->once()->andReturn('cm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDepthSnowUnit());
    }

    public function testNullGetDepthSnowUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDepthSnowUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDepthSnowUnit());
    }

    public function testSuccessGetDurationSunshine()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineReport')->once()->andReturn(8);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(8, $this->report->getDurationSunshine());
    }

    public function testSuccessIsFloatGetDurationSunshine()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineReport')->once()->andReturn(8);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsFloat($this->report->getDurationSunshine());
    }

    public function testNullGetDurationSunshine()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDurationSunshine());
    }

    public function testSuccessGetDurationSunshineUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineUnitReport')->once()->andReturn('hour');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('hour', $this->report->getDurationSunshineUnit());
    }

    public function testSuccessIsStringGetDurationSunshineUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineUnitReport')->once()->andReturn('hour');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getDurationSunshineUnit());
    }

    public function testNullGetDurationSunshineUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getDurationSunshineUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getDurationSunshineUnit());
    }

    public function testSuccessGetRegionalExchangeAmountRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallReport')->once()->andReturn(1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(1, $this->report->getRegionalExchangeAmountRainfall());
    }

    public function testSuccessIsIntGetRegionalExchangeAmountRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallReport')->once()->andReturn(1);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsInt($this->report->getRegionalExchangeAmountRainfall());
    }

    public function testNullGetRegionalExchangeAmountRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getRegionalExchangeAmountRainfall());
    }

    public function testSuccessGetRegionalExchangeAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallUnitReport')->once()->andReturn('mm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('mm', $this->report->getRegionalExchangeAmountRainfallUnit());
    }

    public function testSuccessIsStringGetRegionalExchangeAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallUnitReport')->once()->andReturn('mm');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getRegionalExchangeAmountRainfallUnit());
    }

    public function testNullGetRegionalExchangeAmountRainfallUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeAmountRainfallUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getRegionalExchangeAmountRainfallUnit());
    }

    public function testSuccessGetRegionalExchangeDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeDurationPeriodRainfallReport')
            ->once()->andReturn('At 0001 and 1200 GMT');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('At 0001 and 1200 GMT', $this->report->getRegionalExchangeDurationPeriodRainfall());
    }

    public function testSuccessIsStringGetRegionalExchangeDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeDurationPeriodRainfallReport')
            ->once()->andReturn('At 0001 and 1200 GMT');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getRegionalExchangeDurationPeriodRainfall());
    }

    public function testNullGetRegionalExchangeDurationPeriodRainfall()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getRegionalExchangeDurationPeriodRainfallReport')
            ->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getRegionalExchangeDurationPeriodRainfall());
    }

    public function testSuccessGetAmountIndividualCloudLayer()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountIndividualCloudLayerReport')
            ->once()->andReturn('Sky completely covered');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Sky completely covered', $this->report->getAmountIndividualCloudLayer());
    }

    public function testSuccessIsStringGetAmountIndividualCloudLayer()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountIndividualCloudLayerReport')
            ->once()->andReturn('Sky completely covered');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getAmountIndividualCloudLayer());
    }

    public function testNullGetAmountIndividualCloudLayer()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getAmountIndividualCloudLayerReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getAmountIndividualCloudLayer());
    }

    public function testSuccessGetFormCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormClodReport')->once()->andReturn('Nimbostratus (Ns)');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('Nimbostratus (Ns)', $this->report->getFormCloud());
    }

    public function testSuccessIsStringGetFormCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormClodReport')->once()->andReturn('Nimbostratus (Ns)');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getFormCloud());
    }

    public function testNullGetFormCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getFormClodReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getFormCloud());
    }

    public function testSuccessGetHeightCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightCloudReport')->once()->andReturn(540);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals(540, $this->report->getHeightCloud());
    }

    public function testNullGetHeightCloud()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightCloudReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getHeightCloud());
    }

    public function testSuccessGetHeightCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightCloudUnitReport')->once()->andReturn('m');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertEquals('m', $this->report->getHeightCloudUnit());
    }

    public function testSuccessIsStringGetHeightCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightCloudUnitReport')->once()->andReturn('m');

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertIsString($this->report->getHeightCloudUnit());
    }

    public function testNullGetHeightCloudUnit()
    {
        $this->report->parse();

        $partData = Mockery::mock(PartData::class);
        $partData->shouldReceive('getHeightCloudUnitReport')->once()->andReturn(null);

        $reflectorProperty = new \ReflectionProperty(Report::class, 'partData');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->report, $partData);

        $this->assertNull($this->report->getHeightCloudUnit());
    }
}
