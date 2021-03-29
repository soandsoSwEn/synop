<?php


namespace Synop\Sheme;

use Synop\Decoder\DecoderInterface;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;
use Synop\Sheme\GroupInterface;
use Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Exception;


/**
 * Class AirTemperatureGroup contains methods for working with a group of air temperatures - 1SnTTT
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AirTemperatureGroup implements GroupInterface
{
    /**
     * @var string Air temperature group data
     */
    private $raw_air_temperature;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var int air temperature sign
     */
    private $sign;

    /**
     * @var float air temperature in tenths
     */
    private $temperature;

    /**
     * @var float The resulting signed air temperature
     */
    private $temperatureValue;

    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for the air temperature group
     *
     * @param string $data Air temperature group data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if(!empty($data)) {
            $this->raw_air_temperature = $data;
            $this->decoder = new AirTemperatureDecoder($this->raw_air_temperature);
            $this->setAirTempGroup($this->decoder);
        } else {
            throw new Exception('AirTemperatureGroup group cannot be empty!');
        }
    }

    /**
     * Sets the value of the Unit object
     * @param UnitInterface $unit class instance of the entity Unit
     */
    public function setUnit(UnitInterface $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * Returns the value of the Unit object
     * @return UnitInterface
     */
    public function getUnit() : UnitInterface
    {
        return $this->unit;
    }

    /**
     * Returns unit data for the weather report group
     * @return array|null
     */
    public function getUnitValue() : ?array
    {
        return $this->getUnit()->getUnitByGroup(get_class($this));
    }

    /**
     * Sets air temperature group data
     * @param string $data temperature group data
     */
    public function setRawAirTemperature(string $data)
    {
        $this->raw_air_temperature = $data;
    }

    /**
     * @param GroupDecoderInterface $decoder
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets air temperature sign
     * @param int $singValue air temperature sign
     */
    public function setSignValue(int $singValue)
    {
        $this->sign = $singValue;
    }

    /**
     * Sets air temperature in tenths value
     * @param float $temperature air temperature value
     */
    public function setTemperatureData(float $temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * Sets resulting signed air temperature
     * @param float $temperatureValue resulting signed air temperature
     */
    public function setTemperatureValue(float $temperatureValue)
    {
        $this->temperatureValue = $temperatureValue;
    }

    /**
     * Returns air temperature group data
     * @return string Air temperature group data
     */
    public function getRawAirTemperature() : string
    {
        return $this->raw_air_temperature;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns air temperature sign
     * @return int air temperature sign
     */
    public function getSignValue() : int
    {
        return $this->sign;
    }

    /**
     * Returns air temperature in tenths
     * @return float air temperature
     */
    public function getTemperatureData() : float
    {
        return $this->temperature;
    }

    /**
     * Returns resulting signed air temperature
     * @return float resulting signed air temperature
     */
    public function getTemperatureValue() : float
    {
        return $this->temperatureValue;
    }

    /**
     * Sets the values ​​for the air temperature group variables
     *
     * @param GroupDecoderInterface $decoder
     */
    public function setAirTempGroup(GroupDecoderInterface $decoder) : void
    {
        if ($this->isAirTempGroup($decoder)) {
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
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isAirTempGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the air temperature sign value
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setSign(?GroupDecoderInterface $decoder) : void
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
    public function setTemperature(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->temperature = null;
        } else {
            $this->temperature = $decoder->getTemperatureValue();
        }
    }

    /**
     * Sets the full value of the signed air temperature
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
}