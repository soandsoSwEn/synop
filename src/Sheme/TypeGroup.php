<?php


namespace Synop\Sheme;


use Exception;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\TypeDecoder;

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

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * @inheritDoc
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * @inheritDoc
     */
    public function setData(string $data): void
    {
        if (!empty($data)) {
            $this->rawTypeData = $data;
            $this->setDecoder(new TypeDecoder($this->rawTypeData));
            $this->setTypeGroup($this->getDecoder());
        } else {
            throw new Exception('Type group cannot be empty!');
        }
    }

    /**
     * @inheritDoc
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    public function setTypeGroup(GroupDecoderInterface $decoder) : void
    {
        $this->setType($decoder);
        $this->setSynop($decoder);
        $this->setShip($decoder);
    }

    /**
     * Sets value for type of weather report
     * @param GroupDecoderInterface $decoder
     */
    public function setType(GroupDecoderInterface $decoder) : void
    {
        $this->type = $decoder->getTypeValue();
    }

    /**
     * Sets value of whether the weather report type is Synop
     * @param GroupDecoderInterface $decoder
     */
    public function setSynop(GroupDecoderInterface $decoder) : void
    {
        $this->isSynop = $decoder->getIsSynopValue();
    }

    /**
     * Sets value of whether the weather report type is Ship
     * @param GroupDecoderInterface $decoder
     */
    public function setShip(GroupDecoderInterface $decoder) : void
    {
        $this->isShip = $decoder->getIsShipValue();
    }

    /**
     * Returns type of weather report
     * @return string|null
     */
    public function getType() : ?string
    {
        return $this->type;
    }

    /**
     * Returns information whether the weather report is Synop
     * @return bool
     * @throws Exception
     */
    public function isSynop() : bool
    {
        if (is_null($this->isSynop)) {
            throw new Exception('The type of weather report is not defined');
        }

        return $this->isSynop;
    }

    /**
     * Returns information whether the weather report is Ship
     * @return bool
     * @throws Exception
     */
    public function isShip() : bool
    {
        if (is_null($this->isShip)) {
            throw new Exception('The type of weather report is not defined');
        }

        return $this->isShip;
    }
}