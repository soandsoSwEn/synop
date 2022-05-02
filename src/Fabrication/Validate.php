<?php

namespace Synop\Fabrication;


use Exception;
use Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Synop\Decoder\GroupDecoder\DateDecoder;
use Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;
use Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Synop\Decoder\GroupDecoder\IndexDecoder;
use Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Synop\Decoder\GroupDecoder\TypeDecoder;
use Synop\Sheme\MaxAirTemperatureGroup;
use Synop\Sheme\MinAirTemperatureGroup;
use Synop\Sheme\RegionalExchangeAmountRainfallGroup;

/**
 * Description of Validate
 *
 * @author Vyacheslav
 */
class Validate extends ValidateBase implements ValidateInterface
{
    /**
     * @var string Weather report source code
     */
    private $report;

    /**
     * @var array Errors in the meteorological weather report
     */
    private $errors = [];

    private $groups = [
        TypeDecoder::class => ['check' => 'typeValid'],
        DateDecoder::class => ['check' => 'dateValid'],
        IndexDecoder::class => ['check' => 'indexValid'],
        LowCloudVisibilityDecoder::class => ['check' => 'lowCloudVisibilityValid'],
        CloudWindDecoder::class => ['check' => 'cloudWindGroupValid'],
        AirTemperatureDecoder::class => ['check' => 'airTemperatureGroupValid'],
        DewPointTemperatureDecoder::class => ['check' => 'dewPointTemperatureGroupValid'],
        StLPressureDecoder::class => ['check' => 'stLPressureGroupValid'],
        MslPressureDecoder::class => ['check' => 'mslPressureGroupValid'],
        BaricTendencyDecoder::class => ['check' => 'baricTendencyGroupValid'],
        AmountRainfallDecoder::class => ['check' => 'amountRainfallGroupValid'],
        PresentWeatherDecoder::class => ['check' => 'presentWeatherGroupValid'],
        CloudPresentDecoder::class => ['check' => 'cloudPresentGroupValid'],
        MaxAirTemperatureGroup::class => '',
        MinAirTemperatureGroup::class => '',
        GroundWithoutSnowDecoder::class => ['check' => 'groundWithoutSnowGroupValid'],
        GroundWithSnowDecoder::class => ['check' => 'groundWithSnowGroupValid'],
        SunshineRadiationDataDecoder::class => ['check' => 'sunshineRadiationDataGroupValid'],
        RegionalExchangeAmountRainfallGroup::class => '',
        AdditionalCloudInformationDecoder::class => ['check' => 'additionalCloudInformationGroupValid'],
    ];


    public function __construct(string $report)
    {
        $this->report = $this->preparation($report);
    }

    /**
     * Displays weather report errors with groups
     *
     * @return array Weather report errors with groups
     */
    public function getErrorsWithGrous(): array
    {
        return $this->errors;
    }

    /**
     * Returns all meteorological weather report errors
     *
     * @return array Weather report errors with groups
     */
    public function getErrors(): array
    {
        return array_values($this->errors);
    }

    //TODO Analyse
    public function getErrorByGroup(string $group)
    {
        if (!array_key_exists($group, $this->errors)) {
            return false;
        }

        return $this->errors[$group];
    }

    /**
     * Checks if the given weather group has errors
     *
     * @param array $group Weather group
     * @return bool
     */
    public function notExistsError(array $group): bool
    {
        foreach ($group as $chunkGroup) {
            if (array_key_exists($chunkGroup, $this->errors) && !empty($this->errors[$chunkGroup])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sets error with her group
     *
     * @param string $group
     * @param string $error
     * @return void
     */
    protected function setError(string $group, string $error)
    {
        $this->errors[$group] = $error;
    }

    public function preparation(string $report): string
    {
        return $this->clearDoubleSpacing($report);
    }
    
    public function isValid(): bool
    {
        if(!$this->report) {
            throw new Exception('Meteorological weather report not defined!');
        }
        return $this->isEndEqualSign($this->report) && $this->isCountSymbol($this->report);
    }

    public function isValidGroup(string $groupItem, array $groupData)
    {
        if (!array_key_exists($groupItem, $this->groups)) {
            throw new Exception("The class object {$groupItem} specified for validation was not found");
        }

        $method = $this->groups[$groupItem]['check'];

        return $this->$method($groupData);
    }

    //Valid methods
    public function typeValid(array $groupData): bool
    {
        $patterns = ['/^AAXX$/', '/^BBXX$/'];
        foreach ($patterns as $pattern) {
            $isCheck = preg_match($pattern, $groupData[0]);
            if ($isCheck) {
                return true;
            }
        }

        $this->setError($groupData[0], "The summary type group data does not match the specified format; Code group - {$groupData[0]}");
        return false;
    }

    /**
     * Returns the result of checking the validity of group YYGGiw
     *
     * @param array $groupData
     * @return bool
     */
    public function dateValid(array $groupData): bool
    {
        if (intval($groupData[0]) <= 0 || intval($groupData[0]) > 31) {
            $this->setError($groupData[0], "Invalid day of the month - {$groupData[0]}");
        }

        $patterns = '/^\d{2}$/';
        if (!preg_match($patterns, $groupData[1])) {
            $this->setError($groupData[1], "Wrong period of observation - {$groupData[1]}");
        }

        if (is_null($groupData[2]) || !is_array($groupData[2])) {
            $this->setError(json_encode($groupData[2]), "Incorrect index of wind speed units and how to determine it - {$groupData[2]}");
        }

        $groupData[2] = json_encode($groupData[2]);

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group IIiii
     *
     * @param array $groupData
     * @return bool
     */
    public function indexValid(array $groupData): bool
    {
        $patternArea = '/^\d{2}$/';
        if (!preg_match($patternArea, $groupData[0])) {
            $this->setError($groupData[0], "Wrong Meteorological Station Area Number - {$groupData[0]}");
        }

        $patternNumber = '/^\d{3}$/';
        if (!preg_match($patternNumber, $groupData[1])) {
            $this->setError($groupData[1], "Wrong Meteorological station number within of area - {$groupData[1]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group irixhVV
     *
     * @param array $groupData
     * @return bool
     */
    public function lowCloudVisibilityValid(array $groupData): bool
    {
        $patternIr = '/^[1-4]$/';
        if (!preg_match($patternIr, $groupData[0])) {
            $this->setError($groupData[0], "Wrong Index precipitation group inclusion 6RRRtr - {$groupData[0]}");
        }

        $patternIx = '/^[1-6]$/';
        if (!preg_match($patternIx, $groupData[1])) {
            $this->setError($groupData[1], "Wrong Values of the type indicator of the station, as well as inclusion in the group report 7wwW1W2 - {$groupData[1]}");
        }

        $patternH = '/^\d$/';
        if (!preg_match($patternH, $groupData[2])) {
            $this->setError($groupData[2], "Wrong Value for the height of the base of the lowest clouds above the surface of the earth (sea) - {$groupData[2]}");
        }

        $patternVV = '/^\d{2}$/';
        if (!preg_match($patternVV, $groupData[3])) {
            $this->setError($groupData[3], "Wrong meteorological range of visibility - {$groupData[3]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group Nddff
     *
     * @param array $groupData
     * @return bool
     */
    public function cloudWindGroupValid(array $groupData): bool
    {
        $patternN = '/^\d|\/$/';
        if (!preg_match($patternN, $groupData[0])) {
            $this->setError($groupData[0], "Wrong number of clouds of group Nddff - {$groupData[0]}");
        }

        $patternDd = '/^\d{2}|\/{2}$/';
        if (!preg_match($patternDd, $groupData[1])) {
            $this->setError($groupData[1], "Wrong wind direction of group Nddff - {$groupData[1]}");
        }

        if (intval($groupData[1]) < 0 || intval($groupData[1]) > 36) {
            $this->setError($groupData[1], "Wrong wind direction of group Nddff - {$groupData[1]}");
        }

        $patternVv = '/^\d{2}|\/{2}$/';
        if (!preg_match($patternVv, $groupData[2])) {
            $this->setError($groupData[2], "Wrong value of wind speed of group Nddff - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 1SnTTT
     *
     * @param array $groupData
     * @return bool
     */
    public function airTemperatureGroupValid(array $groupData): bool
    {
        $patternDn = '/^1|\/$/';
        if (!preg_match($patternDn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong distinctive number of air temperature group 1SnTTT - {$groupData[0]}");
        }

        $patternSt = '/^[0-1]|\/$/';
        if (!preg_match($patternSt, $groupData[1])) {
            $this->setError($groupData[1], "Wrong sign of air temperature group 1SnTTT - {$groupData[1]}");
        }

        $patternTv = '/^\d{3}|\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError($groupData[2], "Wrong air temperature group 1SnTTT - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 2SnTdTdTd
     *
     * @param array $groupData
     * @return bool
     */
    public function dewPointTemperatureGroupValid(array $groupData): bool
    {
        $patternDdw = '/^2|\/$/';
        if (!preg_match($patternDdw, $groupData[0])) {
            $this->setError($groupData[0], "Wrong distinctive number of dew point temperature group 2SnTdTdTd - {$groupData[0]}");
        }

        $patternSdw = '/^[0-1]|\/$/';
        if (!preg_match($patternSdw, $groupData[1])) {
            $this->setError($groupData[1], "Wrong sign of dew point temperature group 2SnTdTdTd - {$groupData[1]}");
        }

        $patternTv = '/^\d{3}|\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError($groupData[2], "Wrong dew point temperature group 2SnTdTdTd - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 3P0P0P0P0
     *
     * @param array $groupData
     * @return bool
     */
    public function stLPressureGroupValid(array $groupData): bool
    {
        $patternSp = '/^3|\/$/';
        if (!preg_match($patternSp, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of atmospheric pressure at the station level group 3P0P0P0P0 - {$groupData[0]}");
        }

        $patternSp = '/^\d{4}|\/\/\/\/$/';
        if (!preg_match($patternSp, $groupData[1])) {
            $this->setError($groupData[1], "Wrong atmospheric pressure at the station level group 3P0P0P0P0 - {$groupData[1]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 4PPPP
     *
     * @param array $groupData
     * @return bool
     */
    public function mslPressureGroupValid(array $groupData): bool
    {
        $patternSp = '/^4|\/$/';
        if (!preg_match($patternSp, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of air Pressure reduced to mean sea level group 4PPPP - {$groupData[0]}");
        }

        $patternSp = '/^\d{4}|\/\/\/\/$/';
        if (!preg_match($patternSp, $groupData[1])) {
            $this->setError($groupData[1], "Wrong air Pressure reduced to mean sea level group 4PPPP - {$groupData[1]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 5appp
     *
     * @param array $groupData
     * @return bool
     */
    public function baricTendencyGroupValid(array $groupData): bool
    {
        $patternIn = '/^5|\/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of Pressure change over last three hours group 5appp - {$groupData[0]}");
        }

        $patternCh = '/^[0-8]|\/$/';
        if (!preg_match($patternCh, $groupData[1])) {
            $this->setError($groupData[1], "Wrong Characteristic of Pressure change group 5appp - {$groupData[1]}");
        }

        $patternChan = '/^\d{3}|\/\/\/$/';
        if (!preg_match($patternChan, $groupData[2])) {
            $this->setError($groupData[2], "Wrong Pressure change over last three hours group 5appp - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 6RRRtr
     *
     * @param array $groupData
     * @return bool
     */
    public function amountRainfallGroupValid(array $groupData): bool
    {
        $patternIn = '/^6|\/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of amount rainfall group 6RRRtr - {$groupData[0]}");
        }

        $patternRa = '/^\d{3}|\/\/\/$/';
        if (!preg_match($patternRa, $groupData[1])) {
            $this->setError($groupData[1], "Wrong value of amount rainfall group 6RRRtr - {$groupData[1]}");
        }

        $patternDp = '/^[1-2]|\/\/$/';
        if (!preg_match($patternDp, $groupData[2])) {
            $this->setError($groupData[2], "Wrong duration period of amount rainfall group 6RRRtr - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 7wwW1W2
     *
     * @param array $groupData
     * @return bool
     */
    public function presentWeatherGroupValid(array $groupData): bool
    {
        $patternIn = '/^7|\/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of Present weather group 7wwW1W2 - {$groupData[0]}");
        }

        $patternPrw = '/^\d{2}|\/\/$/';
        if (!preg_match($patternPrw, $groupData[1])) {
            $this->setError($groupData[1], "Wrong Present weather of group 7wwW1W2 - {$groupData[1]}");
        }

        $patternPsw = '/^\d{2}|\/\/$/';
        if (!preg_match($patternPsw, $groupData[2])) {
            $this->setError($groupData[2], "Wrong Past weather of group 7wwW1W2 - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 8NhClCmCh
     *
     * @param array $groupData
     * @return bool
     */
    public function cloudPresentGroupValid(array $groupData): bool
    {
        $patternIn = '/^8|\/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong of indicator of Present clouds group 8NhClCmCh - {$groupData[0]}");
        }

        $patternA = '/^\d$/';
        if (!preg_match($patternA, $groupData[1])) {
            $this->setError($groupData[1], "Wrong of amount of low cloud or medium cloud 8NhClCmCh - {$groupData[1]}");
        }

        $patternFlc = '/^\d|\/$/';
        if (!preg_match($patternFlc, $groupData[2])) {
            $this->setError($groupData[2], "Wrong form of low cloud 8NhClCmCh - {$groupData[2]}");
        }

        $patternFmc = '/^\d|\/$/';
        if (!preg_match($patternFmc, $groupData[3])) {
            $this->setError($groupData[3], "Wrong form of medium cloud 8NhClCmCh - {$groupData[3]}");
        }

        $patternFhc = '/^\d|\/$/';
        if (!preg_match($patternFhc, $groupData[4])) {
            $this->setError($groupData[4], "Wrong form of high cloud 8NhClCmCh - {$groupData[4]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 3ESnTgTg
     *
     * @param array $groupData
     * @return bool
     */
    public function groundWithoutSnowGroupValid(array $groupData): bool
    {
        $patternIn = '/^3|/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong indicator of ground without snow group 3ESnTgTg - {$groupData[0]}");
        }

        $patternSg = '/^\d|/$/';
        if (!preg_match($patternSg, $groupData[1])) {
            $this->setError($groupData[1], "Wrong state of ground group 3ESnTgTg - {$groupData[1]}");
        }

        $patternSn = '/^[0-1]|/$/';
        if (!preg_match($patternSn, $groupData[2])) {
            $this->setError($groupData[2], "Wrong sign temperature group 3ESnTgTg - {$groupData[2]}");
        }

        $patternMt = '/^\d{2}|//$/';
        if (!preg_match($patternMt, $groupData[3])) {
            $this->setError($groupData[3], "Wrong minimum temperature 3ESnTgTg - {$groupData[3]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 4Esss
     *
     * @param array $groupData
     * @return bool
     */
    public function groundWithSnowGroupValid(array $groupData): bool
    {
        $patternIn = '/^4|/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong indicator of state ground with snow group 4Esss - {$groupData[0]}");
        }

        $patternSg = '/^\d|/$/';
        if (!preg_match($patternSg, $groupData[1])) {
            $this->setError($groupData[1], "Wrong state ground with snow in group 4Esss - {$groupData[1]}");
        }

        $patternDs = '/^\d{3}|///$/';
        if (!preg_match($patternDs, $groupData[2])) {
            $this->setError($groupData[2], "Wrong depth snow in group 4Esss - {$groupData[2]}");
        }

        return $this->notExistsError($groupData);
    }

    /**
     * Returns the result of checking the validity of group 55SSS
     *
     * @param array $groupData
     * @return bool
     */
    public function sunshineRadiationDataGroupValid(array $groupData): bool
    {
        $patternIn = '/^55|//$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong indicator of sunshine and radiation group 55SSS - {$groupData[0]}");
        }

        $patternIn = '/^\d{3}|///$/';
        if (!preg_match($patternIn, $groupData[1])) {
            $this->setError($groupData[1], "Wrong duration of daily sunshine in group 55SSS - {$groupData[1]}");
        }

        return $this->notExistsError($groupData);
    }

    public function additionalCloudInformationGroupValid(array $groupData)
    {
        $patternIn = '/^8|/$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError($groupData[0], "Wrong indicator of group Additional cloud information transfer data of section three - {$groupData[0]}");
        }

        $patternA = '/^\d|/$/';
        if (!preg_match($patternA, $groupData[1])) {
            $this->setError($groupData[1], "Wrong amount of individual cloud layer of group Additional cloud information transfer data of section three - {$groupData[1]}");
        }

        $patternF = '/^\d|/$/';
        if (!preg_match($patternF, $groupData[2])) {
            $this->setError($groupData[2], "Wrong form of cloud of group Additional cloud information transfer data of section three - {$groupData[2]}");
        }

        $patternF = '/^\d{2}|//$/';
        if (!preg_match($patternF, $groupData[3])) {
            $this->setError($groupData[3], "Wrong height of base cloud of group Additional cloud information transfer data of section three - {$groupData[3]}");
        }
    }
}
