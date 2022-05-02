<?php


namespace Synop\Decoder\GroupDecoder;



use Exception;
use Synop\Fabrication\ValidateInterface;

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
     * @param ValidateInterface $validate
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate) : bool
    {
        $distinguishingDigit = substr($this->raw_dp_temperature, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(get_class($this), [
                $this->getCodeFigureDistNumber(), $this->getCodeFigureSignDwPTemperature(), $this->getCodeFigureDwPTemperature()
            ]);
            return true;
        }

        return false;
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

    /**
     * Return code figure of distinctive number of air temperature group
     *
     * @return false|string
     */
    public function getCodeFigureDistNumber()
    {
        return substr($this->raw_dp_temperature, 0, 1);
    }

    /**
     * Return code figure of sign of temperature
     *
     * @return false|string
     */
    public function getCodeFigureSignDwPTemperature()
    {
        return substr($this->raw_dp_temperature, 1, 1);
    }

    /**
     * Return code figure of air temperature
     *
     * @return false|string
     */
    public function getCodeFigureDwPTemperature()
    {
        return substr($this->raw_dp_temperature, 2, 3);
    }
}