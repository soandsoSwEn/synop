<?php

namespace Synop\Sheme;

use Synop\Sheme\GroupInterface;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\DateDecoder;
use Exception;

/**
 * Description of DateGroup
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DateGroup implements GroupInterface
{
    private $raw_date;
    
    private $decoder;

    private $day;
    
    private $hour;
    
    private $iw;

    public function __construct(string $date)
    {
        $this->setData($date);
    }
    
    public function setData(string $date) : void
    {
        if(!empty($date)) {
            $this->raw_date = $date;
            $this->decoder = new DateDecoder($this->raw_date);
            $this->setDateGroup($this->decoder);
        } else {
            throw new Exception('Date group cannot be empty!');
        }
    }
    
    public function setDateGroup(GroupDecoderInterface $decoder)
    {
        $this->setDay($decoder);
        $this->setHour($decoder);
        $this->setIw($decoder);
    }

    public function setDay(GroupDecoderInterface $decoder) : void
    {
        $this->day = $decoder->getDay();
    }
    
    public function setHour(GroupDecoderInterface $decoder) : void
    {
        $this->hour = $decoder->getHour();
    }
    
    public function setIw(GroupDecoderInterface $decoder) : void
    {
        $this->iw = $decoder->getIw();
    }
}