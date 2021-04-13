<?php

namespace Synop\Decoder;

use Synop\Fabrication\Unit;
use Synop\Sheme\AirTemperatureGroup;
use Synop\Sheme\AmountRainfallGroup;
use Synop\Sheme\BaricTendencyGroup;
use Synop\Sheme\CloudPresentGroup;
use Synop\Sheme\CloudWindGroup;
use Synop\Sheme\DewPointTemperatureGroup;
use Synop\Sheme\LowCloudVisibilityGroup;
use Synop\Sheme\MslPressureGroup;
use Synop\Sheme\PresentWeatherGroup;
use Synop\Sheme\SectionInterface;
use Synop\Fabrication\RawReportInterface;
use Exception;
use Synop\Process\Pipeline;
use Synop\Sheme\Section;
use Synop\Sheme\DateGroup;
use Synop\Sheme\IndexGroup;
use Synop\Sheme\StLPressureGroup;
use Synop\Sheme\TypeGroup;

/**
 * Identifies decoding and determines the meta information of the weather
 * report, its sections and groups of each section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GeneralDecoder extends Decoder implements DecoderInterface
{
    /** Title of basic container for storing all sections of the meteorological report */
    const ALL_SECTIONS = 'All Sections';

    /** Title for Section 0 of weather report */
    const SECTION_ZERO = 'Section Zero';

    /**
     * @var Section All sections of the meteorological report
     */
    private $sections;

    /**
     * @var SectionInterface Current section of the weather report
     */
    private $section;

    /**
     * @var null Synop identifier for weather report type
     */
    private $synop_report = null;

    /**
     * @var null Ship identifier for weather report type
     */
    private $ship_report = null;

    /**
     * @var string[] Types of weather report
     */
    private $type_report = ['AAXX', 'BBXX'];

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;
    
    public function __construct(SectionInterface $section_title, Unit $unit)
    {
        $this->section = $section_title;
        $this->unit = $unit;
        $this->sections = new Section(self::ALL_SECTIONS);
        $this->putSection(new Section(self::SECTION_ZERO));
        $this->putSection($this->section);
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
     * Returns decoded data for all sections of the weather report
     * @return SectionInterface
     */
    public function parse() : SectionInterface
    {
        return $this->sections;
    }

    /**
     * Adds a new section to the base weather report container
     * @param SectionInterface $data Section data of weather report
     * @return bool
     */
    private function putSection(SectionInterface $data) : bool
    {
        $this->sections->setBody($data);

        return true;
    }

    /**
     * Adds group data to the weather section
     * @param $data mixed Group data
     * @param string|false $sectionTitle Title of section
     * @return bool
     */
    private function putInSection($data, $sectionTitle = false) : bool
    {
        if (!$sectionTitle) {
            $this->section->setBody($data);
        } else {
            $selectSection = $this->getSectionByTitle($sectionTitle);
            $selectSection->setBody($data);
        }

        return true;
    }

    /**
     * Returns object of section for weather report
     * @param string $sectionTitle
     * @return false|SectionInterface
     */
    private function getSectionByTitle(string $sectionTitle) : SectionInterface
    {
        return $this->sections->getBodyByTitle($sectionTitle);
    }

    /**
     * Defines the type of weather report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     * @throws Exception
     */
    public function getType(RawReportInterface $raw_report) : bool
    {
        $typeGroup = $this->block($raw_report->getReport());
        if(!in_array($typeGroup, $this->type_report)) {
            throw new Exception('Weather report type not set correctly!');
        }
        $type = new TypeGroup($typeGroup);
        $this->synop_report = $type->isSynop();
        $this->ship_report = $type->isShip();

        //TODO in this version synop only
        if ($type->isShip()) {
            die('The weather report is in SHIP format. This version supports weather reports only in SYNOP format!');
        }


        $this->updateReport($typeGroup, $raw_report);
        return $this->putInSection($type, self::SECTION_ZERO);
    }

    /**
     * Defines the ship sign of weather report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     * @throws Exception
     */
    public function getShipSign(RawReportInterface $raw_report) : bool
    {
        if($this->synop_report) {
            return false;
        }
        $ship_call = $this->block($raw_report->getReport());
        if(ctype_digit($ship_call)) {
            $this->updateReport($ship_call, $raw_report);
            return $this->putInSection($ship_call, self::SECTION_ZERO) ? true : false;
        } else {
            throw new Exception('Invalid ship call sign format!');
        }
    }

    /**
     * Defines the date group of the weather report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function getYYGGiw(RawReportInterface $raw_report) : bool
    {
        if($this->synop_report) {
            $date_group = $this->block($raw_report->getReport());
            $date = new DateGroup($date_group);
        } else {
            //ship report
        }
        $this->updateReport($date_group, $raw_report);
        return $this->putInSection($date, self::SECTION_ZERO) ? true : false;
    }

    /**
     * Defines the group of the international station index
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function getIIiii(RawReportInterface $raw_report) : bool
    {
        if($this->synop_report) {
            $station_index = $this->block($raw_report->getReport());
            $index = new IndexGroup($station_index);
        } else {
            //ship report
        }
        $this->updateReport($station_index, $raw_report);
        return $this->putInSection($index, self::SECTION_ZERO) ? true : false;
    }

    /**
     * Defines the group of the location of the ship (latittude)
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function get99LaLaLa(RawReportInterface $raw_report) : bool
    {
        if($this->ship_report) {
            //
        }
        return false;
    }

    /**
     * Defines the group of the location of the ship (longitude)
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function getQcL0L0L0L0(RawReportInterface $raw_report) : bool
    {
        if($this->ship_report) {
            //
        }
        return false;
    }

    /**
     * Defines the group for the height of the cloud base
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function getirixhVV(RawReportInterface $raw_report) : bool
    {
        if($this->synop_report) {
            $cloud_visibility_group = $this->block($raw_report->getReport());
            $iRIxHVV = new LowCloudVisibilityGroup($cloud_visibility_group, $this->unit);
        } else {
            //ship report
        }
        $this->updateReport($cloud_visibility_group, $raw_report);
        return $this->putInSection($iRIxHVV) ? true : false;
    }

    /**
     * Defines the group of the total amount of clouds
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function getNddff(RawReportInterface $raw_report) : bool
    {
        if($this->synop_report) {
            $cloud_wind_group = $this->block($raw_report->getReport());
            $Nddff = new CloudWindGroup($cloud_wind_group, $this->unit);
        } else {
            //ship report
        }
        $this->updateReport($cloud_wind_group, $raw_report);
        return $this->putInSection($Nddff) ? true : false;
    }

    /**
     * Defines the group air temperature
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get1SnTTT(RawReportInterface $raw_report) : ?bool
    {
        $temperature = false;
        if($this->synop_report) {
            $air_temperature_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($air_temperature_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($air_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $temperature = true;
                $SnTTT = new AirTemperatureGroup($air_temperature_group, $this->unit);
            } else {
                $temperature = false;
            }
        } else {
            //ship report
        }
        if($temperature) {
            $this->updateReport($air_temperature_group, $raw_report);
            return $this->putInSection($SnTTT) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the dew point temperature group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get2SnTdTdTd(RawReportInterface $raw_report) : ?bool
    {
        $dew_point = false;
        if($this->synop_report) {
            $dew_point_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($dew_point_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($dew_point_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $dew_point = true;
                $SnTdTdTd = new DewPointTemperatureGroup($dew_point_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($dew_point) {
            $this->updateReport($dew_point_group, $raw_report);
            return $this->putInSection($SnTdTdTd) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the group of atmospheric pressure at the station level
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get3P0P0P0P0(RawReportInterface $raw_report) : ?bool
    {
        $pressure_station = false;
        if($this->synop_report) {
            $pressure_station_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($pressure_station_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($pressure_station_group, 0, 1);
            if(strcmp($distinguishing_digit, '3') == 0) {
                $pressure_station = true;
                $P0P0P0P0 = new StLPressureGroup($pressure_station_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($pressure_station) {
            $this->updateReport($pressure_station_group, $raw_report);
            return $this->putInSection($P0P0P0P0) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the group air Pressure reduced to mean sea level
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get4PPPP(RawReportInterface $raw_report) : ?bool
    {
        $pressure_sea_level = false;
        if($this->synop_report) {
            $pressure_sea_level_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($pressure_sea_level_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($pressure_sea_level_group, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $pressure_sea_level = true;
                $PPPP = new MslPressureGroup($pressure_sea_level_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($pressure_sea_level) {
            $this->updateReport($pressure_sea_level_group, $raw_report);
            return $this->putInSection($PPPP) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the group of pressure change over last three hours
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get5appp(RawReportInterface $raw_report) : ?bool
    {
        $baric_tendency = false;
        if($this->synop_report) {
            $baric_tendency_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($baric_tendency_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($baric_tendency_group, 0, 1);
            if(strcmp($distinguishing_digit, '5') == 0) {
                $baric_tendency = true;
                $appp = new BaricTendencyGroup($baric_tendency_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($baric_tendency) {
            $this->updateReport($baric_tendency_group, $raw_report);
            return $this->putInSection($appp) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the group of amount of rainfall
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get6RRRtr(RawReportInterface $raw_report) : ?bool
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
                $RRRtr = new AmountRainfallGroup($precipitation_group, $this->unit);
            }
        } else {
            //ship report
        }
        if($precipitation ) {
            $this->updateReport($precipitation_group, $raw_report);
            return $this->putInSection($RRRtr) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the Present weather group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get7wwW1W2(RawReportInterface $raw_report) : ?bool
    {
        $weather = false;
        if($this->synop_report) {
            $weather_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($weather_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($weather_group, 0, 1);
            if(strcmp($distinguishing_digit, '7') == 0) {
                $weather = true;
                $wwW1W2 = new PresentWeatherGroup($weather_group);
            }
        } else {
            //ship report
        }
        if($weather) {
            $this->updateReport($weather_group, $raw_report);
            return $this->putInSection($wwW1W2) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the cloud present group
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get8NhClCmCh(RawReportInterface $raw_report) : ?bool
    {
        $cloud_characteristics = false;
        if($this->synop_report) {
            $cloud_characteristics_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($cloud_characteristics_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($cloud_characteristics_group, 0, 1);
            if(strcmp($distinguishing_digit, '8') == 0) {
                $cloud_characteristics = true;
                $NhClCmCh = new CloudPresentGroup($cloud_characteristics_group);
            }
        } else {
            //ship report
        }
        if($cloud_characteristics) {
            $this->updateReport($cloud_characteristics_group, $raw_report);
            return $this->putInSection($NhClCmCh) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get9hh(RawReportInterface $raw_report) : ?bool
    {
        $cloud_height = false;
        if($this->synop_report) {
            $cloud_characteristics_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($cloud_characteristics_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($cloud_characteristics_group, 0, 1);
            if(strcmp($distinguishing_digit, '9') == 0) {
                $cloud_height = true;
            }
        } else {
            //ship report
        }
        if($cloud_height) {
            $this->updateReport($cloud_characteristics_group, $raw_report);
            return $this->putInSection($cloud_characteristics_group) ? true : false;
        } else {
            return null;
        }
    }

    /**
     * Defines the groups of section 2
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function get222DsVs(RawReportInterface $raw_report) : ?bool
    {
        $section_two = false;
        //$st_blocks = [];
        if($this->ship_report) {
            $section_two_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($section_two_group, 0, 3);
            if(strcmp($distinguishing_digit, '222') == 0) {
                $section_two = true;
                $st_pipeline = new Pipeline();
                $pipes = $this->getTwoPipes();
                $st_pipeline->pipe($pipes);
                $st_decoder = new SectionTwoDecoder(new Section(self::SECTION_TWO), $this->synop_report, $this->ship_report);
                $st_blocks = $st_pipeline->process($raw_report, $st_decoder);
                return $this->putSection($st_blocks) ? true : false;
            }
        } else {
            return false;
        }

        return $section_two ? true : null;
    }

    /**
     * Returns codes figure for groups of section 2
     * @return string[]
     */
    public function getTwoPipes() : array
    {
        return [
            '222DsVs',
            '0SnTwTwTw',
            '1PwaPwaHwaHwa',
            '2PwPwHwHw',
            '3dw1dw1dw2dw2',
            '4Pw1Pw1Hw1Hw1',
            '5Pw2Pw2Hw2Hw2',
            '6IsEsEsPs',
            'ISE'
        ];
    }

    /**
     * Defines the groups of section 3
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function get333(RawReportInterface $raw_report) : ?bool
    {
        $section_three_group = false;
        //$str_blocks = [];
        if($this->synop_report) {
            $section_three = $this->block($raw_report->getReport());
            if(strcmp($section_three, '333') == 0) {
                $section_three_group = true;
                $this->updateReport($section_three, $raw_report);
                $str_pipelie = new Pipeline();
                $pipes = $this->getThreePipes();
                $str_pipelie->pipe($pipes);
                $str_decoder = new SectionThreeDecoder(new Section(self::SECTION_THREE), $this->synop_report, $this->ship_report, $this->unit);
                $str_blocks = $str_pipelie->process($raw_report, $str_decoder);
                return $this->putSection($str_blocks) ? true : false;
            }
        } else {
            //ship report
        }

        return $section_three_group ? true : null;
    }

    /**
     * Returns codes figure for groups of section 3
     * @return string[]
     */
    public function getThreePipes() : array
    {
        return [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];
    }

    /**
     * Defines the groups of section 4
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool
     */
    public function get444(RawReportInterface $raw_report) : ?bool
    {
        $section_four_group = false;
        //$sf_blocks = [];
        if($this->synop_report) {
            $section_four = $this->block($raw_report->getReport());
            if(strcmp($section_four, '444') == 0) {
                $section_four_group = true;
                $this->updateReport($section_four, $raw_report);
                $sf_pipelie = new Pipeline();
                $pipes = $this->getFourPipes();
                $sf_pipelie->pipe($pipes);
                $sf_decoder = new SectionFourDecoder(new Section(self::SECTION_FOUR), $this->synop_report, $this->ship_report);
                $sf_blocks = $sf_pipelie->process($raw_report, $sf_decoder);
                return $this->putSection($sf_blocks) ? true : false;
            }
        } else {
            //ship report
        }

        return $section_four_group ? true : null;
    }

    /**
     * Returns codes figure for groups of section 4
     * @return string[]
     */
    public function getFourPipes() : array
    {
        return ['NCHHCt'];
    }

    /**
     * Defines the groups of section 5
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @return bool|null
     */
    public function get555(RawReportInterface $raw_report) : ?bool
    {
        $section_five_group = false;
        //$sv_blocks = [];
        if($this->synop_report) {
            $section_five = $this->block($raw_report->getReport());
            if(strcmp($section_five, '555') == 0) {
                $section_five_group = true;
                $this->updateReport($section_five, $raw_report);
                $sv_pipelie = new Pipeline();
                $pipes = $this->getFivePipes();
                $sv_pipelie->pipe($pipes);
                $sv_decoder = new SectionFiveDecoder(new Section(self::SECTION_FIVE), $this->synop_report, $this->ship_report);
                $sv_blocks = $sv_pipelie->process($raw_report, $sv_decoder);
                return $this->putSection($sv_blocks) ? true : false;
            }
        } else {
            //ship report
        }

        return $section_five_group ? true : null;
    }

    /**
     * Returns codes figure for groups of section 5
     * @return string[]
     */
    public function getFivePipes() : array
    {
        return [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];
    }
}
