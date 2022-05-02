<?php


namespace Synop\Decoder\GroupDecoder;


use Exception;
use Synop\Fabrication\ValidateInterface;

/**
 * Class GroundWithSnowDecoder contains methods for decoding a group of state of the ground with snow
 * or measurable ice cover
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GroundWithSnowDecoder implements GroupDecoderInterface
{
    /** Distinctive digit of state of ground with snow or measurable ice cover group */
    const DIGIT = '4';

    /**
     * @var string State of ground with snow data
     */
    private $rawGroundWithSnow;

    /**
     * @var string[] Map correspondences of symbolic and state of ground with snow or measurable ice cover values
     */
    private $groundStateMap = [
        '0' => 'Ground predominantly covered by ice',
        '1' => 'Compact or wet snow (with or without ice) covering less than one-half of the ground',
        '2' => 'Compact or wet snow (with or without ice) covering at least one-half of the ground but ground not completely covered',
        '3' => 'Even layer of compact or wet snow covering ground completely',
        '4' => 'Uneven layer of compact or wet snow covering ground completely',
        '5' => 'Loose dry snow covering less than one-half of the ground',
        '6' => 'Loose dry snow covering at least one-half of the ground (but not completely)',
        '7' => 'Even layer of loose dry snow covering ground completely',
        '8' => 'Uneven layer of loose dry snow covering ground completely',
        '9' => 'Snow covering ground completely; deep drifts',
    ];

    /**
     * @var string[] Map correspondences of symbolic and depth of snow string values
     */
    private $depthSnowMap = [
        '997' => 'Less than 0.5 cm',
        '998' => 'Snow cover, not continuous',
        '999' => 'Measurement impossible or inaccurate',
    ];

    public function __construct(string $rawGroundWithSnow)
    {
        $this->rawGroundWithSnow = $rawGroundWithSnow;
    }

    /**
     * Returns the result of checking the validity of the group
     * @return bool
     */
    public function isGroup(ValidateInterface $validate) : bool
    {
        $distinguishingDigit = substr($this->rawGroundWithSnow, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(get_class($this), [
                $this->getCodeFigureIndicator(), $this->getCodeFigureStateGround(), $this->getCodeFigureDepthSnow()
            ]);
            return true;
        }

        return false;
    }

    /**
     * Return code figure of state ground
     * @return int Code figure of state of the ground with snow or measurable ice cover
     * @throws Exception
     */
    public function getCodeGroundState() : int
    {
        $E = substr($this->rawGroundWithSnow, 1, 1);
        if (array_key_exists($E, $this->groundStateMap)) {
            return intval($E);
        } else {
            throw new Exception('Invalid data Code figure of State ground with snow');
        }
    }

    /**
     * Return state of ground title
     * @return string State of ground with snow or measurable ice cover
     * @throws Exception
     */
    public function getGroundState() : string
    {
        $E = substr($this->rawGroundWithSnow, 1, 1);
        if (array_key_exists($E, $this->groundStateMap)) {
            return $this->groundStateMap[$E];
        } else {
            throw new Exception('Invalid data of State ground with snow');
        }
    }

    /**
     * Returns depth of snow data
     * @return array|string[] Depth of snow data value
     */
    public function getDepthSnow() : array
    {
        $sss = substr($this->rawGroundWithSnow, 2, 3);
        if (array_key_exists($sss, $this->depthSnowMap)) {
            return ['value' => $this->depthSnowMap[$sss]];
        }

        return ['value' => intval($sss)];
    }

    /**
     * Return code figure indicator of group of state ground with snow
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawGroundWithSnow, 0, 1);
    }

    /**
     * Return code figure of state of ground with snow
     * @return false|string
     */
    private function getCodeFigureStateGround()
    {
        return substr($this->rawGroundWithSnow, 1, 1);
    }

    /**
     * Return code figure of depth snow
     *
     * @return false|string
     */
    private function getCodeFigureDepthSnow()
    {
        return substr($this->rawGroundWithSnow, 2, 3);
    }
}