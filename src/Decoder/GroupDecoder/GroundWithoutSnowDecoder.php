<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class GroundWithoutSnowDecoder contains methods for decoding a group of state of the ground without snow
 * or measurable ice cover
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GroundWithoutSnowDecoder implements GroupDecoderInterface
{
    /** Distinctive digit of state of ground without snow or measurable ice cover group */
    protected const DIGIT = '3';

    /**
     * @var string State of ground without snow data
     */
    private $rawGroundWithoutSnow;

    /**
     * @var string[] Map correspondences of symbolic and state of ground without snow or measurable ice cover values
     */
    private $groundStateMap = [
        '0' => 'Surface of ground dry (without cracks and no appreciable amount of dust of loose sand)',
        '1' => 'Surface of ground moist',
        '2' => 'Surface of ground wet (standing water in small or large pools on surface)',
        '3' => 'Flooded',
        '4' => 'Surface of ground frozen',
        '5' => 'Glaze on ground',
        '6' => 'Loose dry dust or sand not covering ground completely',
        '7' => 'Thin cover of loose dry dust or sand covering ground completely',
        '8' => 'Moderate or thick cover of loose dry dust or sand covering ground completely',
        '9' => 'Extremely dry with cracks',
    ];

    public function __construct(string $rawGroundWithoutSnow)
    {
        $this->rawGroundWithoutSnow = $rawGroundWithoutSnow;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        $distinguishingDigit = substr($this->rawGroundWithoutSnow, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [
                $this->getCodeFigureIndicator(),
                $this->getCodeFigureStateGround(),
                $this->getCodeFigureSignTemperature(),
                $this->getCodeFigureMinTemperature()
                ]
            );

            return true;
        }

        return false;
    }

    /**
     * Return code figure of state ground
     *
     * @return int Code figure of state of the ground without snow or measurable ice cover
     * @throws Exception
     */
    public function getCodeGroundState(): int
    {
        $E = substr($this->rawGroundWithoutSnow, 1, 1);
        if (array_key_exists($E, $this->groundStateMap)) {
            return intval($E);
        } else {
            throw new Exception('Invalid data Code figure of State ground without snow');
        }
    }

    /**
     * Return state of ground title
     *
     * @return string State of ground without snow or measurable ice cover
     * @throws Exception
     */
    public function getGroundState(): string
    {
        $E = substr($this->rawGroundWithoutSnow, 1, 1);
        if (array_key_exists($E, $this->groundStateMap)) {
            return $this->groundStateMap[$E];
        } else {
            throw new Exception('Invalid data of State ground without snow');
        }
    }

    //TODO string or int return sign analyse
    /**
     * Returns the sign of grass minimum temperature
     *
     * @return false|string Sign of grass minimum temperature
     */
    public function getGroundSignTemperature()
    {
        return substr($this->rawGroundWithoutSnow, 2, 1);
    }

    /**
     * Returns grass minimum temperature (rounded to nearest whole degree)
     *
     * @return int Grass minimum temperature
     */
    public function getGroundMinTemperature(): int
    {
        $minGrassTemperature = substr($this->rawGroundWithoutSnow, 3, 2);

        return intval($minGrassTemperature);
    }

    /**
     * Returns indicator and description of state and temperature of the ground without snow 333 3ESnTgTg
     *
     * @return string[] Indicator and description of state temperature of the ground without snow
     */
    public function getGetIndicatorGroup(): array
    {
        return ['3' => 'Indicator'];
    }

    /**
     * Returns indicator and description of state of the ground without snow 333 3ESnTgTg
     *
     * @return string[] Indicator and description of state of ground without snow
     */
    public function getStateGroundIndicator(): array
    {
        return ['E' => 'State of ground without snow or measurable ice cover'];
    }

    /**
     * Returns indicator and description of sign of temperature 333 3ESnTgTg
     *
     * @return string[] Indicator and description of sign of temperature
     */
    public function getSignTemperatureIndicator(): array
    {
        return ['Sn' => 'Sign of temperature'];
    }

    /**
     * Returns indicator and description of grass minimum temperature 333 3ESnTgTg
     *
     * @return string[] Indicator and description of minimum temperature
     */
    public function getMinimumTemperature(): array
    {
        return ['TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getGetIndicatorGroup()),
            key($this->getStateGroundIndicator()),
            key($this->getSignTemperatureIndicator()),
            key($this->getMinimumTemperature()),
        ];
    }

    /**
     * Return code figure of indicator of ground without snow group
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawGroundWithoutSnow, 0, 1);
    }

    /**
     * Return code figure of state of ground
     *
     * @return false|string
     */
    private function getCodeFigureStateGround()
    {
        return substr($this->rawGroundWithoutSnow, 1, 1);
    }

    /**
     * Return code figure of sign temperature
     *
     * @return false|string
     */
    private function getCodeFigureSignTemperature()
    {
        return substr($this->rawGroundWithoutSnow, 2, 1);
    }

    /**
     * Return code figure minimum temperature
     *
     * @return false|string
     */
    private function getCodeFigureMinTemperature()
    {
        return substr($this->rawGroundWithoutSnow, 3, 2);
    }
}
