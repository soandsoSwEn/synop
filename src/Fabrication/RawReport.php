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
     * @var type string Weather report
     */
    private $report;
    
    /**
     * Sets the value of the weather report
     * @param string $report
     * @return void
     */
    public function setReport(string $report): void
    {
        $this->report = $report;
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
        $this->setReport($report);
    }
}
