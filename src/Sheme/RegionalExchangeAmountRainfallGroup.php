<?php


namespace Synop\Sheme;


use Exception;
use Synop\Decoder\GroupDecoder\AmountRainfallDecoder;

/**
 * Class RegionalExchangeAmountRainfallGroup contains methods for working with a group of amount of rainfall
 * of section three - 333 6RRRtr
 *
 * When precipitation data are to be exchanged in time periods of three hours or other periods required
 * for regional exchange, this group shall be included in Section 3.
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class RegionalExchangeAmountRainfallGroup extends AmountRainfallGroup
{
    public function __construct(string $data)
    {
        parent::__construct($data);
    }

    /**
     * Sets the initial data for the amount of rainfall group in section three
     * @param string $data Amount of rainfall group data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->setRawAmountRainfall($data);
            $this->setDecoder(new AmountRainfallDecoder($this->getRawAmountRainfall()));
            $this->setAmountRainfallGroup($this->getDecoder());
        } else {
            throw new Exception('RegionalExchangeAmountRainfallGroup group cannot be empty!');
        }
    }
}