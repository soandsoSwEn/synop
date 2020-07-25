<?php


namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Exception;


/**
 * Class AirTemperatureDecoder contains methods for decoding an air temperature group
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AirTemperatureDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of air temperature group */
    const DIGIT = '1';

    /**
     * @var string Air temperature group data
     */
    private $raw_air_temperature;

    public function __construct(string $raw_air_temperature)
    {
        $this->raw_air_temperature = $raw_air_temperature;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @return bool
     */
    public function isGroup() : bool
    {
        $distinguishing_digit = substr($this->raw_air_temperature, 0, 1);

        return strcasecmp($distinguishing_digit, self::DIGIT) == 0 ? true : false;
    }

    /**
     * Returns the sign of the temperature
     * @return int
     */
    public function getSignTemperature() : int
    {
        return intval(substr($this->raw_air_temperature, 1, 1));
    }

    /**
     * Returns the temperature value
     *
     * @return float
     */
    public function getTemperatureValue() : float
    {
        $stringOfTemperature = substr($this->raw_air_temperature, 2, 3);
        $integerOfNumber = substr($stringOfTemperature, 0, 2);
        $fractionalOfNumber = substr($stringOfTemperature, 2, 1);
        $airTemperature = $integerOfNumber . '.' . $fractionalOfNumber;

        return floatval($airTemperature);
    }
}