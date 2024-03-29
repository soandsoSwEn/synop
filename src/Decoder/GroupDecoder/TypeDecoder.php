<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class TypeDecoder contains methods for defining the type of the weather report type
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class TypeDecoder implements GroupDecoderInterface
{
    /**
     * @var string Type of weather report raw data
     */
    private $rawTypeData;

    /**
     * @var string[] All valid values for defining the type of weather report
     */
    private $typeOfReport = ['AAXX', 'BBXX'];

    public function __construct(string $rawTypeData)
    {
        $this->rawTypeData = $rawTypeData;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate Object for weather data validation
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        return $validate->isValidGroup($this, $groupIndicator, [$this->getTypeValue()]);
    }

    /**
     * Return type of weather report
     *
     * @return string|null
     */
    public function getTypeValue(): ?string
    {
        foreach ($this->typeOfReport as $type) {
            if (strcasecmp($this->rawTypeData, $type) == 0) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Returns the result of checking if the weather summary is Synop
     *
     * @return bool|null
     */
    public function getIsSynopValue(): ?bool
    {
        $type = $this->getTypeValue();
        if (is_null($type)) {
            return null;
        }

        return strcasecmp($type, $this->typeOfReport[0]) == 0;
    }

    /**
     * Returns the result of checking if the weather summary is Ship
     *
     * @return bool|null
     */
    public function getIsShipValue(): ?bool
    {
        $type = $this->getTypeValue();
        if (is_null($type)) {
            return null;
        }

        return strcasecmp($type, $this->typeOfReport[1]) == 0;
    }

    /**
     * Returns indicator and description of synoptic code identifier - AAXX/BBXX
     *
     * @return string[] Indicator and description of synoptic code identifier
     */
    public function getTypeReportIndicator(): array
    {
        return ['AAXX/BBXX' => 'Synoptic Code Identifier'];
    }
}
