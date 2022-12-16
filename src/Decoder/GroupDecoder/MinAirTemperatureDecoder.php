<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Soandso\Synop\Fabrication\ValidateInterface;

class MinAirTemperatureDecoder extends AirTemperatureDecoder
{
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
     * Returns indicator and description of group indicator for air temperatures - 1SnTTT
     *
     * @return string[] Indicator and description of air temperature
     */
    public function getIndicatorGroup(): array
    {
        return ['2' => 'Indicator'];
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
        return ['TnTnTn' => 'Minimum temperature in tenths of a degree'];
    }
}
