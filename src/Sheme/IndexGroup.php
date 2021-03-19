<?php

namespace Synop\Sheme;

use Synop\Sheme\GroupInterface;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\IndexDecoder;
use Exception;

/**
 * Description of IndexGroup
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class IndexGroup implements GroupInterface
{
    private $raw_index;
    
    private $decoder;
    
    /**
     * @var string Meteorological Station Area Number
     */
    private $area_number;
    
    /**
     * @var string Meteorological station number within the area
     */
    private $station_number;
    
    /**
     * @var string International weather station index
     */
    private $station_index;
    
    public function __construct(string $index)
    {
        $this->setData($index);
    }
    
    public function setData(string $index) : void
    {
        if(!empty($index)) {
            $this->raw_index = $index;
            $this->setDecoder(new IndexDecoder($this->raw_index));
            $this->setIndexGroup($this->getDecoder());
        } else {
            throw new Exception('Station index group cannot be empty!');
        }
    }

    /**
     * @param GroupDecoderInterface $decoder
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    public function setAreaNumberValue(string $areaNumber) : void
    {
        $this->area_number = $areaNumber;
    }

    public function setStationNumberValue(string $stationNumber) : void
    {
        $this->station_number = $stationNumber;
    }

    public function setStationIndexValue(string $stationIndex) : void
    {
        $this->station_index = $stationIndex;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    public function getAreaNumberValue() : string
    {
        return $this->area_number;
    }

    public function getStationNumberValue() : string
    {
        return $this->station_number;
    }

    public function getStationIndexValue() : string
    {
        return $this->station_index;
    }
    
    public function setIndexGroup(GroupDecoderInterface $decoder) : void
    {
        $this->setAreaNumber($decoder);
        $this->setStationNumber($decoder);
        $this->setStationIndex($decoder);
    }
    
    public function setAreaNumber(GroupDecoderInterface $decoder) : void
    {
        $this->setAreaNumberValue($decoder->getArea());
    }
    
    public function setStationNumber(GroupDecoderInterface $decoder) : void
    {
        $this->setStationNumberValue($decoder->getNumber());
    }
    
    public function setStationIndex(GroupDecoderInterface $decoder) : void
    {
        $this->setStationIndexValue($decoder->getIndex());
    }
}
