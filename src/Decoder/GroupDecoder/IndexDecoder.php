<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class IndexDecoder contains methods for decoding station index
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class IndexDecoder implements GroupDecoderInterface
{
    /**
     * @var string Station number source code
     */
    private $rawIndex;

    public function __construct(string $rawIndex)
    {
        $this->rawIndex = $rawIndex;
    }

    public function isGroup(ValidateInterface $validate): bool
    {
        return $validate->isValidGroup(get_class($this), [$this->getArea(), $this->getNumber()]);
    }

    /**
     * Return area station
     *
     * @return string
     */
    public function getArea(): string
    {
        return substr($this->rawIndex, 0, 2);
    }

    /**
     * Return station number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return substr($this->rawIndex, 2, 3);
    }

    /**
     * Return station index
     *
     * @return string
     */
    public function getIndex(): string
    {
        return $this->rawIndex;
    }
}
