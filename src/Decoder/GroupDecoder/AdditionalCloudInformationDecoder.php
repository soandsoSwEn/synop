<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class AdditionalCloudInformationDecoder contains methods for decoding a group of Additional cloud
 * information transfer data
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AdditionalCloudInformationDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of Additional cloud information transfer group */
    protected const DIGIT = '8';

    /**
     * @var string Additional cloud information transfer data
     */
    private $rawAdditionCloudInformation;

    /**
     * @var string[] Map correspondences of symbolic and amount of individual cloud layer values
     */
    private $amountCloudMap = [
        '0' => 'Niel',
        '1' => '1 eight of sky covered, or less, but not zero',
        '2' => '2 eight of sky covered',
        '3' => '3 eight of sky covered',
        '4' => '4 eight of sky covered',
        '5' => '5 eight of sky covered',
        '6' => '6 eight of sky covered',
        '7' => '7 eight of sky covered, or more, but not completely covered',
        '8' => 'Sky completely covered',
        '9' => 'Sky obscured or cloud amount cannot be estimated',
    ];

    /**
     * @var string[] Map correspondences of symbolic and form of cloud values
     */
    private $formCloudMap = [
        '0' => 'Cirrus (Ci)',
        '1' => 'Cirrocumulus (Cc)',
        '2' => 'Cirrostratus (Cs)',
        '3' => 'Altocumulus (Ac)',
        '4' => 'Altostratus (As)',
        '5' => 'Nimbostratus (Ns)',
        '6' => 'Stratocumulus (Sc)',
        '7' => 'Status (St)',
        '8' => 'Cumulus (Cu)',
        '9' => 'Cumulonimbus (Cb)',
        '/' => 'Cloud not visible owing to darkness, fog, duststorm, sandstorm or other analogous phenomena',
    ];

    private $heightCloudMap = [
        '90' => '< 50',
        '91' => '50-100',
        '92' => '100-200',
        '93' => '200-300',
        '94' => '300-600',
        '95' => '600-1000',
        '96' => '1000-1500',
        '97' => '1500-2000',
        '98' => '2000-2500',
        '99' => '> 2500 or no clouds',
    ];

    public function __construct(string $rawAdditionCloudInformation)
    {
        $this->rawAdditionCloudInformation = $rawAdditionCloudInformation;
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
        $distinguishingDigit = substr($this->rawAdditionCloudInformation, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [
                $this->getCodeFigureIndicator(), $this->getCodeFigureAmount(), $this->getCodeFigureForm(),
                $this->getCodeFigureHeight()
                ]
            );
            return true;
        }

        return false;
    }

    /**
     * Returns code figure of amount of individual cloud layer
     *
     * @return string|null Code figure of amount of individual cloud layer
     * @throws Exception
     */
    public function getCodeAmountCloud(): ?string
    {
        $Ns = substr($this->rawAdditionCloudInformation, 1, 1);
        if (array_key_exists($Ns, $this->amountCloudMap)) {
            return $Ns;
        } elseif (strcasecmp($Ns, '/') == 0) {
            return null;
        } else {
            throw new Exception('Invalid data Code figure of amount of individual cloud layer');
        }
    }

    /**
     * Returns amount of individual cloud layer
     *
     * @return string|null Amount of individual cloud layer
     * @throws Exception
     */
    public function getAmountCloud(): ?string
    {
        $Ns = $this->getCodeAmountCloud();
        if (is_null($Ns)) {
            return null;
        }

        return $this->amountCloudMap[$Ns];
    }

    /**
     * Returns form of cloud
     *
     * @return string Form of cloud
     * @throws Exception
     */
    public function getFormCloud(): string
    {
        $C = substr($this->rawAdditionCloudInformation, 2, 1);
        if (array_key_exists($C, $this->formCloudMap)) {
            return $this->formCloudMap[$C];
        } else {
            throw new Exception('Invalid data Form of Cloud');
        }
    }

    /**
     * Returns code figure of height of base of cloud layer
     *
     * @return false|string|null Code figure of height of base of cloud layer
     */
    public function getCodeHeightCloud(): ?string
    {
        $hshs = substr($this->rawAdditionCloudInformation, 3, 2);
        if (strcasecmp($hshs, '//') == 0) {
            return null;
        }

        return $hshs;
    }

    /**
     * Returns height of base of cloud layer
     *
     * @return float[]|string[]|null Height of base of cloud layer data
     * @throws Exception
     */
    public function getHeightCloud(): ?array
    {
        $hshs = $this->getCodeHeightCloud();
        if (is_null($hshs)) {
            return null;
        }

        if (array_key_exists($hshs, $this->heightCloudMap)) {
            return ['Height' => $this->heightCloudMap[$hshs]];
        }

        $intValueOfHshs = intval($hshs);
        if ($intValueOfHshs >= 0 && $intValueOfHshs <= 50) {
            return ['Height' => $this->get0050Height($intValueOfHshs)];
        } elseif ($intValueOfHshs >= 56 && $intValueOfHshs <= 80) {
            return ['Height' => $this->get5080Height($intValueOfHshs)];
        } elseif ($intValueOfHshs >= 81 && $intValueOfHshs <= 89) {
            return ['Height' => $this->get8189Height($intValueOfHshs)];
        } else {
            throw new Exception('Invalid data Height of base of cloud layer');
        }
    }

    /**
     * Returns the value of the height of base of cloud for cases of heights between 0 and 1500 m
     *
     * @param int $intValueOfHshs Code figure of height of base of cloud layer
     * @return float|int|string
     */
    public function get0050Height(int $intValueOfHshs)
    {
        if ($intValueOfHshs == 0) {
            return '< 30';
        }

        return $intValueOfHshs * 30;
    }

    /**
     * Returns the value of the height of base of cloud for cases of heights between 1800 and 9000 m
     *
     * @param int $intValueOfHshs Code figure of height of base of cloud layer
     * @return float|int
     */
    public function get5080Height(int $intValueOfHshs)
    {
        return ($intValueOfHshs - 50) * 300;
    }

    /**
     * Returns the height of base of cloud for cases of altitude between 10500 and over 21000 m
     *
     * @param int $intValueOfHshs Code figure of height of base of cloud layer
     * @return float|int|string
     */
    public function get8189Height(int $intValueOfHshs)
    {
        if ($intValueOfHshs == 89) {
            return '> 21000';
        }

        return ($intValueOfHshs - 80) * 1500 + 9000;
    }

    /**
     * Returns indicator and description of additional cloud information transfer data - 8NsChshs
     *
     * @return string[] Indicator and description of additional cloud information
     */
    public function getIndicatorGroup(): array
    {
        return ['8' => 'Indicator'];
    }

    /**
     * Returns indicator and description of amount of individual cloud layer - 8NsChshs
     *
     * @return string[] Indicator and description amount of individual cloud layer
     */
    public function getAmountCloudLayerIndicator(): array
    {
        return ['Ns' => 'Amount of individual cloud layer'];
    }

    /**
     * Returns indicator and description of Form of cloud - 8NsChshs
     *
     * @return string[] Indicator and description form of cloud
     */
    public function getFormCloudIndicator(): array
    {
        return ['C' => 'Form of cloud'];
    }

    /**
     * Returns indicator and description of height of base cloud layer - 8NsChshs
     *
     * @return string[] Indicator and description of height of base cloud layer
     */
    public function getHeightCloudIndicator(): array
    {
        return ['hshs' => 'Height of base cloud layer'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getIndicatorGroup()),
            key($this->getAmountCloudLayerIndicator()),
            key($this->getFormCloudIndicator()),
            key($this->getHeightCloudIndicator()),
        ];
    }

    /**
     * Return code figure of indicator of Additional cloud group
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawAdditionCloudInformation, 0, 1);
    }

    /**
     * Return code figure of amount of individual cloud layer
     *
     * @return false|string
     */
    private function getCodeFigureAmount()
    {
        return substr($this->rawAdditionCloudInformation, 1, 1);
    }

    /**
     * Return code figure of form of cloud
     *
     * @return false|string
     */
    private function getCodeFigureForm()
    {
        return substr($this->rawAdditionCloudInformation, 2, 1);
    }

    /**
     * Return code figure height of base of cloud layer
     *
     * @return false|string
     */
    private function getCodeFigureHeight()
    {
        return substr($this->rawAdditionCloudInformation, 3, 2);
    }
}
