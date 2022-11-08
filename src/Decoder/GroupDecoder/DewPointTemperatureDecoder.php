<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

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
    protected const DIGIT = '2';

    /**
     * @var string Dew point temperature group data
     */
    private $rawDpTemperature;

    public function __construct(string $rawDpTemperature)
    {
        $this->rawDpTemperature = $rawDpTemperature;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate): bool
    {
        $distinguishingDigit = substr($this->rawDpTemperature, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup($this, [
                $this->getCodeFigureDistNumber(),
                $this->getCodeFigureSignDwPTemperature(),
                $this->getCodeFigureDwPTemperature()
            ]);

            return true;
        }

        return false;
    }

    /**
     * Returns the sign of the dew point temperature
     *
     * @return int
     */
    public function getSignDewPointTemperature(): int
    {
        return intval(substr($this->rawDpTemperature, 1, 1));
    }

    /**
     * Returns the dew point temperature value
     * @return float
     */
    public function getDewPointTemperature(): float
    {
        $stringOfDewPoint = substr($this->rawDpTemperature, 2, 3);
        $integerOfDewPoint = substr($stringOfDewPoint, 0, 2);
        $fractionalOfNumber = substr($stringOfDewPoint, 2, 1);
        $dewPoint = $integerOfDewPoint . '.' . $fractionalOfNumber;

        return floatval($dewPoint);
    }

    /**
     * Returns indicator and description of group indicator dew point temperature - 2SnTdTdTd
     *
     * @return string[] Indicator and description of dew point temperature
     */
    public function getGetIndicatorGroup(): array
    {
        return ['2' => 'Indicator'];
    }

    /**
     * Returns indicator and description of sign of temperature for dew point temperature - 2SnTdTdTd
     *
     * @return string[] Indicator and description of sign of dew point temperature
     */
    public function getSignTemperatureIndicator(): array
    {
        return ['Sn' => 'Sign of temperature'];
    }

    /**
     * Returns indicator and description of Dry-bulb temperature for dew point temperature - 2SnTdTdTd
     *
     * @return string[] Indicator and description of dew point temperature
     */
    public function getDryBulbTemperatureIndicator(): array
    {
        return ['TdTdTd' => 'Dew point temperature in tenths of a degree'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getGetIndicatorGroup()),
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
        return substr($this->rawDpTemperature, 0, 1);
    }

    /**
     * Return code figure of sign of temperature
     *
     * @return false|string
     */
    public function getCodeFigureSignDwPTemperature()
    {
        return substr($this->rawDpTemperature, 1, 1);
    }

    /**
     * Return code figure of air temperature
     *
     * @return false|string
     */
    public function getCodeFigureDwPTemperature()
    {
        return substr($this->rawDpTemperature, 2, 3);
    }
}
