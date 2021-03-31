<?php


namespace Synop\Decoder\GroupDecoder;



/**
 * Class DewPointTemperatureDecoder contains methods for decoding a group of Dew point temperature
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DewPointTemperatureDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of dew point temperature group */
    const DIGIT = '2';

    /**
     * @var string Dew point temperature group data
     */
    private $raw_dp_temperature;

    public function __construct(string $raw_dp_temperature)
    {
        $this->raw_dp_temperature = $raw_dp_temperature;
    }

    /**
     * Returns the result of checking the validity of the group
     * @return bool
     */
    public function isGroup() : bool
    {
        $distinguishingDigit = substr($this->raw_dp_temperature, 0, 1);

        return strcasecmp($distinguishingDigit, self::DIGIT) == 0 ? true : false;
    }

    /**
     * Returns the sign of the dew point temperature
     * @return int
     */
    public function getSignDewPointTemperature() : int
    {
        return intval(substr($this->raw_dp_temperature, 1, 1));
    }

    /**
     * Returns the dew point temperature value
     * @return float
     */
    public function getDewPointTemperature() : float
    {
        $stringOfDewPoint = substr($this->raw_dp_temperature, 2, 3);
        $integerOfDewPoint = substr($stringOfDewPoint, 0, 2);
        $fractionalOfNumber = substr($stringOfDewPoint, 2, 1);
        $dewPoint = $integerOfDewPoint . '.' . $fractionalOfNumber;

        return floatval($dewPoint);
    }
}