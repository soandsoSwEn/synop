<?php

namespace Soandso\Synop\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Exception\EmptyReportException;

class EmptyReportExceptionTest extends TestCase
{
    public function testSuccessGetReport()
    {
        $report = 'AAXX 24124 40272 NIL=';
        $message = 'Weather report is empty';

        $emptyReportException = new EmptyReportException($report, $message);

        $this->assertEquals($report, $emptyReportException->getReport());
    }

    public function testSuccessIsStringGetReport()
    {
        $report = 'AAXX 24124 40272 NIL=';
        $message = 'Weather report is empty';

        $emptyReportException = new EmptyReportException($report, $message);

        $this->assertIsString($emptyReportException->getReport());
    }
}