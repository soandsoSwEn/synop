<?php

namespace Synop\Fabrication;

use Exception;
use Synop\Sheme\AdditionalCloudInformationGroup;
use Synop\Sheme\AirTemperatureGroup;
use Synop\Sheme\AmountRainfallGroup;
use Synop\Sheme\BaricTendencyGroup;
use Synop\Sheme\CloudWindGroup;
use Synop\Sheme\DewPointTemperatureGroup;
use Synop\Sheme\GroundWithoutSnowGroup;
use Synop\Sheme\GroundWithSnowGroup;
use Synop\Sheme\LowCloudVisibilityGroup;
use Synop\Sheme\MaxAirTemperatureGroup;
use Synop\Sheme\MinAirTemperatureGroup;
use Synop\Sheme\MslPressureGroup;
use Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Synop\Sheme\StLPressureGroup;
use Synop\Sheme\SunshineRadiationDataGroup;

/**
 * Class Unit contains methods for working with unit data for all weather report groups
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Unit implements UnitInterface
{
    /**
     * @var array Default units for meteorological fields
     */
    private $defaultUnits = [
        LowCloudVisibilityGroup::class => ['h' => 'm', 'VV' => 'm'],
        CloudWindGroup::class => ['dd' => 'degrees', 'ff' => 'm/s'],
        AirTemperatureGroup::class => ['TTT' => 'degree C'],
        DewPointTemperatureGroup::class => ['TdTdTd' => 'degree C'],
        StLPressureGroup::class => ['PoPoPoPo' => 'hPa'],
        MslPressureGroup::class => ['PPPP' => 'hPa'],
        BaricTendencyGroup::class => ['ppp' => 'hPa'],
        AmountRainfallGroup::class => ['RRR' => 'mm'],
        MaxAirTemperatureGroup::class => ['TxTxTx' => 'degree C'],
        MinAirTemperatureGroup::class => ['TnTnTn' => 'degree C'],
        GroundWithoutSnowGroup::class => ['TgTg' => 'degree C'],
        GroundWithSnowGroup::class => ['sss' => 'cm'],
        SunshineRadiationDataGroup::class => ['SSS' => 'hour'],
        RegionalExchangeAmountRainfallGroup::class => ['RRR' => 'mm'],
        AdditionalCloudInformationGroup::class => ['hshs' => 'm'],
    ];

    /**
     * Returns all current unit values for weather groups
     * @return array|\string[][]
     */
    protected function getDefaultUnits() : array
    {
        return $this->defaultUnits;
    }

    /**
     * Sets the unit of measurement for the group parameter
     * @param string $group Fully-qualified name for a class of group of weather report
     * @param string $parameter Meteorological parameter of this weather report group
     * @param string $value Parameter value
     * @throws Exception
     */
    public function setUnit(string $group, string $parameter, string $value) : void
    {
        if (!array_key_exists($group, $this->getDefaultUnits())) {
            throw new Exception("class {$group} does not exist to define units");
        }

        $this->defaultUnits[$group][$parameter] = $value;
    }

    /**
     * Returns the unit of measure for all parameters of the weather group
     * @param string $group
     * @return string[]|null
     */
    public function getUnitByGroup(string $group) : ?array
    {
        if (array_key_exists($group, $this->defaultUnits)) {
            return $this->defaultUnits[$group];
        }

        return null;
    }
}
