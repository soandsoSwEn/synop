<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;

/**
 * Decodes the group code for section 4 of the weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionFourDecoder extends Decoder implements DecoderInterface
{
    /**
     * @var SectionInterface Current section of the weather report
     */
    private $section;

    /**
     * @var bool Synop identifier for weather report type
     */
    private $synopReport = null;

    /**
     * @var bool Ship identifier for weather report type
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
     * @return SectionInterface Decoded data for this section
     */
    public function parse(): SectionInterface
    {
        return $this->section;
    }

    /**
     * Adds group data to the weather section
     *
     * @param $data
     * @return bool
     */
    private function putInSection($data)
    {
        $this->section->setBody($data);

        return true;
    }

    public function getNCHHCt(RawReportInterface $rawReport)
    {
        $mountainStations = false;
        if ($this->synopReport) {
            $mountainWeatherStations = $this->block($rawReport->getReport());
            if (!$this->isGroup($mountainWeatherStations, 5)) {
                return null;
            }

            $mountainStations = true;
            $this->updateReport($mountainWeatherStations, $rawReport);
            return $this->putInSection($mountainWeatherStations) ? true : false;
        } else {
            //ship report
        }

        return $mountainStations ? true : null;
    }
}
