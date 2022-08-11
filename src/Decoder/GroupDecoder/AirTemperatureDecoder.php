<?php


namespace Soandso\Synop\Decoder\GroupDecoder;



use Soandso\Synop\Fabrication\ValidateInterface;

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

    /** Value distinctive number of minimum air temperature group */
    const MINIMUM_TEMP_DIGIT = '2';

    private $currentDigit = null;

    /**
     * @var string Air temperature group data
     */
    private $raw_air_temperature;

    public function __construct(string $raw_air_temperature, $isMinTemp = false)
    {
        $this->currentDigit = $isMinTemp ? self::MINIMUM_TEMP_DIGIT : self::DIGIT;
        $this->raw_air_temperature = $raw_air_temperature;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate
     * @return bool
     * @throws \Exception
     */
    public function isGroup(ValidateInterface $validate) : bool
    {
        $distinguishing_digit = substr($this->raw_air_temperature, 0, 1);

        if (strcasecmp($distinguishing_digit, $this->currentDigit) == 0) {
            $validate->isValidGroup(get_class($this), [
                $this->getCodeFigureDistNumber(), $this->getCodeFigureSignTemperature(), $this->getCodeFigureTemperature()
            ]);
            return true;
        }

        return false;
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

    /**
     * Return code figure of distinctive number of air temperature group
     *
     * @return false|string
     */
    public function getCodeFigureDistNumber()
    {
        return substr($this->raw_air_temperature, 0, 1);
    }

    /**
     * Return code figure of sign of temperature
     *
     * @return false|string
     */
    public function getCodeFigureSignTemperature()
    {
        return substr($this->raw_air_temperature, 1, 1);
    }

    /**
     * Return code figure of air temperature
     *
     * @return false|string
     */
    public function getCodeFigureTemperature()
    {
        return substr($this->raw_air_temperature, 2, 3);
    }
}
