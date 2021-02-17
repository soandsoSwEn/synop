<?php


namespace Synop\Decoder\GroupDecoder;

use Exception;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;


class CloudPresentDecoder implements GroupDecoderInterface
{
    const DIGIT = '8';

    private $rawCloudPresent;

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

    private $formLowCloudMap = [
        '0' => 'No Stratocumulus, Stratus, Cumulus or Cumulonimbus',
        '1' => 'Cumulus with little vertical extend and seemingly flattened, or ragged Cumulus other than of bad weather or both',
        '2' => 'Cumulus moderate or string vertical extent, generally with protuberances in the form of domes or towers
                either accompanied or not by other Cumulus or by Stratocumulus, all having their bases at the same level',
        '3' => 'Cumulonimbus the summits of switch, at least partially, lack sharp outlines, but are neither clearly fibrous
                (cirriform) nor in the form of an anvil; Cumulus, Stratocumulus or Status may also be present',
        '4' => 'Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present',
        '5' => 'Stratocumulus not resulting from the spreading out of Cumulus',
        '6' => 'Stratus in a more or less continuous sheet or layer, or in ragged shreds, or both, but no Stratus fractus
                of bad weather',
        '7' => 'Stratus fractus of bad weather or Cumulus fractus of bad weather or both (pannus), usually below Altostratus
                or Nimbostratus',
        '8' => 'Cumulus and Stratocumulus other than that formed from the spreading out of Cumulus; the base of the Cumulus
                is at a different level from that of the Stratocumulus',
        '9' => 'Cumulonimbus, the upper part of which is clearly fibrous (cirriform), often in the form of an anvil ; either
                accompanied or not by Cumulonimbus without anvil or fibrous upper part, by Cumulus, Stratocumulus, Stratus or pannus',
        '/' => 'Stratocumulus, Stratus, Cumulus and Cumulonimbus invisible owing to darkness, fog, blowing dust or sand,
                or other similar phenomena',
    ];

    private $formMediumCloudMap = [
        '0' => 'No Altocumulus, Altocumulus or Nimbostartus',
        '1' => 'Altostratus, the greater part of which is semi-transparent; trough this part the sun or moon may be weakly
                visible as trough ground glass',
        '2' => 'Altostratus, the greater part of which is sufficiently dense to hide the sun or moon, or Nimbostratus',
        '3' => 'Altocumulus, the greater part of which is semi-transparent; the various elements of the cloud change only slowly
                and are all at a single level',
        '4' => 'Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent;
                the clouds occur at one or more levels and the elements are continually changing in appearance',
        '5' => 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque),
                progressively invading the sky; these Altocumulus clouds generally thicken as a whole',
        '6' => 'Altocumulus resulting from the spreading out of Cumulus (or Cumulonimbus)',
        '7' => 'Altocumulus in two or more layers, usually opaque in places, and not progressively invading the sky; or opaque
                layer of Altocumulus not progressivelly invading the sky; or Altocumulus together with Altostratus or Nimbostratus',
        '8' => 'Altostrarus with sprouting in the form of small or battlements, or Altocumulus having the appearance of cumulonimbus tufts',
        '9' => 'Altocumulus of a chaotic sky, generally at several levels',
        '/' => 'Altocumulus, Altostarus and Nimbostratus invisible owing to darkness, fog, blowing dust or sand, or other
                similar phenomena, or more often because of the presence of a continuous layer of lower clouds',
    ];

    private $formHighCloudMap = [
        '0' => 'No Cirrus, Cirrocumulus or Cirrostartus',
        '1' => 'Cirrus in the form of filaments, strands or hooks, not progressively invading the sky',
        '2' => 'Dense Cirrus in patches or entangled sheaves, which usually do not increase and sometimes seem to be the remains
                of the upper part of a Cumulonimbus; or Cirrus with sproutings in the form of small turrets or battlements,
                or Cirrus having the appearance of cumuliform tufts',
        '3' => 'Dense Cirrus, often in the form of an anvil, being the remains of the upper parts of Cumulonimbus',
        '4' => 'Cirrus in the forms of hooks or of filaments, or both, progressively invading the sky; they generally
                become denser as a whole',
        '5' => 'Cirrus (often in band converging toward one point or two opposite points of the horizon) and Cirrostratus,
                or Cirrostrarus alone; in either case, they are progressively invading the sky, and generally growing denser
                as a whole, but the continuous veil does not reach 45 degrees above the horizon',
        '6' => 'Cirrus (often in band converging toward one point or two opposite points of the horizon) and Cirrostratus,
                or Cirrostrarus alone; in either case, they are progressively invading the sky, and generally growing denser
                as a whole; the continuous veil extends more than 45 degrees above the horizon, without the sky being totally covered',
        '7' => 'Veil of Cirrostratus covering the celestial dome',
        '8' => 'Cirrostratus not progressively invading the sky and not completely covering the celestial dome',
        '9' => 'Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant',
        '/' => 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar
                phenomena or more often because of the presence of a continuous layer of lower clouds',
    ];

    public function __construct(string $rawCloudPresent)
    {
        $this->rawCloudPresent = $rawCloudPresent;
    }

    public function isGroup() : bool
    {
        $distinguishingDigit = substr($this->rawCloudPresent, 0, 1);

        return strcasecmp($distinguishingDigit, self::DIGIT) == 0 ? true : false;
    }

    public function getAmountLowCloudSymbol() : string
    {
        return substr($this->rawCloudPresent, 1, 1);
    }

    public function getAmountLowCloud() : string
    {
        $Nh = $this->getAmountLowCloudSymbol();
        if (array_key_exists($Nh, $this->amountCloudMap)) {
            return $this->amountCloudMap[$Nh];
        } else {
            throw new Exception('Invalid data of Amount of low cloud');
        }
    }

    public function getFormLowCloudSymbol() : string
    {
        return substr($this->rawCloudPresent, 2, 1);
    }

    public function getFormLowCloud() : string
    {
        $Cl = $this->getFormLowCloudSymbol();
        if (array_key_exists($Cl, $this->formLowCloudMap)) {
            return $this->formLowCloudMap[$Cl];
        } else {
            throw new Exception('Invalid data of Form of low cloud');
        }
    }

    public function getFormMediumCloudSymbol() : string
    {
        return substr($this->rawCloudPresent, 3, 1);
    }

    public function getFormMediumCloud() : string
    {
        $Cm = $this->getFormMediumCloudSymbol();
        if (array_key_exists($Cm, $this->formMediumCloudMap)) {
            return $this->formMediumCloudMap[$Cm];
        } else {
            throw new Exception('Invalid data of Form of medium cloud');
        }
    }

    public function getFormHighCloudSymbol() : string
    {
        return substr($this->rawCloudPresent, 4, 1);
    }

    public function getFormHighCloud() : string
    {
        $Ch = $this->getFormHighCloudSymbol();
        if (array_key_exists($Ch, $this->formHighCloudMap)) {
            return $this->formHighCloudMap[$Ch];
        } else {
            throw new Exception('Invalid data of Form of hight cloud');
        }
    }
}