<?php

namespace Synop;

use Synop\ReportInterface;

/**
 * Weather report initial processing
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Report implements ReportInterface
{
    public function setReport(string $report): void
    {
        //
    }
    
    public function validate()
    {
        //
    }
    
    public function getType(): string
    {
        //
    }
    
    public function getWmo() : string
    {
        //
    }

    public function parse(): object
    {
        //
    }

}
