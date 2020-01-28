<?php

namespace Synop;

use Synop\ReportInterface;
use Exception;
use Synop\Fabrication\Validate;
use Synop\Fabrication\RawReport;
use Synop\Decoder\GeneralDecoder;

/**
 * Weather report initial processing
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Report implements ReportInterface
{
    /**
     * @var type sring
     */
    private $report;
    
    /**
     * @var type \Synop\Fabrication\RawReport
     */
    private $raw_report;


    public function __construct(string $report)
    {
        $this->setReport($report);
    }

    public function setReport(string $report): void
    {
        if (!empty($report)) {
            $this->report = $report;
            $this->raw_report = RawReport($report);
        } else {
            throw new Exception('Weather report cannot be an empty string!');
        }
    }
    
    public function validate() : bool
    {
        if(!$this->report) {
            throw new Exception('Meteorological weather report not defined!');
        }
        $validator = new Validate($this->report);
        return $validator->isValid();
    }
    
    public function getReport() : string
    {
        return $this->report;
    }

    public function getType(): string
    {
        $decoder = new GeneralDecoder();
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
