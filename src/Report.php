<?php

namespace Synop;

use Synop\ReportInterface;
use Exception;
use Synop\Fabrication\Validate;
use Synop\Fabrication\RawReport;
use Synop\Decoder\GeneralDecoder;
use Synop\Process\Pipeline;

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
            $this->raw_report = new RawReport($report);
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
    
    public function getRawReport() : Fabrication\RawReportInterface
    {
        return $this->raw_report;
    }

    public function getType(): string
    {
        $decoder = new GeneralDecoder();
    }
    
    public function getWmo() : string
    {
        //
    }

    public function parse()
    {
        $pipes = $this->getPipes();
        
        $pipeline = new Pipeline();
        $pipeline->pipe($pipes);
        $decoder = new GeneralDecoder();
        $blocks =  $pipeline->process($this->raw_report, $decoder); var_dump($blocks);
    }
    
    private function getPipes() : array
    {
        return [
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
            '0SnTwTwTw',
            '1PwaPwaHwaHwa',
            '2PwPwHwHw',
            '3dw1dw1dw2dw2',
            '4Pw1Pw1Hw1Hw1',
            '5Pw2Pw2Hw2Hw2',
            '6IsEsEsPs',
            'ISE',
            '333',
            '444',
            '555',
        ];
    }

}
