<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Sheme\GroupInterface;


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
     * @var GroupDecoderInterface
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

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * @param string $data Code block of cloud present group
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->rawCloudPresent = $data;
            $this->decoder = new CloudPresentDecoder($this->rawCloudPresent);
            $this->setCloudPresentGroup($this->decoder);
        } else {
            throw new Exception('PresentWeatherGroup group cannot be empty!');
        }
    }

    /**
     * Sets the parameters of cloud present group
     * @param GroupDecoderInterface $decoder
     */
    public function setCloudPresentGroup(GroupDecoderInterface $decoder)
    {
        if ($this->isCloudPresentGroup($decoder)) {
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
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isCloudPresentGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the symbolic value of amount low cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setAmountLowCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->amountLowCloudSymbol = null;
        } else {
            $this->amountLowCloudSymbol = $decoder->getAmountLowCloudSymbol();
        }
    }

    /**
     * Sets the value of amount low cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setAmountLowCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->amountLowCloud = null;
        } else {
            $this->amountLowCloud = $decoder->getAmountLowCloud();
        }
    }

    /**
     * Sets the symbolic value of form low cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormLowCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formLowCloudSymbol = null;
        } else {
            $this->formLowCloudSymbol = $decoder->getFormLowCloudSymbol();
        }
    }

    /**
     * Sets the value of form low cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormLowCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formLowCloud = null;
        } else {
            $this->formLowCloud = $decoder->getFormLowCloud();
        }
    }

    /**
     * Sets the symbolic value of form medium cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormMediumCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formMediumCloudSymbol = null;
        } else {
            $this->formMediumCloudSymbol = $decoder->getFormMediumCloudSymbol();
        }
    }

    /**
     * Sets the value of form medium cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormMediumCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formMediumCloud = null;
        } else {
            $this->formMediumCloud = $decoder->getFormMediumCloud();
        }
    }

    /**
     * Sets the symbolic value of form high cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormHighCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formHighCloudSymbol = null;
        } else {
            $this->formHighCloudSymbol = $decoder->getFormHighCloudSymbol();
        }
    }

    /**
     * Sets the value of form high cloud
     * @param GroupDecoderInterface|null $decoder
     */
    public function setFormHighCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formHighCloud = null;
        } else {
            $this->formHighCloud = $decoder->getFormHighCloud();
        }
    }
}