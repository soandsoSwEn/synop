<?php

namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;

/**
 * Description of DateDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DateDecoder implements GroupDecoderInterface
{
    private $raw_date;
    
    private $i_w = [
        0 => ['Visual', 'm/s'],
        1 => ['Instrumental', 'm/s'],
        3 => ['Visual', 'knot'],
        4 => ['Instrumental', 'knot']
    ];
    
    public function __construct(string $raw_date)
    {
        $this->raw_date = $raw_date;
    }

    public function isGroup(): bool
    {
        // TODO: Implement isGroup() method.
    }

    public function getDay() : string
    {
        return substr($this->raw_date, 0, 2);
    }
    
    public function getHour() : string
    {
        return substr($this->raw_date, 2, 2);
    }
    
    public function getIw()
    {
        $i_w = $this->getIwElement($this->raw_date);
        if(array_key_exists($i_w, $this->getIwData())) {
            return $this->getIwData()[$i_w];
        }
    }
    
    private function getIwElement(string $raw_data) : string
    {
        return substr($raw_data, 4, 1);
    }
       
    private function getIwData()
    {
        return $this->i_w;
    }
}
