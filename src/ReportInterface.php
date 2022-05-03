<?php

namespace Soandso\Synop;

/**
 * This interface describes basic operations with the weather report 
 * AAXX/BBXX (Synop/Ship)
 * 
 * @see https://www.wmo.int/pages/prog/www/WMOCodes/WMO306_vI1/Publications/2016update/WMO306_vI1_en_2011UP2016.pdf Meteorological code documentation
 * @see http://www.met.wur.nl/education/atmospract/unit1/plot%20manual.pdf Synop code plot in map
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ReportInterface
{
    /**
     * Sets the initial value of the weather report
     *
     * @param string $report Meteorological weather report source code
     */
    public function setReport(string $report) : void;

    /**
     * Validates the original meteorological weather report
     *
     * @return bool
     */
    public function validate() : bool;

    /**
     * Get a type of weather station (Synop or Ship)
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Get WMO station index
     *
     * @return string
     */
    public function getWmo() : string;

    /**
     * Starts the decoding process for the meteorological report
     *
     * @return void
     */
    public function parse() : void;
}
