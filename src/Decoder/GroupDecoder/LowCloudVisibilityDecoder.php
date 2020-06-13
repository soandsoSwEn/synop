<?php

namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Exception;

/**
 * The LowCloudVisibilityDecoder class contains methods for decoding elements 
 * of a group of cloud height and horizontal visibility
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class LowCloudVisibilityDecoder implements GroupDecoderInterface
{
    private $raw_cloud_vis;
    
    /** @var type array Indices precipitation group inclusion 6RRRtr */
    private $i_r = [
        1 => 'Included in section 1',
        2 => 'Included in section 3',
        3 => 'Not included, as precipitation was not',
        4 => 'Not included, as precipitation was not measured'
    ];
    
    /** 
     * @var type array Values ​​of the type indicator of the station, as well
     *  as inclusion in the group report 7wwW1W2
     */
    private $i_x = [
        1 => ['Included', 'Staffed'],
        2 => ['Not included (no phenomena to be transmitted)', 'Staffed'],
        3 => ['Not included (no observations)', 'Staffed'],
        4 => ['Included', 'Automatic'],
        5 => ['Enabled (no events to be transmitted)', 'Automatic'],
        6 => ['Enabled (no observations)', 'Automatic']
    ];
    
    /**
     * @var type array Values ​​for the height of the base of the lowest clouds 
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

    public function __construct(string $raw_cloud_vis)
    {
        $this->raw_cloud_vis = $raw_cloud_vis;
    }

    public function getIr() : string
    {
        $ir = substr($this->raw_cloud_vis, 0, 1);
        if (array_key_exists($ir, $this->getIrData())) {
            return $this->getIrData()[$ir];
        }
    }

    public function getIx() : array
    {
        $ix = substr($this->raw_cloud_vis, 1, 1);
        if (array_key_exists($ix, $this->getIxData())) {
            return $this->getIxData()[$ix];
        }
    }

    public function getH()
    {
        $h = substr($this->raw_cloud_vis, 2, 1);
        if (array_key_exists($h, $this->getHData())) {
            return $this->getHData()[$h];
        }
    }

    public function getVV()
    {
        $vv = substr($this->raw_cloud_vis, 3, 2);
        return $this->getVisValue($vv);
    }

    /**
     * Returns the meteorological range of visibility
     * @param string $data
     * @return float|int|string
     * @throws Exception
     */
    private function getVisValue(string $data)
    {
        $data = intval($data);
        if($data == 0) {
            return '<0.1';
        } elseif ($data > 0 && $data < 50) {
            return $data/10;
        } elseif ($data == 50 ) {
            return 5;
        } elseif ($data >= 56 && $data <= 80) {
            return $data-50;
        } elseif ($data >= 81 && $data <= 88) {
            if($data == 81) {
                return 35;
            } else {
                return (($data-81)*5)+35;
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
            if($data == 95) {
                return 2;
            }
            if($data == 96) {
                return 4;
            }
            if($data == 97) {
                return 10;
            }
            if($data == 98) {
                return 20;
            }
        } elseif ($data == 99) {
            return '>50';
        } else {
            throw new Exception('Meteorological range of visibility error');
        }
    }

    private function getIrData() : array
    {
        return $this->i_r;
    }

    private function getIxData() : array
    {
        return $this->i_x;
    }

    /**
     * Returns the height value of the lower cloud cover
     * @return array|string[]
     */
    private function getHData() : array
    {
        return $this->h;
    }
}
