<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;

/**
 * Decodes the group code for section 3 of the weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionTwoDecoder extends Decoder implements DecoderInterface
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
     * @param int $size Weather group size
     * @return bool
     */
    public function isGroup(string $codeFigure, int $size): bool
    {
        return mb_strlen($codeFigure) === $size;
    }

    /**
     * Returns decoded data for this section of the weather report
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
     * @param $data mixed Group data
     * @return bool
     */
    private function putInSection($data)
    {
        $this->section->setBody($data);

        return true;
    }
    
    public function get222DsVs(RawReportInterface $rawReport)
    {
        $sectionTwo = false;
        if ($this->shipReport) {
            $sectionTwoGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($sectionTwoGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($sectionTwoGroup, 0, 3);
            if (strcmp($distinguishingDigit, '222') == 0) {
                $sectionTwo = true;
            }
        } else {
            //synop report
        }

        if ($sectionTwo) {
            $this->updateReport($sectionTwoGroup, $rawReport);
            return $this->putInSection($sectionTwoGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get0SnTwTwTw(RawReportInterface $rawReport)
    {
        $seaTemperature = true;
        if ($this->shipReport) {
            $seaTemperatureGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($seaTemperatureGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($seaTemperatureGroup, 0, 1);
            if (strcmp($distinguishingDigit, '0') == 0) {
                $seaTemperature = true;
            }
        } else {
            //synop report
        }

        if ($seaTemperature) {
            $this->updateReport($seaTemperatureGroup, $rawReport);
            return $this->putInSection($seaTemperatureGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get1PwaPwaHwaHwa(RawReportInterface $rawReport)
    {
        $seaWave = false;
        if ($this->shipReport) {
            $seaWaveGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($seaWaveGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($seaWaveGroup, 0, 1);
            if (strcmp($distinguishingDigit, '1') == 0) {
                $seaWave = true;
            }
        } else {
            //synop report
        }
        if ($seaWave) {
            $this->updateReport($seaWaveGroup, $rawReport);
            return $this->putInSection($seaWaveGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get2PwPwHwHw(RawReportInterface $rawReport)
    {
        $windWaves = false;
        if ($this->shipReport) {
            $windWavesGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($windWavesGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($windWavesGroup, 0, 1);
            if (strcmp($distinguishingDigit, '2') == 0) {
                $windWaves = true;
            }
        } else {
            //synop report
        }

        if ($windWaves) {
            $this->updateReport($windWavesGroup, $rawReport);
            return $this->putInSection($windWavesGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get3dw1dw1dw2dw2(RawReportInterface $rawReport)
    {
        $waveTransference = false;
        if ($this->shipReport) {
            $waveTransferenceGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($waveTransferenceGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($waveTransferenceGroup, 0, 1);
            if (strcmp($distinguishingDigit, '3') == 0) {
                $waveTransference = true;
            }
        } else {
            //synop report
        }

        if ($waveTransference) {
            $this->updateReport($waveTransferenceGroup, $rawReport);
            return $this->putInSection($waveTransferenceGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get4Pw1Pw1Hw1Hw1(RawReportInterface $rawReport)
    {
        $periodHeightWave = false;
        if ($this->shipReport) {
            $periodHeightWindWaveGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($periodHeightWindWaveGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($periodHeightWindWaveGroup, 0, 1);
            if (strcmp($distinguishingDigit, '4') == 0) {
                $periodHeightWave = true;
            }
        } else {
            //synop report
        }

        if ($periodHeightWave) {
            $this->updateReport($periodHeightWindWaveGroup, $rawReport);
            return $this->putInSection($periodHeightWindWaveGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get5Pw2Pw2Hw2Hw2(RawReportInterface $rawReport)
    {
        $periodAndHeightWave = false;
        if ($this->shipReport) {
            $periodHeightWaveGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($periodHeightWaveGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($periodHeightWaveGroup, 0, 1);
            if (strcmp($distinguishingDigit, '5') == 0) {
                $periodAndHeightWave = true;
            }
        } else {
            //synop report
        }

        if ($periodAndHeightWave) {
            $this->updateReport($periodHeightWaveGroup, $rawReport);
            return $this->putInSection($periodHeightWaveGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get6IsEsEsPs(RawReportInterface $rawReport)
    {
        $periodAndHeightWave = false;
        if ($this->shipReport) {
            $vesselIcingGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($vesselIcingGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($vesselIcingGroup, 0, 1);
            if (strcmp($distinguishingDigit, '6') == 0) {
                $periodAndHeightWave = true;
            }
        } else {
            //synop report
        }

        if ($periodAndHeightWave) {
            $this->updateReport($vesselIcingGroup, $rawReport);
            return $this->putInSection($vesselIcingGroup) ? true : false;
        } else {
            return null;
        }
    }
    
    public function getISE(RawReportInterface $rawReport)
    {
        $ice = false;
        if ($this->shipReport) {
            $distinguishingWordIce = $this->block($rawReport->getReport());
            if (!$this->isGroup($distinguishingWordIce, 3)) {
                return null;
            }

            if (strcmp($distinguishingWordIce, 'ICE') == 0) {
                $ice = true;
                $this->updateReport($distinguishingWordIce, $rawReport);
                $iceGroup = $this->getciSibiDizi($rawReport);
                return $this->putInSection($iceGroup) ? true : false;
            }
        } else {
            //synop report
        }
        return null;
    }
    
    public function getciSibiDizi(RawReportInterface $rawReport)
    {
        if ($this->shipReport) {
            $iceGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($iceGroup, 5)) {
                return null;
            }
        } else {
            //synop report
        }

        $this->updateReport($iceGroup, $rawReport);
        return $iceGroup;
    }
}
