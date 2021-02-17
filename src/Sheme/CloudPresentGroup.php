<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Sheme\GroupInterface;


class CloudPresentGroup implements GroupInterface
{
    private $rawCloudPresent;

    private $decoder;

    private $amountLowCloudSymbol;

    private $amountLowCloud;

    private $formLowCloudSymbol;

    private $formLowCloud;

    private $formMediumCloudSymbol;

    private $formMediumCloud;

    private $formHighCloudSymbol;

    private $formHighCloud;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

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

    public function isCloudPresentGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    public function setAmountLowCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->amountLowCloudSymbol = null;
        } else {
            $this->amountLowCloudSymbol = $decoder->getAmountLowCloudSymbol();
        }
    }

    public function setAmountLowCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->amountLowCloud = null;
        } else {
            $this->amountLowCloud = $decoder->getAmountLowCloud();
        }
    }

    public function setFormLowCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formLowCloudSymbol = null;
        } else {
            $this->formLowCloudSymbol = $decoder->getFormLowCloudSymbol();
        }
    }

    public function setFormLowCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formLowCloud = null;
        } else {
            $this->formLowCloud = $decoder->getFormLowCloud();
        }
    }

    public function setFormMediumCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formMediumCloudSymbol = null;
        } else {
            $this->formMediumCloudSymbol = $decoder->getFormMediumCloudSymbol();
        }
    }

    public function setFormMediumCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formMediumCloud = null;
        } else {
            $this->formMediumCloud = $decoder->getFormMediumCloud();
        }
    }

    public function setFormHighCloudSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formHighCloudSymbol = null;
        } else {
            $this->formHighCloudSymbol = $decoder->getFormHighCloudSymbol();
        }
    }

    public function setFormHighCloud(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->formHighCloud = null;
        } else {
            $this->formHighCloud = $decoder->getFormHighCloud();
        }
    }
}