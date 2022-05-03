<?php

namespace Soandso\Synop\Sheme;

/**
 * SectionInterface an interface that should be implemented by a class that works with sections of the weather report
 *
 * The code form is divided into a number of sections as follows:
 * - Section 0.
 *   Data for reporting identification (type, ship’s call sign/buoy identifier, date, time, location)
 *   and units of wind speed used
 *
 * - General Section (Section 1)
 *   Data for global exchange which are common to the SYNOP, SHIP and SYNOP MOBIL code forms
 *
 * - Section 2
 *   Maritime data for global exchange pertaining to a sea, or to a coastal station
 *
 * - Section 3
 *   Data for regional exchange
 *
 * - Section 4
 *   Data for national use for clouds with base below station level, included by national decision
 *
 * - Section 5
 *   Data for national use
 *
 * Source -  Manual on Codes. International Codes. Volume I.1. Annex II to the WMO Technical Regulations.
 * Part A – Alphanumeric Codes, art 24
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface SectionInterface
{
    /**
     * Sets the title of the section of the meteorological report
     *
     * @param string $title Ешду of ф section of the meteorological report
     */
    public function setTitle(string $title) : void;

    /**
     * Adds a data for the section of the meteorological report
     *
     * @param $data array|string The data contained in the section "All sections" or in a specific section
     */
    public function setBody($data) : void;

    /**
     * Returns title of the section of the meteorological report
     *
     * @return string
     */
    public function getTile() : string;

    /**
     * Returns the data for all sections of the meteorological report
     *
     * @return array
     */
    public function getBody() : array;

    /**
     * Returns data for a section by its title
     *
     * @param string $titleSection Title of section of the meteorological report
     * @return mixed
     */
    public function getBodyByTitle(string $titleSection);
}
