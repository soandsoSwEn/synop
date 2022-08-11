<?php

namespace Soandso\Synop\Decoder\GroupDecoder;


use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Description of IndexDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class IndexDecoder implements GroupDecoderInterface
{
    private $raw_index;
    
    public function __construct(string $raw_index)
    {
        $this->raw_index = $raw_index;
    }

    public function isGroup(ValidateInterface $validate): bool
    {
        return $validate->isValidGroup(get_class($this), [$this->getArea(), $this->getNumber()]);
    }

    public function getArea() : string
    {
        return substr($this->raw_index, 0, 2);
    }
    
    public function getNumber() : string
    {
        return substr($this->raw_index, 2, 3);
    }
    
    public function getIndex() : string
    {
        return $this->raw_index;
    }
}
