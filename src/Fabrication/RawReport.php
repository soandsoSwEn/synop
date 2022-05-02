<?php

namespace Synop\Fabrication;

use Synop\Fabrication\RawReportInterface;

/**
 * RawReport provides access and manipulation of the native weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class RawReport implements RawReportInterface
{
    /**
     * @var string Weather report
     */
    private $report;
    
    public function __construct(string $report)
    {
        $this->setReport($report);
    }

    /**
     * Sets the value of the weather report
     * @param string $report
     * @return void
     */
    public function setReport(string $report): void
    {
        $this->report = $this->cleanReport($report);
    }
    
    /**
     * Returns the value of the weather report
     * @return string
     */
    public function getReport(): string
    {
        return $this->report;
    }
    
    /**
     * Updates weather report value
     * @param string $report
     * @return void
     */
    public function updateReport(string $report): void
    {
        $this->report = $report;
    }
    
    /**
     * Clears meteorological data from random errors
     * @param string $report
     * @return string
     */
    public function cleanReport(string $report) : string
    {
        return $this->clearDoubleSpacing($report);
    }
    
    /**
     * Removes double spaces in the text of the weather report
     * @param string $report
     * @return string
     */
    public function clearDoubleSpacing(string $report) : string
    {
        return preg_replace("/[  ]+/", " ", $report);
    }
}
