<?php

namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;

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

    public function isGroup(): bool
    {
        // TODO: Implement isGroup() method.
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
