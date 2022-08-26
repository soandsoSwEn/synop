<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Fabrication\ValidateInterface;
use Soandso\Synop\Sheme\GroupInterface;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Exception;

/**
 * Class IndexGroup contains methods for working with station index group Section 0 - IIiii
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class IndexGroup implements GroupInterface
{
    /**
     * @var string Code figure of station index group
     */
    private $rawIndex;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;
    
    /**
     * @var string Meteorological Station Area Number
     */
    private $areaNumber;
    
    /**
     * @var string Meteorological station number within the area
     */
    private $stationNumber;
    
    /**
     * @var string International weather station index
     */
    private $stationIndex;
    
    public function __construct(string $index, ValidateInterface $validate)
    {
        $this->setData($index, $validate);
    }

    /**
     * Sets the initial data for station index group
     *
     * @param string $index Station index source code
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $index, ValidateInterface $validate): void
    {
        if (!empty($index)) {
            $this->rawIndex = $index;
            $this->setDecoder(new IndexDecoder($this->rawIndex));
            $this->setIndexGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('Station index group cannot be empty!');
        }
    }

    /**
     * Sets an initialized decoder object station index group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets area station data
     *
     * @param string $areaNumber Area station
     * @return void
     */
    public function setAreaNumberValue(string $areaNumber): void
    {
        $this->areaNumber = $areaNumber;
    }

    /**
     * Sets station number data
     *
     * @param string $stationNumber Station number
     * @return void
     */
    public function setStationNumberValue(string $stationNumber): void
    {
        $this->stationNumber = $stationNumber;
    }

    /**
     * Sets station index data
     *
     * @param string $stationIndex Station index
     * @return void
     */
    public function setStationIndexValue(string $stationIndex): void
    {
        $this->stationIndex = $stationIndex;
    }

    /**
     * Returns initialized decoder object for station index group
     *
     * @return GroupDecoderInterface Initialized decoder object
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns area station data
     *
     * @return string
     */
    public function getAreaNumberValue(): string
    {
        return $this->areaNumber;
    }

    /**
     * Returns station number data
     *
     * @return string
     */
    public function getStationNumberValue(): string
    {
        return $this->stationNumber;
    }

    /**
     * Returns station index data
     *
     * @return string
     */
    public function getStationIndexValue(): string
    {
        return $this->stationIndex;
    }

    /**
     * Sets parameters of station index group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setIndexGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): void
    {
        if ($decoder->isGroup($validate)) {
            $this->setAreaNumber($decoder);
            $this->setStationNumber($decoder);
            $this->setStationIndex($decoder);
        } else {
            throw new Exception('Error specifying station number');
        }
    }

    /**
     * Sets area station group data
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @return void
     */
    public function setAreaNumber(GroupDecoderInterface $decoder): void
    {
        $this->setAreaNumberValue($decoder->getArea());
    }

    /**
     * Sets station number group data
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @return void
     */
    public function setStationNumber(GroupDecoderInterface $decoder): void
    {
        $this->setStationNumberValue($decoder->getNumber());
    }

    /**
     * Sets station index group data
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @return void
     */
    public function setStationIndex(GroupDecoderInterface $decoder): void
    {
        $this->setStationIndexValue($decoder->getIndex());
    }
}
