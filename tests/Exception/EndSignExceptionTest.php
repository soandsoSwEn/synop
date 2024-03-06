<?php

namespace Exception;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Exception\EndSignException;

class EndSignExceptionTest extends TestCase
{
    public function testSuccessGetReport()
    {
        $report = 'AAXX 27124 78714 12560 60000 10195 20194 84520 333 10297 20195 390/// 56000 70049 84624 83457';

        $endSignException = new EndSignException($report);

        $this->assertEquals($report, $endSignException->getReport());
    }

    public function testSuccessIsStringGetReport()
    {
        $report = 'AAXX 27124 78714 12560 60000 10195 20194 84520 333 10297 20195 390/// 56000 70049 84624 83457';

        $endSignException = new EndSignException($report);

        $this->assertIsString($endSignException->getReport());
    }
}
