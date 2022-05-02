<?php

namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\DateDecoder;
use Exception;
use Synop\Fabrication\ValidateInterface;

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

    public function __construct(string $date, ValidateInterface $validate)
    {
        $this->setData($date, $validate);
    }
    
    public function setData(string $date, ValidateInterface $validate) : void
    {
        if(!empty($date)) {
            $this->raw_date = $date;
            $this->setDecoder(new DateDecoder($this->raw_date));
            $this->setDateGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('Date group cannot be empty!');
        }
    }

    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    public function setDayValue(string $day) : void
    {
        $this->day = $day;
    }

    public function setHourValue(string $hour) : void
    {
        $this->hour = $hour;
    }

    public function setIwValue(array $iw) : void
    {
        $this->iw = $iw;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    public function getDayValue() : string
    {
        return $this->day;
    }

    public function getHourValue() : string
    {
        return $this->hour;
    }

    public function getIwValue() : array
    {
        return $this->iw;
    }
    
    public function setDateGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        $this->isDateGroup($decoder, $validate);
        $this->setDay($decoder);
        $this->setHour($decoder);
        $this->setIw($decoder);
    }

    public function setDay(GroupDecoderInterface $decoder) : void
    {
        $this->setDayValue($decoder->getDay());
    }
    
    public function setHour(GroupDecoderInterface $decoder) : void
    {
        $this->setHourValue($decoder->getHour());
    }
    
    public function setIw(GroupDecoderInterface $decoder) : void
    {
        $this->setIwValue($decoder->getIw());
    }

    public function isDateGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        return $decoder->isGroup($validate);
    }
}
