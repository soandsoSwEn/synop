<?php

namespace Soandso\Synop\Sheme;

use Exception;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\ValidateInterface;

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
    protected const DIGIT = '2';

    public function __construct(string $data, Unit $unit, ValidateInterface $validate)
    {
        parent::__construct($data, $unit, $validate);
    }

    /**
     * Sets the initial data for the minimum air temperature group
     *
     * @param string $data Minimum air temperature group data
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->setRawAirTemperature($data);
            $this->setDecoder(new AirTemperatureDecoder($this->getRawAirTemperature(), self::DIGIT));
            $this->setAirTempGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('Minimum Air Temperature Group group cannot be empty!');
        }
    }
}
