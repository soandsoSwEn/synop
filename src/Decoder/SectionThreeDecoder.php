<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\ValidateInterface;
use Soandso\Synop\Sheme\AdditionalCloudInformationGroup;
use Soandso\Synop\Sheme\AirTemperatureGroup;
use Soandso\Synop\Sheme\GroundWithoutSnowGroup;
use Soandso\Synop\Sheme\GroundWithSnowGroup;
use Soandso\Synop\Sheme\MaxAirTemperatureGroup;
use Soandso\Synop\Sheme\MinAirTemperatureGroup;
use Soandso\Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;
use Soandso\Synop\Sheme\SunshineRadiationDataGroup;

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
    private $synopReport = null;

    /**
     * @var bool Ship identifier for weather report type
     */
    private $shipReport = null;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;
    
    public function __construct(SectionInterface $sectionTitle, bool $synop, bool $ship, Unit $unit)
    {
        $this->section = $sectionTitle;
        $this->synopReport = $synop;
        $this->shipReport = $ship;
        $this->unit = $unit;
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

    /**
     * Defines the air maximum temperature group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get1SnTxTxTx(RawReportInterface $rawReport, ValidateInterface $validate): ?bool
    {
        $maximumTemperature = false;
        if ($this->synopReport) {
            $maximumTemperatureGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($maximumTemperatureGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($maximumTemperatureGroup, 0, 1);
            if (strcmp($distinguishingDigit, '1') == 0) {
                $maximumTemperature = true;
                $SnTxTxTxMax = new MaxAirTemperatureGroup($maximumTemperatureGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($maximumTemperature) {
            $this->updateReport($maximumTemperatureGroup, $rawReport);
            return $this->putInSection($SnTxTxTxMax) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the air minimum temperature group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get2SnTnTnTn(RawReportInterface $rawReport, ValidateInterface $validate): ?bool
    {
        $minimumTemperature = false;
        if ($this->synopReport) {
            $minimumTemperatureGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($minimumTemperatureGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($minimumTemperatureGroup, 0, 1);
            if (strcmp($distinguishingDigit, '2') == 0) {
                $minimumTemperature = true;
                $SnTxTxTxMin = new MinAirTemperatureGroup($minimumTemperatureGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($minimumTemperature) {
            $this->updateReport($minimumTemperatureGroup, $rawReport);
            return $this->putInSection($SnTxTxTxMin) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines data of state of ground without snow group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get3ESnTgTg(RawReportInterface $rawReport, ValidateInterface $validate): ?bool
    {
        $stateGround = false;
        if ($this->synopReport) {
            $stateGroundGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($stateGroundGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($stateGroundGroup, 0, 1);
            if (strcmp($distinguishingDigit, '3') == 0) {
                $stateGround = true;
                $ESnTgTg = new GroundWithoutSnowGroup($stateGroundGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($stateGround) {
            $this->updateReport($stateGroundGroup, $rawReport);
            return $this->putInSection($ESnTgTg) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines data of state of ground with snow group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get4Esss(RawReportInterface $rawReport, ValidateInterface $validate)
    {
        $stateGroundSnow = false;
        if ($this->synopReport) {
            $state_ground_with_snow_group = $this->block($rawReport->getReport());
            if (!$this->isGroup($state_ground_with_snow_group, 5)) {
                return null;
            }

            $distinguishingDigit = substr($state_ground_with_snow_group, 0, 1);
            if (strcmp($distinguishingDigit, '4') == 0) {
                $stateGroundSnow = true;
                $Esss = new GroundWithSnowGroup($state_ground_with_snow_group, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($stateGroundSnow) {
            $this->updateReport($state_ground_with_snow_group, $rawReport);
            return $this->putInSection($Esss) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines duration of sunshine and radiation group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get55SSS(RawReportInterface $rawReport, ValidateInterface $validate)
    {
        $sunshineRadiation = false;
        if ($this->synopReport) {
            $sunshineRadiationGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($sunshineRadiationGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($sunshineRadiationGroup, 0, 2);
            if (strcmp($distinguishingDigit, '55') == 0) {
                $sunshineRadiation = true;
                $SSS = new SunshineRadiationDataGroup($sunshineRadiationGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($sunshineRadiation) {
            $this->updateReport($sunshineRadiationGroup, $rawReport);
            return $this->putInSection($SSS) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines amount of rainfall group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get6RRRtr(RawReportInterface $rawReport, ValidateInterface $validate)
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
                $RRRtr = new RegionalExchangeAmountRainfallGroup($precipitationGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($precipitation) {
            $this->updateReport($precipitationGroup, $rawReport);
            return $this->putInSection($RRRtr) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines additional cloud information transfer group
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool|null
     */
    public function get8NsChshs(RawReportInterface $rawReport, ValidateInterface $validate)
    {
        $clouds = false;
        if ($this->synopReport) {
            $cloudsGroup = $this->block($rawReport->getReport());
            if (!$this->isGroup($cloudsGroup, 5)) {
                return null;
            }

            $distinguishingDigit = substr($cloudsGroup, 0, 1);
            if (strcmp($distinguishingDigit, '8') == 0) {
                $clouds = true;
                $NsChshs = new AdditionalCloudInformationGroup($cloudsGroup, $this->unit, $validate);
            }
        } else {
            //ship report
        }

        if ($clouds) {
            $this->updateReport($cloudsGroup, $rawReport);
            return $this->putInSection($NsChshs) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @return bool|null
     */
    public function get9SpSpspsp(RawReportInterface $rawReport)
    {
        $weather = false;
        if ($this->synopReport) {
            $weatherGroup = $this->block($rawReport->getReport());
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
