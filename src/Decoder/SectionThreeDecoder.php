<?php

namespace Synop\Decoder;

use Synop\Decoder\Decoder;
use Synop\Fabrication\Unit;
use Synop\Sheme\AdditionalCloudInformationGroup;
use Synop\Sheme\AirTemperatureGroup;
use Synop\Sheme\GroundWithoutSnowGroup;
use Synop\Sheme\GroundWithSnowGroup;
use Synop\Sheme\MaxAirTemperatureGroup;
use Synop\Sheme\MinAirTemperatureGroup;
use Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Synop\Sheme\SectionInterface;
use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;
use Synop\Sheme\SunshineRadiationDataGroup;

/**
 * Decodes the group code for section 3 of the weather report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionThreeDecoder extends Decoder implements DecoderInterface
{
    /**
     * @var SectionInterface Current section of the weather report
     */
    private $section;

    /**
     * @var bool Synop identifier for weather report type
     */
    private $synop_report = null;

    /**
     * @var bool Ship identifier for weather report type
     */
    private $ship_report = null;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;
    
    public function __construct(SectionInterface $section_title, bool $synop, bool $ship, Unit $unit)
    {
        $this->section = $section_title;
        $this->synop_report = $synop;
        $this->ship_report = $ship;
        $this->unit = $unit;
    }

    /**
     * Returns the result of checking if the group of the code matches the group of the weather report
     * @param string $codeFigure
     * @param int $size Weather group size
     * @return bool
     */
    public function isGroup(string $codeFigure, int $size) : bool
    {
        return mb_strlen($codeFigure) === $size;
    }

    /**
     * @return SectionInterface Returns decoded data for this section of the weather report
     */
    public function parse(): SectionInterface
    {
        return $this->section;
    }

    /**
     * Adds group data to the weather section
     * @param $data mixed Group data
     * @return bool
     */
    private function putInSection($data)
    {
        $this->section->setBody($data);

        return true;
    }

    /**
     * Defines the air maximum temperature group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get1SnTxTxTx(RawReportInterface $raw_report) : ?bool
    {
        $maximum_temperature = false;
        if($this->synop_report) {
            $maximum_temperature_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($maximum_temperature_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($maximum_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $maximum_temperature = true;
                $SnTxTxTxMax = new MaxAirTemperatureGroup($maximum_temperature_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($maximum_temperature) {
            $this->updateReport($maximum_temperature_group, $raw_report);
            return $this->putInSection($SnTxTxTxMax) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the air minimum temperature group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get2SnTnTnTn(RawReportInterface $raw_report) : ?bool
    {
        $minimum_temperature = false;
        if($this->synop_report) {
            $minimum_temperature_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($minimum_temperature_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($minimum_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $minimum_temperature = true;
                $SnTxTxTxMin = new MinAirTemperatureGroup($minimum_temperature_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($minimum_temperature) {
            $this->updateReport($minimum_temperature_group, $raw_report);
            return $this->putInSection($SnTxTxTxMin) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines data of state of ground without snow group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get3ESnTgTg(RawReportInterface $raw_report)
    {
        $stateGround = false;
        if($this->synop_report) {
            $state_ground_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($state_ground_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($state_ground_group, 0, 1);
            if(strcmp($distinguishing_digit, '3') == 0) {
                $stateGround = true;
                $ESnTgTg = new GroundWithoutSnowGroup($state_ground_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($stateGround) {
            $this->updateReport($state_ground_group, $raw_report);
            return $this->putInSection($ESnTgTg) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines data of state of ground with snow group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get4Esss(RawReportInterface $raw_report)
    {
        $stateGroundSnow = false;
        if($this->synop_report) {
            $state_ground_with_snow_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($state_ground_with_snow_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($state_ground_with_snow_group, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $stateGroundSnow = true;
                $Esss = new GroundWithSnowGroup($state_ground_with_snow_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($stateGroundSnow) {
            $this->updateReport($state_ground_with_snow_group, $raw_report);
            return $this->putInSection($Esss) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines duration of sunshine and radiation group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get55SSS(RawReportInterface $raw_report)
    {
        $sunshineRadiation = false;
        if($this->synop_report) {
            $sunshineRadiationGroup = $this->block($raw_report->getReport());
            if (!$this->isGroup($sunshineRadiationGroup, 5)) {
                return null;
            }

            $distinguishing_digit = substr($sunshineRadiationGroup, 0, 2);
            if(strcmp($distinguishing_digit, '55') == 0) {
                $sunshineRadiation = true;
                $SSS = new SunshineRadiationDataGroup($sunshineRadiationGroup, $this->unit);
            }
        } else {
            //ship report
        }
        if($sunshineRadiation) {
            $this->updateReport($sunshineRadiationGroup, $raw_report);
            return $this->putInSection($SSS) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines amount of rainfall group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get6RRRtr(RawReportInterface $raw_report)
    {
        $precipitation = false;
        if($this->synop_report) {
            $precipitation_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($precipitation_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($precipitation_group, 0, 1);
            if(strcmp($distinguishing_digit, '6') == 0) {
                $precipitation = true;
                $RRRtr = new RegionalExchangeAmountRainfallGroup($precipitation_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($precipitation) {
            $this->updateReport($precipitation_group, $raw_report);
            return $this->putInSection($RRRtr) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines additional cloud information transfer group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get8NsChshs(RawReportInterface $raw_report)
    {
        $clouds = false;
        if($this->synop_report) {
            $clouds_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($clouds_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($clouds_group, 0, 1);
            if(strcmp($distinguishing_digit, '8') == 0) {
                $clouds = true;
                $NsChshs = new AdditionalCloudInformationGroup($clouds_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($clouds) {
            $this->updateReport($clouds_group, $raw_report);
            return $this->putInSection($NsChshs) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get9SpSpspsp(RawReportInterface $raw_report)
    {
        $weather = false;
        if($this->synop_report) {
            $weather_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($weather_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($weather_group, 0, 1);
            if(strcmp($distinguishing_digit, '9') == 0) {
                $weather = true;
            }
        } else {
            //ship report
        }
        if($weather) {
            $this->updateReport($weather_group, $raw_report);
            return $this->putInSection($weather_group) ? true : false;
        } else {
            return null;
        }
    }
}
