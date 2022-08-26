<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;

/**
 * The class contains methods for decoding groups of Section 5 of the meteorological weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionFiveDecoder extends Decoder implements DecoderInterface
{
    /**
     * @var SectionInterface Current section of the weather report
     */
    private $section;

    /**
     * @var bool Whether the weather report is a SYNOP type
     */
    private $synopReport = null;

    /**
     * @var bool Whether the weather report is a SHIP type
     */
    private $shipReport = null;

    public function __construct(SectionInterface $sectionTitle, bool $synop, bool $ship)
    {
        $this->section = $sectionTitle;
        $this->synopReport = $synop;
        $this->shipReport = $ship;
    }

    /**
     * Returns the result of checking if the group of the code matches the group of the weather report
     *
     * @param string $codeFigure Code figure of single group of the section
     * @param int $size Size (number of symbol) of single group of the section
     * @return bool
     */
    public function isGroup(string $codeFigure, int $size): bool
    {
        return mb_strlen($codeFigure) === $size;
    }

    /**
     * Returns the Section object for the fifth section of the meteorological weather report
     *
     * @return SectionInterface
     */
    public function parse(): SectionInterface
    {
        return $this->section;
    }

    /**
     * Adds group data to the weather section
     *
     * @param $data mixed Group data
     * @return bool
     */
    private function putInSection($data)
    {
        $this->section->setBody($data);

        return true;
    }

    public function get1SnT24T24T24(RawReportInterface $rawReport)
    {
        $averageTemperature = false;
        if ($this->synopReport) {
            $averageTemperature = $this->block($rawReport->getReport());
            if (!$this->isGroup($averageTemperature, 5)) {
                return null;
            }

            $distinguishing_digit = substr($averageTemperature, 0, 1);
            if (strcmp($distinguishing_digit, '1') == 0) {
                $average_temperature = true;
            }
        } else {
            //ship report
        }
        if ($average_temperature) {
            $this->updateReport($averageTemperature, $rawReport);
            return $this->putInSection($averageTemperature) ? true : false;
        } else {
            return null;
        }
    }

    public function get3SnTgTg(RawReportInterface $rawReport)
    {
        $soilTemperature = false;
        if ($this->synopReport) {
            $minimumSoilTemperature = $this->block($rawReport->getReport());
            if (!$this->isGroup($minimumSoilTemperature, 5)) {
                return null;
            }

            $distinctiveCharacter = substr($minimumSoilTemperature, 0, 2);
            if (strcmp($distinctiveCharacter, '3/') == 0) {
                $soilTemperature = true;
            }
        } else {
            //ship report
        }
        if ($soilTemperature) {
            $this->updateReport($minimumSoilTemperature, $rawReport);
            return $this->putInSection($minimumSoilTemperature) ? true : false;
        } else {
            return null;
        }
    }

    public function get4Esss(RawReportInterface $rawReport)
    {
        $snow = false;
        if ($this->synopReport) {
            $snowCover = $this->block($rawReport->getReport());
            if (!$this->isGroup($snowCover, 5)) {
                return null;
            }

            $distinguishingDigit = substr($snowCover, 0, 1);
            if (strcmp($distinguishingDigit, '4') == 0) {
                $snow = true;
            }
        } else {
            //ship report
        }
        if ($snow) {
            $this->updateReport($snowCover, $rawReport);
            return $this->putInSection($snowCover) ? true : false;
        } else {
            return null;
        }
    }

    public function get6RRRtr(RawReportInterface $rawReport)
    {
        $precipitation = false;
        if ($this->synopReport) {
            $precipitationGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($precipitationGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($precipitationGroup, 0, 1);
            if (strcmp($distinguishingDigit, '6') == 0) {
                $precipitation = true;
            }
        } else {
            //ship report
        }
        if ($precipitation) {
            $this->updateReport($precipitationGroup, $rawReport);
            return $this->putInSection($precipitationGroup) ? true : false;
        } else {
            return null;
        }
    }

    public function get7R24R24R24E(RawReportInterface $rawReport)
    {
        $precipitation = false;
        if ($this->synopReport) {
            $precipitationDay = $this->block($rawReport->getReport());
            if (!$this->isGroup($precipitationDay, 5)) {
                return null;
            }

            $distinguishing_digit = substr($precipitationDay, 0, 1);
            if (strcmp($distinguishing_digit, '7') == 0) {
                $precipitation = true;
            }
        } else {
            //ship report
        }

        if ($precipitation) {
            $this->updateReport($precipitationDay, $rawReport);
            return $this->putInSection($precipitationDay) ? true : false;
        } else {
            return null;
        }
    }

    public function get9SpSpspsp(RawReportInterface $rawReport)
    {
        $weather = false;
        if ($this->synopReport) {
            $weatherGroup = $this->endBlock($rawReport->getReport());
            if (!$this->isGroup($weatherGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($weatherGroup, 0, 1);
            if (strcmp($distinguishingDigit, '9') == 0) {
                $weather = true;
            }
        } else {
            //ship report
        }

        if ($weather) {
            $this->updateReport($weatherGroup, $rawReport);
            return $this->putInSection($weatherGroup) ? true : false;
        } else {
            return null;
        }
    }
}
