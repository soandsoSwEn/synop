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
    protected const DIGIT = '1';

    /** Value distinctive number of minimum air temperature group */
    protected const MINIMUM_TEMP_DIGIT = '2';

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
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws \Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        $distinguishing_digit = substr($this->raw_air_temperature, 0, 1);

        if (strcasecmp($distinguishing_digit, $this->currentDigit) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [
                $this->getCodeFigureDistNumber(),
                $this->getCodeFigureSignTemperature(),
                $this->getCodeFigureTemperature()
                ]
            );

            return true;
        }

        return false;
    }

    /**
     * Returns the sign of the temperature
     *
     * @return int
     */
    public function getSignTemperature(): int
    {
        return intval(substr($this->raw_air_temperature, 1, 1));
    }

    /**
     * Returns the temperature value
     *
     * @return float
     */
    public function getTemperatureValue(): float
    {
        $stringOfTemperature = substr($this->raw_air_temperature, 2, 3);
        $integerOfNumber = substr($stringOfTemperature, 0, 2);
        $fractionalOfNumber = substr($stringOfTemperature, 2, 1);
        $airTemperature = $integerOfNumber . '.' . $fractionalOfNumber;

        return floatval($airTemperature);
    }

    /**
     * Returns indicator and description of group indicator for air temperatures - 1SnTTT
     *
     * @return string[] Indicator and description of air temperature
     */
    public function getIndicatorGroup(): array
    {
        return ['1' => 'Indicator'];
    }

    /**
     * Returns indicator and description of sign of temperature for air temperatures - 1SnTTT
     *
     * @return string[] Indicator and description of sign of temperature
     */
    public function getSignTemperatureIndicator(): array
    {
        return ['Sn' => 'Sign of temperature'];
    }

    /**
     * Returns indicator and description of Dry-bulb temperature for air temperatures - 1SnTTT
     *
     * @return string[] Indicator and description of Dry-bulb temperature
     */
    public function getDryBulbTemperatureIndicator(): array
    {
        return ['TTT' => 'Dry-bulb temperature in tenths of a degree'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getIndicatorGroup()),
            key($this->getSignTemperatureIndicator()),
            key($this->getDryBulbTemperatureIndicator()),
        ];
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
