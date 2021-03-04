<?php


namespace Synop\Decoder\GroupDecoder;


use Exception;

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
    const DIGIT = '3';

    /**
     * @var string State of ground without snow data
     */
    private $rawGroundWithoutSnow;

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
     * @return bool
     */
    public function isGroup() : bool
    {
        $distinguishingDigit = substr($this->rawGroundWithoutSnow, 0, 1);

        return strcasecmp($distinguishingDigit, self::DIGIT) == 0;
    }

    /**
     * Return code figure of state ground
     * @return int Code figure of state of the ground without snow or measurable ice cover
     * @throws Exception
     */
    public function getCodeGroundState() : int
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
     * @return string State of ground without snow or measurable ice cover
     * @throws Exception
     */
    public function getGroundState() : string
    {
        $E = substr($this->rawGroundWithoutSnow, 1, 1);
        if (array_key_exists($E, $this->groundStateMap)) {
            return $this->groundStateMap[$E];
        } else {
            throw new Exception('Invalid data of State ground without snow');
        }
    }

    /**
     * Returns the sign of grass minimum temperature
     * @return false|string Sign of grass minimum temperature
     */
    public function getGroundSignTemperature()
    {
        return substr($this->rawGroundWithoutSnow, 2, 1);
    }

    /**
     * Returns grass minimum temperature (rounded to nearest whole degree)
     * @return int Grass minimum temperature
     */
    public function getGroundMinTemperature() : int
    {
        $minGrassTemperature = substr($this->rawGroundWithoutSnow, 3, 2);

        return intval($minGrassTemperature);
    }
}