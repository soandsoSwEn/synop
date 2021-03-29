<?php


namespace Synop\Sheme;


use Exception;
use Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Synop\Fabrication\Unit;

/**
 * Class MinAirTemperatureGroup contains methods for working with a group of minimum air temperatures
 * of section three - 333 2SnTnTnTn
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class MinAirTemperatureGroup extends AirTemperatureGroup
{
    /** Value distinctive number of minimum air temperature group */
    const DIGIT = '2';

    public function __construct(string $data, Unit $unit)
    {
        parent::__construct($data, $unit);
    }

    /**
     * Sets the initial data for the minimum air temperature group
     * @param string $data Minimum air temperature group data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if(!empty($data)) {
            $this->setRawAirTemperature($data);
            $this->setDecoder(new AirTemperatureDecoder($this->getRawAirTemperature(), self::DIGIT));
            $this->setAirTempGroup($this->getDecoder());
        } else {
            throw new Exception('Minimum Air Temperature Group group cannot be empty!');
        }
    }
}