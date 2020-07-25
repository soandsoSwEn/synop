<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
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

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * Sets the initial data for the air temperature group
     *
     * @param string $data Air temperature group data
     * @throws Exception
     */
    public function setData(string $data)
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
     * Sets the values ​​for the air temperature group variables
     *
     * @param GroupDecoderInterface $decoder
     */
    public function setAirTempGroup(GroupDecoderInterface $decoder) : void
    {
        if ($this->isAirTempGroup($decoder)) {
            $this->setSign($decoder);
            $this->setTemperature($decoder);
        } else {
            $this->setSign(null);
            $this->setTemperature(null);
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
}