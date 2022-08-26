<?php

namespace Soandso\Synop\Sheme;

use Exception;
use Soandso\Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class CloudPresentGroup contains methods for working with the cloud present group - 8NhClCmCH
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class CloudPresentGroup implements GroupInterface
{
    /**
     * @var string Code block of cloud present group
     */
    private $rawCloudPresent;

    /**
     * @var GroupDecoderInterface Decoder object for cloud present group
     */
    private $decoder;

    /**
     * @var string Amount of low cloud code symbol
     */
    private $amountLowCloudSymbol;

    /**
     * @var string Amount of low cloud value
     */
    private $amountLowCloud;

    /**
     * @var string Form of low cloud code symbol
     */
    private $formLowCloudSymbol;

    /**
     * @var string Form of low cloud value
     */
    private $formLowCloud;

    /**
     * @var string Form of medium cloud code symbol
     */
    private $formMediumCloudSymbol;

    /**
     * @var string Form of medium cloud value
     */
    private $formMediumCloud;

    /**
     * @var string Form of high cloud code symbol
     */
    private $formHighCloudSymbol;

    /**
     * @var string Form of high cloud value
     */
    private $formHighCloud;

    public function __construct(string $data, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
    }

    /**
     * Sets the initial data for cloud present group section one
     *
     * @param string $data Code block of cloud present group
     * @param ValidateInterface $validate Object for weather data validation
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawCloudPresent = $data;
            $this->setDecoder(new CloudPresentDecoder($this->rawCloudPresent));
            $this->setCloudPresentGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('PresentWeatherGroup group cannot be empty!');
        }
    }

    /**
     * Sets an initialized decoder object for cloud present group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for cloud present group
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets amount of low or middle cloud value
     *
     * @param string|null $amountLowCloud Amount of low or middle cloud value
     */
    public function setAmountLowCloudValue(?string $amountLowCloud): void
    {
        $this->amountLowCloud = $amountLowCloud;
    }

    /**
     * Sets form of low cloud code symbol value
     *
     * @param string|null $formLowCloudSymbol Form of low cloud code symbol
     */
    public function setFormLowCloudSymbolValue(?string $formLowCloudSymbol): void
    {
        $this->formLowCloudSymbol = $formLowCloudSymbol;
    }

    /**
     * Sets form of low cloud value
     *
     * @param string|null $formLowCloud Form of low cloud value
     */
    public function setFormLowCloudValue(?string $formLowCloud): void
    {
        $this->formLowCloud = $formLowCloud;
    }

    /**
     * Sets form of medium cloud code symbol
     *
     * @param string|null $formMediumCloudSymbol Form of medium cloud code symbol
     */
    public function setFormMediumCloudSymbolValue(?string $formMediumCloudSymbol): void
    {
        $this->formMediumCloudSymbol = $formMediumCloudSymbol;
    }

    /**
     * Sets form of medium cloud value
     *
     * @param string|null $formMediumCloud Form of medium cloud value
     */
    public function setFormMediumCloudValue(?string $formMediumCloud): void
    {
        $this->formMediumCloud = $formMediumCloud;
    }

    /**
     * Sets form of high cloud code symbol
     *
     * @param string|null $formHighCloudSymbol Form of high cloud code symbol
     */
    public function setFormHighCloudSymbolValue(?string $formHighCloudSymbol): void
    {
        $this->formHighCloudSymbol = $formHighCloudSymbol;
    }

    /**
     * Sets form of high cloud value
     *
     * @param string|null $formHighCloud Form of high cloud value
     */
    public function setFormHighCloudValue(?string $formHighCloud): void
    {
        $this->formHighCloud = $formHighCloud;
    }

    /**
     * Returns decoder object for cloud present group
     *
     * @return GroupDecoderInterface Initialized decoder object for cloud present group
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns amount of low or middle cloud value
     *
     * @return string Amount of low or middle cloud value
     */
    public function getAmountLowCloudValue(): string
    {
        return $this->amountLowCloud;
    }

    /**
     * Returns form of low cloud code symbol
     *
     * @return string Form of low cloud code symbol
     */
    public function getFormLowCloudSymbolValue(): string
    {
        return $this->formLowCloudSymbol;
    }

    /**
     * Returns form of low cloud value
     *
     * @return string Form of low cloud value
     */
    public function getFormLowCloudValue(): string
    {
        return $this->formLowCloud;
    }

    /**
     * Returns form of medium cloud code symbol
     *
     * @return string Form of medium cloud code symbol
     */
    public function getFormMediumCloudSymbolValue(): string
    {
        return $this->formMediumCloudSymbol;
    }

    /**
     * Returns form of medium cloud value
     *
     * @return string Form of medium cloud value
     */
    public function getFormMediumCloudValue(): string
    {
        return $this->formMediumCloud;
    }

    /**
     * Returns form of high cloud code symbol
     *
     * @return string Form of high cloud code symbol
     */
    public function getFormHighCloudSymbolValue(): string
    {
        return $this->formHighCloudSymbol;
    }

    /**
     * Returns form of high cloud value
     *
     * @return string Form of high cloud value
     */
    public function getFormHighCloudValue(): string
    {
        return $this->formHighCloud;
    }

    /**
     * Sets the parameters of cloud present group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for cloud present group
     * @param ValidateInterface $validate Object for weather data validation
     */
    public function setCloudPresentGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isCloudPresentGroup($decoder, $validate)) {
            $this->setAmountLowCloudSymbol($decoder);
            $this->setAmountLowCloud($decoder);
            $this->setFormLowCloudSymbol($decoder);
            $this->setFormLowCloud($decoder);
            $this->setFormMediumCloudSymbol($decoder);
            $this->setFormMediumCloud($decoder);
            $this->setFormHighCloudSymbol($decoder);
            $this->setFormHighCloud($decoder);
        } else {
            $this->setAmountLowCloudSymbol(null);
            $this->setAmountLowCloud(null);
            $this->setFormLowCloudSymbol(null);
            $this->setFormLowCloud(null);
            $this->setFormMediumCloudSymbol(null);
            $this->setFormMediumCloud(null);
            $this->setFormHighCloudSymbol(null);
            $this->setFormHighCloud(null);
        }
    }

    /**
     * Validates a block of code against a cloud present group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object for cloud present group
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool
     */
    public function isCloudPresentGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate);
    }

    /**
     * Sets the symbolic value of amount low cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setAmountLowCloudSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->amountLowCloudSymbol = null;
        } else {
            $this->amountLowCloudSymbol = $decoder->getAmountLowCloudSymbol();
        }
    }

    /**
     * Sets the value of amount low cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setAmountLowCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setAmountLowCloudValue(null);
        } else {
            $this->setAmountLowCloudValue($decoder->getAmountLowCloud());
        }
    }

    /**
     * Sets the symbolic value of form low cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormLowCloudSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormLowCloudSymbolValue(null);
        } else {
            $this->setFormLowCloudSymbolValue($decoder->getFormLowCloudSymbol());
        }
    }

    /**
     * Sets the value of form low cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormLowCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormLowCloudValue(null);
        } else {
            $this->setFormLowCloudValue($decoder->getFormLowCloud());
        }
    }

    /**
     * Sets the symbolic value of form medium cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormMediumCloudSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormMediumCloudSymbolValue(null);
        } else {
            $this->setFormMediumCloudSymbolValue($decoder->getFormMediumCloudSymbol());
        }
    }

    /**
     * Sets the value of form medium cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormMediumCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormMediumCloudValue(null);
        } else {
            $this->setFormMediumCloudValue($decoder->getFormMediumCloud());
        }
    }

    /**
     * Sets the symbolic value of form high cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormHighCloudSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormHighCloudSymbolValue(null);
        } else {
            $this->setFormHighCloudSymbolValue($decoder->getFormHighCloudSymbol());
        }
    }

    /**
     * Sets the value of form high cloud
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormHighCloud(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setFormHighCloudValue(null);
        } else {
            $this->setFormHighCloudValue($decoder->getFormHighCloud());
        }
    }
}
