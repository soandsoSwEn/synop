<?php

namespace Soandso\Synop\Sheme;

use Exception;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\TypeDecoder;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class TypeGroup contains methods for working with a group such as a weather report AAXX/BBXX
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class TypeGroup implements GroupInterface
{
    /**
     * @var string Type of weather report raw data
     */
    private $rawTypeData;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var string Type of weather report
     */
    private $type;

    /**
     * @var bool Whether the weather report type is Synop
     */
    private $isSynop;

    /**
     * @var bool Whether the weather report type is Ship
     */
    private $isShip;

    public function __construct(string $data, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
    }

    /**
     * Sets an initialized decoder object type group block
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @return void
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets the initial data for type group block
     *
     * @param string $data Code figure of type of weather report group
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawTypeData = $data;
            $this->setDecoder(new TypeDecoder($this->rawTypeData));
            $this->setTypeGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('Type group cannot be empty!');
        }
    }

    /**
     * Returns an initialized decoder object type group block
     *
     * @return GroupDecoderInterface Initialized decoder object
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Sets the parameters of a type group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setTypeGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): void
    {
        if ($this->isTypeGroup($decoder, $validate)) {
            $this->setType($decoder);
            $this->setSynop($decoder);
            $this->setShip($decoder);
        } else {
            throw new Exception('Error determining the type of weather report');
        }
    }

    /**
     * Sets value for type of weather report
     *
     * @param GroupDecoderInterface $decoder
     */
    public function setType(GroupDecoderInterface $decoder): void
    {
        $this->type = $decoder->getTypeValue();
    }

    /**
     * Sets value of whether the weather report type is Synop
     *
     * @param GroupDecoderInterface $decoder
     */
    public function setSynop(GroupDecoderInterface $decoder): void
    {
        $this->isSynop = $decoder->getIsSynopValue();
    }

    /**
     * Sets value of whether the weather report type is Ship
     *
     * @param GroupDecoderInterface $decoder
     */
    public function setShip(GroupDecoderInterface $decoder): void
    {
        $this->isShip = $decoder->getIsShipValue();
    }

    /**
     * Returns type of weather report
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Returns information whether the weather report is Synop
     *
     * @return bool
     * @throws Exception
     */
    public function isSynop(): bool
    {
        if (is_null($this->isSynop)) {
            throw new Exception('The type of weather report is not defined');
        }

        return $this->isSynop;
    }

    /**
     * Returns information whether the weather report is Ship
     *
     * @return bool
     * @throws Exception
     */
    public function isShip(): bool
    {
        if (is_null($this->isShip)) {
            throw new Exception('The type of weather report is not defined');
        }

        return $this->isShip;
    }

    /**
     * Returns whether the given group is a type report
     *
     * @param GroupDecoderInterface $decoder
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isTypeGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate);
    }
}
