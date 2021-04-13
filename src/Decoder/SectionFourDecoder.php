<?php

namespace Synop\Decoder;

use Synop\Decoder\Decoder;
use Synop\Sheme\SectionInterface;
use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionFourDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionFourDecoder extends Decoder implements DecoderInterface
{
    private $section;
    
    private $synop_report = null;
    
    private $ship_report = null;
    
    public function __construct(SectionInterface $section_title, bool $synop, bool $ship)
    {
        $this->section = $section_title;
        $this->synop_report = $synop;
        $this->ship_report = $ship;
    }

    public function isGroup(string $codeFigure, int $size) : bool
    {
        return mb_strlen($codeFigure) === $size;
    }
    
    public function parse(): SectionInterface
    {
        return $this->section;
    }
    
    private function putInSection($data)
    {
        if($this->section->setBody($data)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getNCHHCt(RawReportInterface $raw_report)
    {
        $mountain_stations = false;
        if($this->synop_report) {
            $mountain_weather_stations = $this->block($raw_report->getReport());
            if (!$this->isGroup($mountain_weather_stations, 5)) {
                return null;
            }

            $mountain_stations = true;
            $this->updateReport($mountain_weather_stations, $raw_report);
            return $this->putInSection($mountain_weather_stations) ? true : false;
        } else {
            //ship report
        }

        return $mountain_stations ? true : null;
    }
}
