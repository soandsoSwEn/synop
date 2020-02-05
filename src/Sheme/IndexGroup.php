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
     * @var type string Meteorological Station Area Number
     */
    private $area_number;
    
    /**
     * @var type string Meteorological station number within the area
     */
    private $station_number;
    
    /**
     * @var type string International weather station index
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
            $this->decoder = new IndexDecoder($this->raw_index);
            $this->setIndexGroup($this->decoder);
        } else {
            throw new Exception('Station index group cannot be empty!');
        }
    }
    
    public function setIndexGroup(GroupDecoderInterface $decoder) : void
    {
        $this->setAreaNumber($decoder);
        $this->setStationNumber($decoder);
        $this->setStationIndex($decoder);
    }
    
    public function setAreaNumber(GroupDecoderInterface $decoder) : void
    {
        $this->area_number = $decoder->getArea();
    }
    
    public function setStationNumber(GroupDecoderInterface $decoder) : void
    {
        $this->station_number = $decoder->getNumber();
    }
    
    public function setStationIndex(GroupDecoderInterface $decoder) : void
    {
        $this->station_index = $decoder->getIndex();
    }
}
