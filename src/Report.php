<?php

namespace Synop;

use Synop\Fabrication\PartData;
use Synop\Fabrication\RawReportInterface;
use Exception;
use Synop\Fabrication\Validate;
use Synop\Fabrication\RawReport;
use Synop\Decoder\GeneralDecoder;
use Synop\Sheme\Section;
use Synop\Process\Pipeline;
use Synop\Sheme\SectionInterface;

/**
 * Weather report initial processing
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Report implements ReportInterface
{
    const GENERAL_SECTION = 'General Section';

    /**
     * @var string Meteorological weather report source code
     */
    private $report;
    
    /**
     * @var RawReport Object of meteorological report source code
     */
    private $raw_report;

    /**
     * @var SectionInterface Data for all sections of the meteorological report
     */
    private $rawBlocksData;

    /**
     * @var PartData Instance class forming the final data of the output parameters of the meteorological report
     */
    private $partData;


    public function __construct(string $report)
    {
        $this->setReport($report);
        $this->partData = new PartData();
    }

    /**
     * Sets the initial value of the weather report
     * @param string $report
     * @throws Exception
     */
    public function setReport(string $report): void
    {
        if (!empty($report)) {
            $this->report = $report;
            $this->raw_report = new RawReport($report);
        } else {
            throw new Exception('Weather report cannot be an empty string!');
        }
    }

    /**
     * Checking the validity of the source code of the meteorological report
     * @return bool
     * @throws Exception
     */
    public function validate() : bool
    {
        if(!$this->report) {
            throw new Exception('Meteorological weather report not defined!');
        }
        $validator = new Validate($this->report);
        return $validator->isValid();
    }

    /**
     * Returns meteorological weather report source code value
     * @return string
     */
    public function getReport() : string
    {
        return $this->report;
    }

    /**
     * Returns instance class of meteorological report
     * @return RawReportInterface
     */
    public function getRawReport() : RawReportInterface
    {
        return $this->raw_report;
    }

    /**
     * Get Type Of Station
     * @return string
     * @throws Exception
     */
    public function getType(): string
    {
        $typeOfStation = $this->getTypeStation();
        if (is_null($typeOfStation)) {
            throw new Exception('Type Of Station index not defined');
        }
    }

    /**
     * Get WMO station index
     * @return string
     * @throws Exception
     */
    public function getWmo() : string
    {
        $wmoIndex = $this->getStationIndex();
        if (is_null($wmoIndex)) {
            throw new Exception('WMO index not defined');
        }

        return $wmoIndex;
    }

    /**
     * Starts the decoding process for the meteorological report
     * @return void
     */
    public function parse() : void
    {
        $pipes = $this->getPipes();
        
        $pipeline = new Pipeline();
        $pipeline->pipe($pipes);
        $decoder = new GeneralDecoder(new Section(self::GENERAL_SECTION));
        $blocks =  $pipeline->process($this->raw_report, $decoder);
        $this->rawBlocksData = $blocks;
    }

    /**
     * Code figure of the group of the general section, as well as indicators of additional groups
     * @return string[]
     */
    private function getPipes() : array
    {
        return [
            'type',
            'ShipSign',
            'YYGGiw',
            'IIiii',
            '99LaLaLa',
            'QcL0L0L0L0',
            'irixhVV',
            'Nddff',
            '1SnTTT',
            '2SnTdTdTd',
            '3P0P0P0P0',
            '4PPPP',
            '5appp',
            '6RRRtr',
            '7wwW1W2',
            '8NhClCmCh',
            '9hh//',
            '222DsVs',
            /*'0SnTwTwTw',
            '1PwaPwaHwaHwa',
            '2PwPwHwHw',
            '3dw1dw1dw2dw2',
            '4Pw1Pw1Hw1Hw1',
            '5Pw2Pw2Hw2Hw2',
            '6IsEsEsPs',
            'ISE',*/
            '333',
            '444',
            '555',
        ];
    }

    //Get Part of Data

    /**
     * Get a type of weather station
     * @return string|null
     */
    public function getTypeStation() : ?string
    {
        return $this->partData->getTypeStation($this->rawBlocksData);
    }

    /**
     * Get a date (day) of a meteorological report
     * @return string|null
     */
    public function getDay() : ?string
    {
        return $this->partData->getDayReport($this->rawBlocksData);
    }

    /**
     * Get a time (hour) of a meteorological report
     * @return string|null
     */
    public function getTime() : ?string
    {
        return $this->partData->getHourReport($this->rawBlocksData);
    }

    /**
     * Get a unit of measure for wind speed
     * @return string|null
     */
    public function getUnitWind() : ?string
    {
        return $this->partData->getUnitWindReport($this->rawBlocksData);
    }

    /**
     * Get a type of wind measurement
     * @return string|null
     */
    public function getWindDetection() : ?string
    {
        return $this->partData->getWindDetectionReport($this->rawBlocksData);
    }

    //Station Group IIiii

    /**
     * Get area number of meteorological station
     * @return string|null
     */
    public function getAreaNumber() : ?string
    {
        return $this->partData->getAreaNumberReport($this->rawBlocksData);
    }

    /**
     * Get a number of meteorological station
     * @return string|null
     */
    public function getStationNumber() : ?string
    {
        return $this->partData->getStationNumberReport($this->rawBlocksData);
    }

    /**
     * Get a station index
     * @return string|null
     */
    public function getStationIndex() : ?string
    {
        return $this->partData->getStationIndexReport($this->rawBlocksData);
    }

    //Group IrIxHvv

    /**
     * Get index inclusion the precipitation group 6RRRtr
     * @return string|null
     */
    public function getInclusionPrecipitation() : ?string
    {
        return $this->partData->getInclusionPrecipitationReport($this->rawBlocksData);
    }

    /**
     * Get a weather indicator inclusion index 7wwW1W2
     * @return string|null
     */
    public function getInclusionWeather() : ?string
    {
        return $this->partData->getInclusionWeatherReport($this->rawBlocksData);
    }

    /**
     * Get a type station operation
     * @return string|null
     */
    public function getTypeStationOperation() : ?string
    {
        return $this->partData->getTypeStationOperationReport($this->rawBlocksData);
    }

    /**
     * Get a base height of low clouds above sea level
     * @return string|null
     */
    public function getHeightLowCloud() : ?string
    {
        return $this->partData->getHeightLowCloudReport($this->rawBlocksData);
    }

    /**
     * Get a meteorological range of visibility
     * @return string|null
     */
    public function getVisibility() : ?string
    {
        return $this->partData->getVisibilityReport($this->rawBlocksData);
    }

    //Group NDDff

    /**
     * Get total clouds
     * @return string|null
     */
    public function getTotalAmountCloud() : ?string
    {
        return $this->partData->getTotalAmountCloudReport($this->rawBlocksData);
    }

    /**
     * Get direction of wind
     * @return string|null
     */
    public function getWindDirection() : ?string
    {
        return $this->partData->getWindDirectionReport($this->rawBlocksData);
    }

    /**
     * Get wind speed
     * @return string|null
     */
    public function getWindSpeed() : ?string
    {
        return $this->partData->getWindSpeedReport($this->rawBlocksData);
    }

    //Group 1SnTTT

    /**
     * Get air temperature
     * @return float|null
     */
    public function getAirTemperature() : ?float
    {
        return $this->partData->getAirTemperatureReport($this->rawBlocksData);
    }

    //Group 2SnTdTdTd

    /**
     * Get dew point temperature
     * @return float|null
     */
    public function getDewPointTemperature() : ?float
    {
        return $this->partData->getDewPointTemperatureReport($this->rawBlocksData);
    }

    //Group 3P0P0P0

    /**
     * Get atmospheric pressure at station level
     * @return float|null
     */
    public function getStationLevelPressure() : ?float
    {
        return $this->partData->getStationLevelPressureReport($this->rawBlocksData);
    }

    //Group 4PPPP

    /**
     * Get atmospheric pressure reduced to mean sea level
     * @return float|null
     */
    public function getSeaLevelPressure() : ?float
    {
        return $this->partData->getSeaLevelPressureReport($this->rawBlocksData);
    }

    //Group 5appp

    /**
     * Get pressure change over last three hours
     * @return string|null
     */
    public function getBaricTendency() : ?string
    {
        return $this->partData->getBaricTendencyReport($this->rawBlocksData);
    }

    //Group 6RRRtr

    /**
     * Get a title or value of amount of rainfall
     * @return mixed|null
     */
    public function getAmountRainfall()
    {
        return $this->partData->getAmountRainfallReport($this->rawBlocksData);
    }

    /**
     * Get duration period of rainfall
     * @return string|null
     */
    public function getDurationPeriodRainfall() : ?string
    {
        return $this->partData->getDurationPeriodRainfallReport($this->rawBlocksData);
    }

    //Group 7wwW1W2

    /**
     * Get present weather
     * @return string|null
     */
    public function getPresentWeather() : ?string
    {
        return $this->partData->getPresentWeatherReport($this->rawBlocksData);
    }

    /**
     * Get Past weather
     * @return array|null
     */
    public function getPastWeather() : ?array
    {
        return $this->partData->getPastWeatherReport($this->rawBlocksData);
    }

    //Group 8NhClCmCH

    /**
     * Get amount of low or middle cloud
     * @return string|null
     */
    public function getAmountLowOrMiddleCloud() : ?string
    {
        return $this->partData->getAmountLowOrMiddleCloudReport($this->rawBlocksData);
    }

    /**
     * Get form of low cloud
     * @return string|null
     */
    public function getFormLowCloud() : ?string
    {
        return $this->partData->getFormLowCloudReport($this->rawBlocksData);
    }

    /**
     * Get form of medium cloud
     * @return string|null
     */
    public function getFormMediumCloud() : ?string
    {
        return $this->partData->getFormMediumCloudReport($this->rawBlocksData);
    }

    /**
     * Get form of high cloud
     * @return string|null
     */
    public function getFormHighCloud() : ?string
    {
        return $this->partData->getFormHighCloudReport($this->rawBlocksData);
    }

    //Section 3
    //Group 1SnTxTxTx

    /**
     * Get maximum air temperature
     * @return float|null
     */
    public function getMaxAirTemperature() : ?float
    {
        return $this->partData->getMaxAirTemperatureReport($this->rawBlocksData);
    }

    //Group 2SnTnTnTn

    /**
     * Get minimum air temperature
     * @return float|null
     */
    public function getMinAirTemperature() : ?float
    {
        return $this->partData->getMinAirTemperatureReport($this->rawBlocksData);
    }

    //Group 3ESnTgTg

    /**
     * Get a state of ground for case ground without snow or measurable ice cover
     * @return string|null
     */
    public function getStateGroundWithoutSnow() : ?string
    {
        return $this->partData->getStateGroundWithoutSnowReport($this->rawBlocksData);
    }

    /**
     * Get grass minimum temperature for case ground without snow or measurable ice cover
     * @return int|null
     */
    public function getMinTemperatureOfGroundWithoutSnow() : ?int
    {
        return $this->partData->getMinTemperatureOfGroundWithoutSnowReport($this->rawBlocksData);
    }

    //Group 4Esss

    /**
     * Get a state of ground for case ground with snow or measurable ice cover
     * @return string|null
     */
    public function getStateGroundWithSnow() : ?string
    {
        return $this->partData->getStateGroundWithSnowReport($this->rawBlocksData);
    }

    /**
     * Get depth of snow
     * @return mixed|null
     */
    public function getDepthSnow()
    {
        return $this->partData->getDepthSnowReport($this->rawBlocksData);
    }

    //Group 55SSS

    /**
     * Get a duration of daily sunshine
     * @return float|null
     */
    public function getDurationSunshine() : ?float
    {
        return $this->partData->getDurationSunshineReport($this->rawBlocksData);
    }

    //Group 6RRRtr

    /**
     * Get amount of rainfall
     * @return int|null
     */
    public function getRegionalExchangeAmountRainfall() : ?int
    {
        return $this->partData->getRegionalExchangeAmountRainfallReport($this->rawBlocksData);
    }

    /**
     * Get duration period of rainfall
     * @return string|null
     */
    public function getRegionalExchangeDurationPeriodRainfall() : ?string
    {
        return $this->partData->getRegionalExchangeDurationPeriodRainfallReport($this->rawBlocksData);
    }

    //Group 8NsChshs

    /**
     * Get amount of individual cloud layer
     * @return string|null
     */
    public function getAmountIndividualCloudLayer() : ?string
    {
        return $this->partData->getAmountIndividualCloudLayerReport($this->rawBlocksData);
    }

    /**
     * Get a form of cloud (additional cloud information)
     * @return string|null
     */
    public function getFormCloud() : ?string
    {
        return $this->partData->getFormClodReport($this->rawBlocksData);
    }

    /**
     * Get height of base of cloud layer (additional cloud information)
     * @return mixed|null
     */
    public function getHeightCloud()
    {
        return $this->partData->getHeightCloudReport($this->rawBlocksData);
    }

}
