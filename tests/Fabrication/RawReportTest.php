<?php

namespace Soandso\Synop\Tests\Fabrication;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\RawReport;

class RawReportTest extends TestCase
{
    private $rawReport;

    protected function setUp(): void
    {
        $this->rawReport = new RawReport('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=');
    }

    protected function tearDown(): void
    {
        unset($this->rawReport);
    }

    public function testSuccessSetReport()
    {
        $this->rawReport->setReport('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 222// 03012 ICE 42994 333 10091 21021 32103 45997 55080 444 92952 555 1/004=');

        $reflector = new \ReflectionClass(RawReport::class);
        $property = $reflector->getProperty('report');
        $property->setAccessible(true);
        $value = $property->getValue($this->rawReport);

        $this->assertEquals('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 222// 03012 ICE 42994 333 10091 21021 32103 45997 55080 444 92952 555 1/004=', $value);
    }

    public function testSuccessGetReport()
    {
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $this->rawReport->getReport());
    }

    public function testSuccessIsStringGetReport()
    {
        $this->assertIsString($this->rawReport->getReport());
    }

    public function testSuccessUpdateReport()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->rawReport->updateReport($report);

        $reflector = new \ReflectionClass(RawReport::class);
        $property = $reflector->getProperty('report');
        $property->setAccessible(true);
        $value = $property->getValue($this->rawReport);

        $this->assertEquals($report, $value);
    }

    public function testSuccessCleanReport()
    {
        $report = 'AAXX  07181 33837 11583 83102 10039 21007   30049 40101 52035 60012  70282 8255/ 333 10091 555 1/004=';
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $this->rawReport->cleanReport($report));
    }

    public function testSuccessIsStringCleanReport()
    {
        $report = 'AAXX  07181 33837 11583 83102 10039 21007   30049 40101 52035 60012  70282 8255/ 333 10091 555 1/004=';

        $this->assertIsString($this->rawReport->cleanReport($report));
    }

    public function testSuccessClearDoubleSpacing()
    {
        $report = 'AAXX  07181 33837 11583 83102 10039 21007   30049 40101 52035 60012  70282 8255/ 333 10091 555 1/004=';
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $this->rawReport->clearDoubleSpacing($report));
    }

    public function testSuccessIsStringClearDoubleSpacing()
    {
        $report = 'AAXX  07181 33837 11583 83102 10039 21007   30049 40101 52035 60012  70282 8255/ 333 10091 555 1/004=';

        $this->assertIsString($this->rawReport->clearDoubleSpacing($report));
    }
}
