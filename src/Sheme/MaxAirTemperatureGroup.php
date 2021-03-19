<?php


namespace Synop\Sheme;


use Exception;
use Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;

/**
 * Class MaxAirTemperatureGroup contains methods for working with a group of maximum air temperatures
 * of section three - 333 1SnTxTxTx
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class MaxAirTemperatureGroup extends AirTemperatureGroup
{
    public function __construct(string $data)
    {
        parent::__construct($data);
    }

    /**
     * Sets the initial data for the maximum air temperature group
     * @param string $data Maximum air temperature group data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if(!empty($data)) {
            $this->setRawAirTemperature($data);
            $this->setDecoder(new AirTemperatureDecoder($this->getRawAirTemperature()));
            $this->setAirTempGroup($this->getDecoder());
        } else {
            throw new Exception('Maximum Air Temperature Group group cannot be empty!');
        }
    }
}