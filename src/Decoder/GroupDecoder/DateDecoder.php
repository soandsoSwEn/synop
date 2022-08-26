<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class DateDecoder contains methods for decoding a group of date report
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DateDecoder implements GroupDecoderInterface
{
    /**
     * @var string Data on the date of the meteorological report
     */
    private $rawDate;

    private $i_w = [
        0 => ['Visual', 'm/s'],
        1 => ['Instrumental', 'm/s'],
        3 => ['Visual', 'knot'],
        4 => ['Instrumental', 'knot']
    ];

    public function __construct(string $rawDate)
    {
        $this->rawDate = $rawDate;
    }

    public function isGroup(ValidateInterface $validate): bool
    {
        return $validate->isValidGroup(get_class($this), [$this->getDay(), $this->getHour(), $this->getIw()]);
    }

    public function getDay(): string
    {
        return substr($this->rawDate, 0, 2);
    }

    public function getHour(): string
    {
        return substr($this->rawDate, 2, 2);
    }

    public function getIw(): ?array
    {
        $i_w = $this->getIwElement($this->rawDate);
        if (array_key_exists($i_w, $this->getIwData())) {
            return $this->getIwData()[$i_w];
        }

        return null;
    }

    private function getIwElement(): string
    {
        return substr($this->rawDate, 4, 1);
    }

    private function getIwData()
    {
        return $this->i_w;
    }
}
