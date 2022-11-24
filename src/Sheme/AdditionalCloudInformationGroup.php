<?php

namespace Soandso\Synop\Sheme;

use Exception;
use Soandso\Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class AdditionalCloudInformationGroup contains methods for working with a group Additional cloud information
 * transfer data of section three - 333 8NsChshs
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AdditionalCloudInformationGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Additional cloud information transfer group data
     */
    private $rawAdditionCloudInformation;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var string Code figure of amount of individual cloud layer
     */
    private $codeAmountCloud;

    /**
     * @var string Amount of individual cloud layer
     */
    private $amountCloud;

    /**
     * @var string Form of Cloud
     */
    private $formCloud;

    /**
     * @var string Code figure of height of base of cloud layer
     */
    private $codeHeightCloud;

    /**
     * @var array Height of base of cloud layer
     */
    private $heightCloud;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for additional cloud information transfer group
     *
     * @param string $data Additional cloud information transfer group data
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->setRawAdditionCloudInformation($data);
            $this->setDecoder(new AdditionalCloudInformationDecoder($this->getRawAdditionCloudInformation()));
            $this->setAdditionalCloudInformationGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('AdditionalCloudInformationGroup group cannot be empty!');
        }
    }

    /**
     * Sets additional cloud information transfer group data
     *
     * @param string $data Additional cloud information transfer group data
     */
    public function setRawAdditionCloudInformation(string $data): void
    {
        $this->rawAdditionCloudInformation = $data;
    }

    /**
     * Sets an initialized decoder object for additional cloud information transfer group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for additional cloud information transfer group
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets code figure of amount of individual cloud layer value
     *
     * @param string|null $codeAmountCloud Code figure of amount of individual cloud layer
     */
    public function setCodeAmountCloudValue(?string $codeAmountCloud): void
    {
        $this->codeAmountCloud = $codeAmountCloud;
    }

    /**
     * Sets amount of individual cloud layer value
     *
     * @param string|null $amountCloud Amount of individual cloud layer
     */
    public function setAmountCloudValue(?string $amountCloud): void
    {
        $this->amountCloud = $amountCloud;
    }

    /**
     * Sets Form of Cloud value
     *
     * @param string|null $formCloud Form of Cloud
     */
    public function setFormCloudValue(?string $formCloud): void
    {
        $this->formCloud = $formCloud;
    }

    /**
     * Sets code figure of height of base of cloud layer value
     *
     * @param string|null $codeHeightCloud Code figure of height of base of cloud layer
     */
    public function setCodeHeightCloudValue(?string $codeHeightCloud): void
    {
        $this->codeHeightCloud = $codeHeightCloud;
    }

    /**
     * Sets Height of base of cloud layer value
     *
     * @param array|null $codeHeight Height of base of cloud layer
     */
    public function setHeightCloudValue(?array $codeHeight): void
    {
        $this->heightCloud = $codeHeight;
    }

    /**
     * Returns additional cloud information transfer group data
     *
     * @return string Additional cloud information transfer group data
     */
    public function getRawAdditionCloudInformation(): string
    {
        return $this->rawAdditionCloudInformation;
    }

    /**
     * Returns Initialized decoder object for additional cloud information transfer group
     *
     * @return GroupDecoderInterface Initialized decoder object for additional cloud information transfer group
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns code figure of amount of individual cloud layer value
     *
     * @return string|null Code figure of amount of individual cloud layer
     */
    public function getCodeAmountCloudValue(): ?string
    {
        return $this->codeAmountCloud;
    }

    /**
     * Returns amount of individual cloud layer value
     *
     * @return string|null Amount of individual cloud layer
     */
    public function getAmountCloudValue(): ?string
    {
        return $this->amountCloud;
    }

    /**
     * Returns Form of Cloud value
     *
     * @return string|null Form of Cloud
     */
    public function getFormCloudValue(): ?string
    {
        return $this->formCloud;
    }

    /**
     * Returns Code figure of height of base of cloud layer value
     *
     * @return string|null Code figure of height of base of cloud layer
     */
    public function getCodeHeightCloudValue(): ?string
    {
        return $this->codeHeightCloud;
    }

    /**
     * Returns height of base of cloud layer value
     *
     * @return array|null Height of base of cloud layer
     */
    public function getHeightCloudValue(): ?array
    {
        return $this->heightCloud;
    }

    public function setAdditionalCloudInformationGroup(
        GroupDecoderInterface $decoder,
        ValidateInterface $validate
    ): void {
        if ($this->isAddCloudGroup($decoder, $validate)) {
            $this->setCodeAmountCloud($decoder);
            $this->setAmountCloud($decoder);
            $this->setFormCloud($decoder);
            $this->setCodeHeightCloud($decoder);
            $this->setHeightCloud($decoder);
        } else {
            $this->setCodeAmountCloud(null);
            $this->setAmountCloud(null);
            $this->setFormCloud(null);
            $this->setCodeHeightCloud(null);
            $this->setHeightCloud(null);
        }
    }

    /**
     * Returns whether the given group is an additional cloud information transfer group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool
     */
    public function isAddCloudGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate, $this->getGroupIndicator());
    }

    /**
     * Sets code figure of amount of individual cloud layer
     *
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setCodeAmountCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setCodeAmountCloudValue(null);
        } else {
            $this->setCodeAmountCloudValue($decoder->getCodeAmountCloud());
        }
    }

    /**
     * Sets amount of individual cloud layer
     *
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setAmountCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setAmountCloudValue(null);
        } else {
            $this->setAmountCloudValue($decoder->getAmountCloud());
        }
    }

    /**
     * Sets Form of Cloud
     *
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setFormCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormCloudValue(null);
        } else {
            $this->setFormCloudValue($decoder->getFormCloud());
        }
    }

    /**
     * Sets code figure of height of base of cloud layer
     *
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setCodeHeightCloud(?GroupDecoderInterface $decoder)
    {
        if (is_null($decoder)) {
            $this->setCodeHeightCloudValue(null);
        } else {
            $this->setCodeHeightCloudValue($decoder->getCodeHeightCloud());
        }
    }

    /**
     * Sets Height of base of cloud layer
     *
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setHeightCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setHeightCloudValue(null);
        } else {
            $this->setHeightCloudValue($decoder->getHeightCloud());
        }
    }

    /**
     * Returns the indicator of the entire weather report group
     *
     * @return string Group indicator
     */
    public function getGroupIndicator(): string
    {
        return '8NsChshs';
    }
}
