<?php

namespace Soandso\Synop\Fabrication;

/**
 * Class Validate contains base methods for validating meteorological groups
 */
class ValidateBase
{
    /**
     * @var string Weather report source code
     */
    private $report;

    /**
     * @var string[] Code figure of distinctive groups
     */
    private $distinctiveGroups = [
        'AAXX', 'BBXX', '222', 'ICE', '333', '444', '555', 'NIL',
    ];

    /**
     * Returns the processed weather report
     *
     * Clears the meteorological summary of extra spaces between groups
     *
     * @param string $report Weather report source code
     * @return string
     */
    public function clearDoubleSpacing(string $report): string
    {
        return preg_replace("/[  ]+/", " ", $report);
    }

    /**
     * Checks the meteorological weather report for an indication of the end of the report
     *
     * @param string $report Weather report source code
     * @return bool
     */
    public function isEndEqualSign(string $report): bool
    {
        $countSubmbolReport = iconv_strlen($report);
        $endSumbolPosition = stripos($report, "=");

        return ($countSubmbolReport - 1) == $endSumbolPosition;
    }

    /**
     * Checks the correctness of the meteorological report based on the number of characters in individual groups
     *
     * @param string $report Weather report source code
     * @return bool
     */
    public function isCountSymbol(string $report): bool
    {
        $this->report = str_replace('=', '', $report);
        $groups = explode(' ', $this->report);
        foreach ($groups as $group) {
            if (!$this->isGroup($group)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks the validity of a single group of meteorological reports based on the number of characters
     *
     * @param string $group Code figure of single weather report group
     * @return bool
     */
    public function isGroup(string $group): bool
    {
        if (iconv_strlen($group) == 5) {
            return true;
        } elseif ($this->isSpecificGroup($group)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if the weather report group is special
     *
     * @param string $group Code figure of single weather report group
     * @return bool
     */
    public function isSpecificGroup(string $group): bool
    {
        foreach ($this->distinctiveGroups as $groups) {
            if (strcasecmp($groups, $group) == 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks for a non-empty weather report
     *
     * @param string $report Weather report source code
     * @return bool
     */
    public function isNil(string $report): bool
    {
        $reportBlocks = explode(' ', $report);

        return strcasecmp(end($reportBlocks), 'NIL=') == 0;
    }
}
