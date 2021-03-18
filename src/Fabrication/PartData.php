<?php


namespace Synop\Fabrication;


use Synop\Sheme\AdditionalCloudInformationGroup;
use Synop\Sheme\AirTemperatureGroup;
use Synop\Sheme\AmountRainfallGroup;
use Synop\Sheme\BaricTendencyGroup;
use Synop\Sheme\CloudPresentGroup;
use Synop\Sheme\CloudWindGroup;
use Synop\Sheme\DateGroup;
use Synop\Sheme\DewPointTemperatureGroup;
use Synop\Sheme\GroundWithoutSnowGroup;
use Synop\Sheme\GroundWithSnowGroup;
use Synop\Sheme\GroupInterface;
use Synop\Sheme\IndexGroup;
use Synop\Sheme\LowCloudVisibilityGroup;
use Synop\Sheme\MaxAirTemperatureGroup;
use Synop\Sheme\MinAirTemperatureGroup;
use Synop\Sheme\MslPressureGroup;
use Synop\Sheme\PresentWeatherGroup;
use Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Synop\Sheme\SectionInterface;
use Synop\Sheme\StLPressureGroup;
use Synop\Sheme\SunshineRadiationDataGroup;

class PartData implements PartDataInterface
{
    /**
     * Returns all weather groups for a given section
     *
     * @param SectionInterface $sectionData All Sections of  the meteorological report
     * @param string $titleSection Preset section
     * @return array|null
     */
    public function getBodyOfSection(SectionInterface $sectionData, string $titleSection) : ?array
    {
        return $sectionData->getBodyByTitle($titleSection)->getBody();
    }

    /**
     * Returns an instance of the group class
     *
     * @param array $groupsData All groups of a specific section
     * @param string $groupItem The name of the class
     * @return GroupInterface|null
     */
    public function getGroupData(array $groupsData, string $groupItem) : ?GroupInterface
    {
        foreach ($groupsData as $key => $itemGroup) {
            if ($itemGroup instanceof $groupItem) {
                return $groupsData[$key];
            }
        }

        return null;
    }

    /**
     * Returns type of weather station
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return mixed|null
     */
    public function getTypeStation(SectionInterface $rawBlocksData)
    {
        //$body = $rawBlocksData->getBodyByTitle('General Section')->getBody();
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        if (array_key_exists('type', $body)) {
            return $body['type'];
        }

        return null;
    }

    /**
     * Returns the date (day) of a meteorological report
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getDayReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, DateGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getDayValue();
    }

    /**
     * Returns the time (hour) of a meteorological report
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getHourReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, DateGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getHourValue();
    }

    /**
     * Returns the unit of measure for wind speed
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getUnitWindReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, DateGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getIwValue()[1];
    }

    /**
     * Returns the type of wind measurement
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getWindDetectionReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, DateGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getIwValue()[0];
    }

    /**
     * Returns area number of meteorological station
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getAreaNumberReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, IndexGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getAreaNumberValue();
    }

    /**
     * Returns number of meteorological station
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getStationNumberReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, IndexGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getStationNumberValue();
    }

    /**
     * Returns station index
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getStationIndexReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, IndexGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getStationIndexValue();
    }

    /**
     * Returns index of the point of inclusion in the metrological report of the precipitation group 6RRRtr
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getInclusionPrecipitationReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, LowCloudVisibilityGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getIncPrecipValue();
    }

    /**
     * Returns weather indicator inclusion index 7wwW1W2
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getInclusionWeatherReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, LowCloudVisibilityGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getIncWeatherValue()[0];
    }

    /**
     * Returns type station operation
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getTypeStationOperationReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, LowCloudVisibilityGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getIncWeatherValue()[1];
    }

    /**
     * Returns base height of low clouds above sea level
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getHeightLowCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, LowCloudVisibilityGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getHeightLowValue();
    }

    /**
     * Returns meteorological range of visibility
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getVisibilityReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, LowCloudVisibilityGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getVisibilityValue();
    }

    /**
     * Returns total clouds
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getTotalAmountCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudWindGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getTotalCloudsValue();
    }

    /**
     * Returns direction of wind
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getWindDirectionReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudWindGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getDirectionWindValue();
    }

    /**
     * Returns wind speed
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getWindSpeedReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudWindGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getWindSpeedValue();
    }

    /**
     * Returns air temperature
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getAirTemperatureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AirTemperatureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getTemperatureValue();
    }

    /**
     * Returns dew point temperature
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getDewPointTemperatureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, DewPointTemperatureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getResultDewPointValue();
    }

    /**
     * Returns atmospheric pressure at station level
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getStationLevelPressureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, StLPressureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getPressureValue();
    }

    /**
     * Returns atmospheric pressure reduced to mean sea level
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getSeaLevelPressureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, MslPressureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getPressureValue();
    }

    /**
     * Returns pressure change over last three hours
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getBaricTendencyReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, BaricTendencyGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getTendencyValueData();
    }

    /**
     * Returns title or value of Amount of rainfall
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return mixed|null
     */
    public function getAmountRainfallReport(SectionInterface $rawBlocksData)
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AmountRainfallGroup::class);
        if (is_null($group)) {
            return null;
        }

        $rainfallData =  $group->getAmountRainfallValue();
        if (is_null($rainfallData[0])) {
            return $rainfallData[1];
        }

        return $rainfallData[0];
    }

    /**
     * Returns duration period of rainfall
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getDurationPeriodRainfallReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AmountRainfallGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getDurationPeriodValue();
    }

    /**
     * Returns present weather
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getPresentWeatherReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, PresentWeatherGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getPresentWeatherValue();
    }

    /**
     * Returns Past weather
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return array|null
     */
    public function getPastWeatherReport(SectionInterface $rawBlocksData) : ?array
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, PresentWeatherGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getPastWeatherValue();
    }

    /**
     * Returns amount of low or middle cloud
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getAmountLowOrMiddleCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudPresentGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getAmountLowCloudValue();
    }

    /**
     * Returns form of low cloud
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getFormLowCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudPresentGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getFormLowCloudValue();
    }

    /**
     * Returns form of medium cloud
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getFormMediumCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudPresentGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getFormMediumCloudValue();
    }

    /**
     * Returns form of high cloud
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getFormHighCloudReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'General Section');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, CloudPresentGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getFormHighCloudValue();
    }

    /**
     * Returns maximum air temperature
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getMaxAirTemperatureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, MaxAirTemperatureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getTemperatureValue();
    }

    /**
     * Returns minimum air temperature
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getMinAirTemperatureReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, MinAirTemperatureGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getTemperatureValue();
    }

    /**
     * Return state of ground for case ground without snow or measurable ice cover
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getStateGroundWithoutSnowReport(SectionInterface $rawBlocksData) :?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, GroundWithoutSnowGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getStateValue();
    }

    /**
     * Returns grass minimum temperature for case ground without snow or measurable ice cover
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return int|null
     */
    public function getMinTemperatureOfGroundWithoutSnowReport(SectionInterface $rawBlocksData) : ?int
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, GroundWithoutSnowGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getResultMinTemperature();
    }

    /**
     * Return state of ground for case ground with snow or measurable ice cover
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getStateGroundWithSnowReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, GroundWithSnowGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getStateValue();
    }

    /**
     * Returns depth of snow
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return mixed|null
     */
    public function getDepthSnowReport(SectionInterface $rawBlocksData)
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, GroundWithSnowGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getDepthSnowValue()['value'];
    }

    /**
     *  Returns duration of daily sunshine
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return float|null
     */
    public function getDurationSunshineReport(SectionInterface $rawBlocksData) : ?float
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, SunshineRadiationDataGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getSunshineValue();
    }

    /**
     * Returns amount of rainfall
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return int|null
     */
    public function getRegionalExchangeAmountRainfallReport(SectionInterface $rawBlocksData) : ?int
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, RegionalExchangeAmountRainfallGroup::class);
        if (is_null($group)) {
            return null;
        }

        $rainfallData = $group->getAmountRainfallValue();
        if (is_null($rainfallData[0])) {
            return $rainfallData[1];
        }

        return $rainfallData[0];
    }

    /**
     * Returns duration period of rainfall
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getRegionalExchangeDurationPeriodRainfallReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, RegionalExchangeAmountRainfallGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getDurationPeriodValue();
    }

    /**
     * Returns amount of individual cloud layer
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getAmountIndividualCloudLayerReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AdditionalCloudInformationGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getAmountCloudValue();
    }

    /**
     * Returns form of cloud (additional cloud information)
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return string|null
     */
    public function getFormClodReport(SectionInterface $rawBlocksData) : ?string
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AdditionalCloudInformationGroup::class);
        if (is_null($group)) {
            return null;
        }

        return $group->getFormCloudValue();
    }

    /**
     * Returns height of base of cloud layer (additional cloud information)
     *
     * @param SectionInterface $rawBlocksData All sections of the meteorological report
     * @return mixed|null
     */
    public function getHeightCloudReport(SectionInterface $rawBlocksData)
    {
        $body = $this->getBodyOfSection($rawBlocksData, 'Section Three');
        if (is_null($body)) {
            return null;
        }

        $group = $this->getGroupData($body, AdditionalCloudInformationGroup::class);
        if (is_null($group)) {
            return null;
        }

        $heightCloudData = $group->getHeightCloudValue();
        if (is_null($heightCloudData)) {
            return null;
        }

        return $heightCloudData['Height'];
    }
}