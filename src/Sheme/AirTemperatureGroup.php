<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class AirTemperatureGroup contains methods for working with a group of air temperatures - 1SnTTT
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AirTemperatureGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Air temperature group data
     */
    private $rawAirTemperature;

    /**
     * @var GroupDecoderInterface Initialized decoder object for air temperature group
     */
    private $decoder;

    /**
     * @var null|int air temperature sign
     */
    private $sign;

    /**
     * @var null|float air temperature in tenths
     */
    private $temperature;

    /**
     * @var null|float The resulting signed air temperature
     */
    private $temperatureValue;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for the air temperature group
     *
     * @param string $data Air temperature group data
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawAirTemperature = $data;
            $this->decoder = new AirTemperatureDecoder($this->rawAirTemperature);
            $this->setAirTempGroup($this->decoder, $validate);
        } else {
            throw new Exception('AirTemperatureGroup group cannot be empty!');
        }
    }

    /**
     * Sets air temperature group data
     *
     * @param string $data temperature group data
     */
    public function setRawAirTemperature(string $data)
    {
        $this->rawAirTemperature = $data;
    }

    /**
     * @param GroupDecoderInterface $decoder Initialized decoder object for air temperature group
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets air temperature sign
     *
     * @param int $singValue air temperature sign
     */
    public function setSignValue(int $singValue)
    {
        $this->sign = $singValue;
    }

    /**
     * Sets air temperature in tenths value
     *
     * @param null|float $temperature air temperature value
     */
    public function setTemperatureData(?float $temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * Sets resulting signed air temperature
     *
     * @param null|float $temperatureValue resulting signed air temperature
     */
    public function setTemperatureValue(?float $temperatureValue)
    {
        $this->temperatureValue = $temperatureValue;
    }

    /**
     * Returns air temperature group data
     *
     * @return string Air temperature group data
     */
    public function getRawAirTemperature(): string
    {
        return $this->rawAirTemperature;
    }

    /**
     * Returns Decoder object for air temperature group
     *
     * @return GroupDecoderInterface
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns air temperature sign
     *
     * @return null|int air temperature sign
     */
    public function getSignValue(): ?int
    {
        return $this->sign;
    }

    /**
     * Returns air temperature in tenths
     *
     * @return null|float air temperature
     */
    public function getTemperatureData(): ?float
    {
        return $this->temperature;
    }

    /**
     * Returns resulting signed air temperature
     *
     * @return null|float resulting signed air temperature
     */
    public function getTemperatureValue(): ?float
    {
        return $this->temperatureValue;
    }

    /**
     * Sets the values for the air temperature group variables
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for air temperature group
     * @param ValidateInterface $validate Object for weather data validation
     */
    public function setAirTempGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): void
    {
        if ($this->isAirTempGroup($decoder, $validate)) {
            $this->setSign($decoder);
            $this->setTemperature($decoder);
            $this->buildAirTemperature($this->sign, $this->temperature);
        } else {
            $this->setSign(null);
            $this->setTemperature(null);
            $this->buildAirTemperature(null, null);
        }
    }

    /**
     * Returns whether the given group is an air temperature group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for air temperature group
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool
     */
    public function isAirTempGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate, $this->getGroupIndicator());
    }

    /**
     * Sets the air temperature sign value
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setSign(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->sign = null;
        } else {
            $this->sign = $decoder->getSignTemperature();
        }
    }

    /**
     * Sets the air temperature value
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setTemperature(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->temperature = null;
        } else {
            $this->temperature = $decoder->getTemperatureValue();
        }
    }

    /**
     * Sets the full value of the signed air temperature
     *
     * @param int|null $sign air temperature sign
     * @param float|null $airTemp air temperature in tenths
     */
    public function buildAirTemperature(?int $sign, ?float $airTemp)
    {
        if (!is_null($sign) && !is_null($airTemp)) {
            if ($sign == 1) {
                $this->temperatureValue = $airTemp * -1;
            } else {
                $this->temperatureValue = $airTemp;
            }
        } else {
            $this->temperatureValue = null;
        }
    }

    /**
     * Returns the indicator of the entire weather report group
     *
     * @return string Group indicator
     */
    public function getGroupIndicator(): string
    {
        return '1SnTTT';
    }
}
