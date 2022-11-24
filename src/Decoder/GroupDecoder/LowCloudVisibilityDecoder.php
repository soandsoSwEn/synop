<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * The LowCloudVisibilityDecoder class contains methods for decoding elements
 * of a group of cloud height and horizontal visibility
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class LowCloudVisibilityDecoder implements GroupDecoderInterface
{
    /**
     * @var string Cloud height and horizontal visibility source code
     */
    private $rawCloudVis;

    /** @var array Indices precipitation group inclusion 6RRRtr */
    private $i_r = [
        1 => 'Included in section 1',
        2 => 'Included in section 3',
        3 => 'Omitted (precipitation amount = 0)',
        4 => 'Omitted (precipitation not amount available)'
    ];

    /**
     * @var array Values of the type indicator of the station, as well
     *  as inclusion in the group report 7wwW1W2
     */
    private $i_x = [
        1 => ['Included', 'Manned'],
        2 => ['Omitted (no significant phenomenon to report)', 'Manned'],
        3 => ['Omitted (not observed, data not available)', 'Manned'],
        4 => ['Included', 'Automatic'],
        5 => ['Omitted (no significant phenomenon to report)', 'Automatic'],
        6 => ['Omitted (not observed, data not available)', 'Automatic']
    ];

    /**
     * @var array Values for the height of the base of the lowest clouds
     * above the surface of the earth (sea)
     */
    private $h = [
        0 => '<50',
        1 => '500-100',
        2 => '100-200',
        3 => '200-300',
        4 => '300-600',
        5 => '600-1000',
        6 => '1000-1500',
        7 => '1500-2000',
        8 => '2000-2500',
        9 => 'no clouds below 2500'
    ];

    public function __construct(string $rawCloudVis)
    {
        $this->rawCloudVis = $rawCloudVis;
    }

    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        return $validate->isValidGroup(
            $this,
            $groupIndicator,
            [
                $this->getCodeFigureIr(),
                $this->getCodeFigureIx(),
                $this->getCodeFigureH(),
                $this->getCodeFigureVV()
            ]
        );
    }

    public function getIr(): ?string
    {
        $ir = substr($this->rawCloudVis, 0, 1);
        if (array_key_exists($ir, $this->getIrData())) {
            return $this->getIrData()[$ir];
        }

        return null;
    }

    public function getIx(): ?array
    {
        $ix = substr($this->rawCloudVis, 1, 1);
        if (array_key_exists($ix, $this->getIxData())) {
            return $this->getIxData()[$ix];
        }

        return null;
    }

    public function getH()
    {
        $h = substr($this->rawCloudVis, 2, 1);
        if (array_key_exists($h, $this->getHData())) {
            return $this->getHData()[$h];
        }

        return null;
    }

    public function getVV()
    {
        $vv = substr($this->rawCloudVis, 3, 2);
        return $this->getVisValue($vv);
    }

    /**
     * Returns the meteorological range of visibility
     *
     * @param string $data
     * @return float|int|string
     * @throws Exception
     */
    private function getVisValue(string $data)
    {
        $data = intval($data);
        if ($data == 0) {
            return '<0.1';
        } elseif ($data > 0 && $data < 50) {
            return $data / 10;
        } elseif ($data == 50) {
            return 5;
        } elseif ($data >= 56 && $data <= 80) {
            return $data - 50;
        } elseif ($data >= 81 && $data <= 88) {
            if ($data == 81) {
                return 35;
            } else {
                return (($data - 81) * 5) + 35;
            }
        } elseif ($data == 89) {
            return '>70';
        } elseif ($data >= 90 && $data <= 98) {
            if ($data == 90) {
                return '<0.05';
            }
            if ($data == 91) {
                return 0.05;
            }
            if ($data == 92) {
                return 0.2;
            }
            if ($data == 93) {
                return 0.5;
            }
            if ($data == 94) {
                return 1;
            }
            if ($data == 95) {
                return 2;
            }
            if ($data == 96) {
                return 4;
            }
            if ($data == 97) {
                return 10;
            }
            if ($data == 98) {
                return 20;
            }
        } elseif ($data == 99) {
            return '>50';
        } else {
            throw new Exception('Meteorological range of visibility error');
        }
    }

    private function getIrData(): array
    {
        return $this->i_r;
    }

    private function getIxData(): array
    {
        return $this->i_x;
    }

    /**
     * Returns the height value of the lower cloud cover
     *
     * @return array|string[]
     */
    private function getHData(): array
    {
        return $this->h;
    }

    /**
     * Returns indicator and description of inclusion omission of precipitation data - irixhVV
     *
     * @return string[] Indicator and description of inclusion omission of precipitation data
     */
    public function getGetPrecipitationDataIndicator(): array
    {
        return ['ir' => 'Inclusion omission of precipitation data'];
    }

    /**
     * Returns indicator and description of inclusion omission of weather group - irixhVV
     *
     * @return string[] Indicator and description of inclusion omission of weather group
     */
    public function getGetWeatherGroupIndicator(): array
    {
        return ['ix' => 'Inclusion omission of weather group'];
    }

    /**
     * Returns indicator and description of Height of base of lowest cloud - irixhVV
     *
     * @return string[] Indicator and description of height of base of lowest cloud
     */
    public function getGetHeightCloudIndicator(): array
    {
        return ['h' => 'Height of base of lowest cloud'];
    }

    /**
     * Returns indicator and description of horizontal visibility - irixhVV
     *
     * @return string[] Indicator and description of horizontal visibility
     */
    public function getGetVisibilityIndicator(): array
    {
        return ['VV' => 'Horizontal visibility'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getGetPrecipitationDataIndicator()),
            key($this->getGetWeatherGroupIndicator()),
            key($this->getGetHeightCloudIndicator()),
            key($this->getGetVisibilityIndicator()),
        ];
    }

    /**
     * Return code figure of precipitation group inclusion 6RRRtr
     *
     * @return false|string
     */
    private function getCodeFigureIr(): string
    {
        return substr($this->rawCloudVis, 0, 1);
    }

    /**
     * Return code figure type indicator of the station, as well as inclusion in the group report 7wwW1W2
     *
     * @return false|string
     */
    private function getCodeFigureIx(): string
    {
        return substr($this->rawCloudVis, 1, 1);
    }

    /**
     * Return code figure of height of the base of the lowest clouds above the surface of the earth (sea)
     *
     * @return string
     */
    private function getCodeFigureH(): string
    {
        return substr($this->rawCloudVis, 2, 1);
    }

    /**
     * Return code figure of horizontal visibility
     *
     * @return false|string
     */
    private function getCodeFigureVV()
    {
        return substr($this->rawCloudVis, 3, 2);
    }
}
