<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Fabrication\RawReportInterface;

/**
 * Base class for working with decoding weather report groups
 *
 * @package Synop\Decoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Decoder
{
    /** Title for Section 2 of weather report */
    const SECTION_TWO = 'Section Two';

    /** Title for Section 3 of weather report */
    const SECTION_THREE = 'Section Three';

    /** Title for Section 4 of weather report */
    const SECTION_FOUR = 'Section Four';

    /** Title for Section 5 of weather report */
    const SECTION_FIVE = 'Section Five';

    /**
     * Gets the closest block of code figure for a weather report
     * @param string $report_data The part of the weather report that is relevant for post-processing
     * @return string
     */
    public function block(string $report_data) : string
    {
        return strstr($report_data, ' ', true);
    }

    /**
     * Get a block code figure of weather report excluding the end report code figure
     * @param string $report_data
     * @return string
     */
    public function endBlock(string $report_data) : string
    {
        return strstr($report_data, '=', true);
    }

    /**
     * Refreshes the current state of the weather report
     * @param string $group Processed group code figure of weather report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     */
    public function updateReport(string $group, RawReportInterface $raw_report) : void
    {
        $report = str_replace($group . ' ', '', $raw_report->getReport());
        $raw_report->updateReport($report);
    }
}
