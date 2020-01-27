<?php

namespace Synop\Fabrication;

/**
 * This interface describes methods for working with a native weather report
 * presented as a string
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface RawReportInterface
{
    /**
     * Sets the current value of the weather report received
     * from an external service
     * 
     * @param string $report
     * @return void
     */
    public function setReport(string $report) : void;
    
    /**
     * Returns a weather report that is currently valid for processing
     * 
     * @return string
     */
    public function getReport() : string;
    
    /**
     * Updates the weather report at a specific stage of its processing
     * 
     * @param string $report
     * @return void
     */
    public function updateReport(string $report) : void;
}
