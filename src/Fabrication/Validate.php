<?php

namespace Soandso\Synop\Fabrication;

use Exception;
use Mockery;
use Soandso\Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Soandso\Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Soandso\Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Soandso\Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Soandso\Synop\Decoder\GroupDecoder\DateDecoder;
use Soandso\Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Soandso\Synop\Decoder\GroupDecoder\MaxAirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\MinAirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Soandso\Synop\Decoder\GroupDecoder\TypeDecoder;
use Soandso\Synop\Exception\CountSymbolException;
use Soandso\Synop\Exception\EmptyReportException;
use Soandso\Synop\Exception\EndSignException;
use Soandso\Synop\Sheme\RegionalExchangeAmountRainfallGroup;

/**
 * Class Validate contains methods for validating meteorological groups
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
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

    /**
     * @var array  Correspondence map of decoding classes and methods of validation of constituent parts
     * of weather report groups
     */
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
        MaxAirTemperatureDecoder::class => ['check' => 'maxAirTemperatureGroupValid'],
        MinAirTemperatureDecoder::class => ['check' => 'minAirTemperatureGroupValid'],
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
     * Displays weather report errors with groups (chunks weather group)
     *
     * @return array Weather report errors with groups
     */
    public function getErrorsWithGroups(): array
    {
        return $this->errors;
    }

    /**
     * Returns all meteorological weather report errors
     *
     * @return bool|array Weather report errors with groups
     * array[]['indicator_group'] - Indicator of the entire weather report group
     * array[]['description_indicator'] - Description of the group element
     * array[]['code_figure'] - Code figure of the group element
     * array[]['description_error'] - Description of the error
     */
    public function getErrors(): array
    {
        if (count($this->errors) == 0) {
            return $this->errors;
        }

        $errorsOutput = [];
        $i = 0;
        foreach ($this->errors as $key => $error) {
            foreach ($error as $codeFigure => $chunkError) {
                $errorsOutput[$i]['indicator_group'] = $key;
                $errorsOutput[$i]['description_indicator'] = $chunkError['description'];
                $errorsOutput[$i]['code_figure'] = $codeFigure;
                $errorsOutput[$i]['description_error'] = $chunkError['error'];
                $i++;
            }
        }

        return $errorsOutput;
    }

    /**
     * Returns a list of weather report errors
     *
     * @return bool|array
     */
    public function getShortListErrors(): array
    {
        $errorsOutput = [];
        if (count($this->errors) == 0) {
            return $errorsOutput;
        }

        foreach ($this->errors as $errorsOfGroup) {
            foreach ($errorsOfGroup as $errorItem) {
                $errorsOutput[] = $errorItem['error'];
            }
        }

        return $errorsOutput;
    }

    /**
     * Returns the weather group error by individual group
     *
     * @param string $groupIndicator Group indicator weather report
     * (Example, 'AAXX' - Code figure indicator of meteorological station index group)
     *
     * @return false|array
     * array['group_figure'] - Code figure of the group element
     * array['description_indicator'] - Description of the group element
     * array['code_figure'] - Code figure of the group element
     * array['description_error'] - Description of the error
     */
    public function getErrorByGroup(string $groupIndicator): array
    {
        if (!array_key_exists($groupIndicator, $this->errors)) {
            return false;
        }

        $errorsOutput = [];
        $i = 0;
        foreach ($this->errors[$groupIndicator] as $key => $error) {
            $errorsOutput[$i]['group_figure'] = $key;
            $errorsOutput[$i]['description_indicator'] = $error['description'];
            $errorsOutput[$i]['code_figure'] = $error['code'];
            $errorsOutput[$i]['description_error'] = $error['error'];
            $i++;
        }

        return $errorsOutput;
    }

    /**
     * Checks if the given weather group has errors
     *
     * @param string $group Indicator of component of groups of weather forecasting
     * @return bool
     */
    public function notExistsError(string $group): bool
    {
        return !array_key_exists($group, $this->errors);
    }

    /**
     * Sets error with her group
     *
     * @param string $groupIndicator Group figure indicator
     * @param string $group Chunk weather group
     * @param string $description Description of chunk weather group
     * @param string $code Code figure of chunk weather group
     * @param string $error An error in the meteorological group
     * @return void
     */
    protected function setError(
        string $groupIndicator,
        string $group,
        string $description,
        string $code,
        string $error
    ): void {
        $this->errors[$groupIndicator][$group]['description'] = $description;
        $this->errors[$groupIndicator][$group]['code'] = $code;
        $this->errors[$groupIndicator][$group]['error'] = $error;
    }

    /**
     * Performs initial preparation of weather reports for decoding
     *
     * @param string $report Weather report source code
     * @return string
     */
    public function preparation(string $report): string
    {
        return $this->clearDoubleSpacing($report);
    }

    /**
     * Performs a basic check of the entire weather report
     *
     * @return true
     * @throws CountSymbolException
     * @throws EndSignException
     * @throws Exception
     */
    public function check(): bool
    {
        if (!$this->report) {
            throw new Exception('Meteorological weather report not defined!');
        }

        if ($this->isEndEqualSign($this->report) === false) {
            throw new EndSignException($this->report);
        }

        if ($this->isCountSymbol($this->report) === false) {
            throw new CountSymbolException($this->getInvalidGroup($this->report));
        }

        if (!$this->isNotEmpty()) {
            throw new EmptyReportException($this->report, 'Weather report is empty');
        }

        return true;
    }

    /**
     * Performs a meteorological report check for emptiness
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return $this->isNil($this->report) === false;
    }

    /**
     * Performs validation of a single weather group
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param array $groupData Components of a weather group
     * @return mixed
     * @throws Exception
     */
    public function isValidGroup(GroupDecoderInterface $groupDecoderItem, string $groupIndicator, array $groupData)
    {
        if (!array_key_exists(get_class($groupDecoderItem), $this->groups)) {
            if (!$this->isClassFromMockery($groupDecoderItem)) {
                throw new Exception(
                    "The class object " . get_class($groupDecoderItem) . " specified for validation was not found"
                );
            }
        }

        if ($this->isMockeryClass($groupDecoderItem)) {
            $method = $this->groups[$this->getClassForMockery($groupDecoderItem)]['check'];
        } else {
            $method = $this->groups[get_class($groupDecoderItem)]['check'];
        }

        return $this->$method($groupDecoderItem, $groupIndicator, $groupData);
    }

    /**
     * Checks if there is a decoder class that emulates a Mockery object
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @return bool
     */
    protected function isClassFromMockery(GroupDecoderInterface $groupDecoderItem): bool
    {
        $className = '';
        $classNameData = explode('_', get_class($groupDecoderItem));
        if (count($classNameData) == 0) {
            return false;
        }

        for ($i = 2; $i <= count($classNameData) - 1; $i++) {
            if ($i === 2) {
                $className = $classNameData[$i];
            } else {
                $className .= "\\" . $classNameData[$i];
            }
        }

        return array_key_exists($className, $this->groups);
    }

    /**
     * Checks if an object is a MockInterface
     *
     * @param object $groupDecoderInstance
     * @return bool
     */
    protected function isMockeryClass(object $groupDecoderInstance): bool
    {
        return $groupDecoderInstance instanceof Mockery\MockInterface;
    }

    /**
     * Returns a class that emulates a Mockery object
     *
     * @param Mockery\MockInterface $groupDecoderItem
     * @return string
     */
    protected function getClassForMockery(Mockery\MockInterface $groupDecoderItem): string
    {
        $classNameData = explode('_', get_class($groupDecoderItem));
        $className = '';
        for ($i = 2; $i <= count($classNameData) - 1; $i++) {
            if ($i === 2) {
                $className = $classNameData[$i];
            } else {
                $className .= "\\" . $classNameData[$i];
            }
        }

        return $className;
    }

    //Valid methods
    /**
     * Returns the result of checking the validity of group AAXX/BBXX
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function typeValid(GroupDecoderInterface $groupDecoderItem, string $groupIndicator, array $groupData): bool
    {
        $patterns = ['/^AAXX$/', '/^BBXX$/'];
        foreach ($patterns as $pattern) {
            $isCheck = preg_match($pattern, $groupData[0]);
            if ($isCheck) {
                return true;
            }
        }

        $this->setError(
            $groupIndicator,
            key($groupDecoderItem->getTypeReportIndicator()),
            current($groupDecoderItem->getTypeReportIndicator()),
            $groupData[0],
            "The summary type group data does not match the specified format; Code group - {$groupData[0]}"
        );

        return false;
    }

    /**
     * Returns the result of checking the validity of group YYGGiw
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function dateValid(GroupDecoderInterface $groupDecoderItem, string $groupIndicator, array $groupData): bool
    {
        if (intval($groupData[0]) <= 0 || intval($groupData[0]) > 31) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDayIndicator()),
                current($groupDecoderItem->getDayIndicator()),
                $groupData[0],
                "Invalid day of the month - {$groupData[0]}"
            );
        }

        $patterns = '/^\d{2}$/';
        if (!preg_match($patterns, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getHourIndicator()),
                current($groupDecoderItem->getHourIndicator()),
                $groupData[1],
                "Wrong period of observation - {$groupData[1]}"
            );
        }

        if (is_null($groupData[2]) || !is_array($groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSpeedUnitsIndicator()),
                current($groupDecoderItem->getSpeedUnitsIndicator()),
                json_encode($groupData[2]),
                "Incorrect index of wind speed units and how to determine it - {$groupData[2][0]} => {$groupData[2][1]}"
            );
        }

        //$groupData[2] = json_encode($groupData[2]);

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group IIiii
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function indexValid(GroupDecoderInterface $groupDecoderItem, string $groupIndicator, array $groupData): bool
    {
        $patternArea = '/^\d{2}$/';
        if (!preg_match($patternArea, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getStationAreaIndicator()),
                current($groupDecoderItem->getStationAreaIndicator()),
                $groupData[0],
                "Wrong Meteorological Station Area Number - {$groupData[0]}"
            );
        }

        $patternNumber = '/^\d{3}$/';
        if (!preg_match($patternNumber, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getStationIndexIndicator()),
                current($groupDecoderItem->getStationIndexIndicator()),
                $groupData[1],
                "Wrong Meteorological station number within of area - {$groupData[1]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group irixhVV
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function lowCloudVisibilityValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIr = '/^[0-4]$/';
        if (!preg_match($patternIr, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetPrecipitationDataIndicator()),
                current($groupDecoderItem->getGetPrecipitationDataIndicator()),
                $groupData[0],
                "Wrong Index precipitation group inclusion 6RRRtr - {$groupData[0]}"
            );
        }

        $patternIx = '/^[1-7]$/';
        if (!preg_match($patternIx, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetWeatherGroupIndicator()),
                current($groupDecoderItem->getGetWeatherGroupIndicator()),
                $groupData[1],
                "Wrong Values of the type indicator of the station, as well as inclusion in the group '
                 . 'report 7wwW1W2 - {$groupData[1]}"
            );
        }

        $patternH = '/^\d$|^\/$/';
        if (!preg_match($patternH, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetHeightCloudIndicator()),
                current($groupDecoderItem->getGetHeightCloudIndicator()),
                $groupData[2],
                "Wrong Value for the height of the base of the lowest clouds above the surface of '
                 . 'the earth (sea) - {$groupData[2]}"
            );
        }

        $patternVV = '/^\d{2}$|^\/\/$/';
        if (!preg_match($patternVV, $groupData[3])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetVisibilityIndicator()),
                current($groupDecoderItem->getGetVisibilityIndicator()),
                $groupData[3],
                "Wrong meteorological range of visibility - {$groupData[3]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group Nddff
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function cloudWindGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternN = '/^\d$|^\/$/';
        if (!preg_match($patternN, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getTotalCloudIndicator()),
                current($groupDecoderItem->getTotalCloudIndicator()),
                $groupData[0],
                "Wrong number of clouds of group Nddff - {$groupData[0]}"
            );
        }

        $patternDd = '/^\d{2}$|^\/{2}$/';
        if (!preg_match($patternDd, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getWindDirectionIndicator()),
                current($groupDecoderItem->getWindDirectionIndicator()),
                $groupData[1],
                "Wrong wind direction of group Nddff - {$groupData[1]}"
            );
        }

        if (intval($groupData[1]) < 0 || intval($groupData[1]) > 36) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getWindDirectionIndicator()),
                current($groupDecoderItem->getWindDirectionIndicator()),
                $groupData[1],
                "Wrong wind direction of group Nddff - {$groupData[1]}"
            );
        }

        $patternVv = '/^\d{2}$|^\/{2}$/';
        if (!preg_match($patternVv, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getWindSpeedIndicator()),
                current($groupDecoderItem->getWindSpeedIndicator()),
                $groupData[2],
                "Wrong value of wind speed of group Nddff - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 1SnTTT
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function airTemperatureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternDn = '/^1$/';
        if (!preg_match($patternDn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong distinctive number of air temperature group 1SnTTT - {$groupData[0]}"
            );
        }

        $patternSt = '/^[0-1]$|^\/$/';
        if (!preg_match($patternSt, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSignTemperatureIndicator()),
                current($groupDecoderItem->getSignTemperatureIndicator()),
                $groupData[1],
                "Wrong sign of air temperature group 1SnTTT - {$groupData[1]}"
            );
        }

        $patternTv = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDryBulbTemperatureIndicator()),
                current($groupDecoderItem->getDryBulbTemperatureIndicator()),
                $groupData[2],
                "Wrong air temperature group 1SnTTT - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 2SnTxTxTx
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function maxAirTemperatureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternDn = '/^1$/';
        if (!preg_match($patternDn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong distinctive number of air temperature group 1SnTxTxTx - {$groupData[0]}"
            );
        }

        $patternSt = '/^[0-1]$|^\/$/';
        if (!preg_match($patternSt, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSignTemperatureIndicator()),
                current($groupDecoderItem->getSignTemperatureIndicator()),
                $groupData[1],
                "Wrong sign of min air temperature group 1SnTxTxTx - {$groupData[1]}"
            );
        }

        $patternTv = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDryBulbTemperatureIndicator()),
                current($groupDecoderItem->getDryBulbTemperatureIndicator()),
                $groupData[2],
                "Wrong air temperature group 1SnTxTxTx - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 2SnTnTnTn
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function minAirTemperatureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternDn = '/^2$/';
        if (!preg_match($patternDn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong distinctive number of air temperature group 2SnTnTnTn - {$groupData[0]}"
            );
        }

        $patternSt = '/^[0-1]$|^\/$/';
        if (!preg_match($patternSt, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSignTemperatureIndicator()),
                current($groupDecoderItem->getSignTemperatureIndicator()),
                $groupData[1],
                "Wrong sign of min air temperature group 2SnTnTnTn - {$groupData[1]}"
            );
        }

        $patternTv = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDryBulbTemperatureIndicator()),
                current($groupDecoderItem->getDryBulbTemperatureIndicator()),
                $groupData[2],
                "Wrong air temperature group 2SnTnTnTn - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 2SnTdTdTd
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function dewPointTemperatureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternDdw = '/^2$/';
        if (!preg_match($patternDdw, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong distinctive number of dew point temperature group 2SnTdTdTd - {$groupData[0]}"
            );
        }

        $patternSdw = '/^[0-1]$|^\/$/';
        if (!preg_match($patternSdw, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSignTemperatureIndicator()),
                current($groupDecoderItem->getSignTemperatureIndicator()),
                $groupData[1],
                "Wrong sign of dew point temperature group 2SnTdTdTd - {$groupData[1]}"
            );
        }

        $patternTv = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternTv, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDryBulbTemperatureIndicator()),
                current($groupDecoderItem->getDryBulbTemperatureIndicator()),
                $groupData[2],
                "Wrong dew point temperature group 2SnTdTdTd - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 3P0P0P0P0
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function stLPressureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternSp = '/^3$/';
        if (!preg_match($patternSp, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of atmospheric pressure at the station level group 3P0P0P0P0 - {$groupData[0]}"
            );
        }

        $patternSp = '/^\d{4}$|^\/\/\/\/$/';
        if (!preg_match($patternSp, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getFigureAirPressure()),
                current($groupDecoderItem->getFigureAirPressure()),
                $groupData[1],
                "Wrong atmospheric pressure at the station level group 3P0P0P0P0 - {$groupData[1]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 4PPPP
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function mslPressureGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternSp = '/^4$/';
        if (!preg_match($patternSp, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of air Pressure reduced to mean sea level group 4PPPP - {$groupData[0]}"
            );
        }

        $patternSp = '/^\d{4}$|^\/\/\/\/$/';
        if (!preg_match($patternSp, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getFigureAirPressure()),
                current($groupDecoderItem->getFigureAirPressure()),
                $groupData[1],
                "Wrong air Pressure reduced to mean sea level group 4PPPP - {$groupData[1]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 5appp
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function baricTendencyGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^5$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of Pressure change over last three hours group 5appp - {$groupData[0]}"
            );
        }

        $patternCh = '/^[0-8]$|^\/$/';
        if (!preg_match($patternCh, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getCharacteristicChangeIndicator()),
                current($groupDecoderItem->getCharacteristicChangeIndicator()),
                $groupData[1],
                "Wrong Characteristic of Pressure change group 5appp - {$groupData[1]}"
            );
        }

        $patternChan = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternChan, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getPressureChangeIndicator()),
                current($groupDecoderItem->getPressureChangeIndicator()),
                $groupData[2],
                "Wrong Pressure change over last three hours group 5appp - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 6RRRtr
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function amountRainfallGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^6$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of amount rainfall group 6RRRtr - {$groupData[0]}"
            );
        }

        $patternRa = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternRa, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getAmountRainfallIndicator()),
                current($groupDecoderItem->getAmountRainfallIndicator()),
                $groupData[1],
                "Wrong value of amount rainfall group 6RRRtr - {$groupData[1]}"
            );
        }

        $patternDp = '/^[1-9]$|^\/$/';
        if (!preg_match($patternDp, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDurationPeriodIndicator()),
                current($groupDecoderItem->getDurationPeriodIndicator()),
                $groupData[2],
                "Wrong duration period of amount rainfall group 6RRRtr - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 7wwW1W2
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function presentWeatherGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^7$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of Present weather group 7wwW1W2 - {$groupData[0]}"
            );
        }

        $patternPrw = '/^\d{2}$|^\/\/$/';
        if (!preg_match($patternPrw, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getPresentWeatherIndicator()),
                current($groupDecoderItem->getPresentWeatherIndicator()),
                $groupData[1],
                "Wrong Present weather of group 7wwW1W2 - {$groupData[1]}"
            );
        }

        $patternPsw = '/^\d{2}$|^\/\/$/';
        if (!preg_match($patternPsw, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getPastWeatherIndicator()),
                current($groupDecoderItem->getPastWeatherIndicator()),
                $groupData[2],
                "Wrong Past weather of group 7wwW1W2 - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 8NhClCmCh
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function cloudPresentGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^8$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong of indicator of Present clouds group 8NhClCmCh - {$groupData[0]}"
            );
        }

        $patternA = '/^\d$|^\/$/';
        if (!preg_match($patternA, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getAmountCloudIndicator()),
                current($groupDecoderItem->getAmountCloudIndicator()),
                $groupData[1],
                "Wrong of amount of low cloud or medium cloud 8NhClCmCh - {$groupData[1]}"
            );
        }

        $patternFlc = '/^\d$|^\/$/';
        if (!preg_match($patternFlc, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getFormLowCloudIndicator()),
                current($groupDecoderItem->getFormLowCloudIndicator()),
                $groupData[2],
                "Wrong form of low cloud 8NhClCmCh - {$groupData[2]}"
            );
        }

        $patternFmc = '/^\d$|^\/$/';
        if (!preg_match($patternFmc, $groupData[3])) {
            $this->setError(
                get_class($groupDecoderItem),
                key($groupDecoderItem->getFormMediumCloudIndicator()),
                current($groupDecoderItem->getFormMediumCloudIndicator()),
                $groupData[3],
                "Wrong form of medium cloud 8NhClCmCh - {$groupData[3]}"
            );
        }

        $patternFhc = '/^\d$|^\/$/';
        if (!preg_match($patternFhc, $groupData[4])) {
            $this->setError(
                get_class($groupDecoderItem),
                key($groupDecoderItem->getFormHighCloudIndicator()),
                current($groupDecoderItem->getFormHighCloudIndicator()),
                $groupData[4],
                "Wrong form of high cloud 8NhClCmCh - {$groupData[4]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 3ESnTgTg
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function groundWithoutSnowGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^3$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong indicator of ground without snow group 3ESnTgTg - {$groupData[0]}"
            );
        }

        $patternSg = '/^\d$|^\/$/';
        if (!preg_match($patternSg, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getStateGroundIndicator()),
                current($groupDecoderItem->getStateGroundIndicator()),
                $groupData[1],
                "Wrong state of ground group 3ESnTgTg - {$groupData[1]}"
            );
        }

        $patternSn = '/^[0-1]$|^\/$/';
        if (!preg_match($patternSn, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getSignTemperatureIndicator()),
                current($groupDecoderItem->getSignTemperatureIndicator()),
                $groupData[2],
                "Wrong sign temperature group 3ESnTgTg - {$groupData[2]}"
            );
        }

        $patternMt = '/^\d{2}$|^\/\/$/';
        if (!preg_match($patternMt, $groupData[3])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getMinimumTemperature()),
                current($groupDecoderItem->getMinimumTemperature()),
                $groupData[3],
                "Wrong minimum temperature 3ESnTgTg - {$groupData[3]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 4Esss
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function groundWithSnowGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^4$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong indicator of state ground with snow group 4Esss - {$groupData[0]}"
            );
        }

        $patternSg = '/^\d$|^\/$/';
        if (!preg_match($patternSg, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getStateGroundIndicator()),
                current($groupDecoderItem->getStateGroundIndicator()),
                $groupData[1],
                "Wrong state ground with snow in group 4Esss - {$groupData[1]}"
            );
        }

        $patternDs = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternDs, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDepthSnowIndicator()),
                current($groupDecoderItem->getDepthSnowIndicator()),
                $groupData[2],
                "Wrong depth snow in group 4Esss - {$groupData[2]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group 55SSS
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function sunshineRadiationDataGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^55$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getGetIndicatorGroup()),
                current($groupDecoderItem->getGetIndicatorGroup()),
                $groupData[0],
                "Wrong indicator of sunshine and radiation group 55SSS - {$groupData[0]}"
            );
        }

        $patternIn = '/^\d{3}$|^\/\/\/$/';
        if (!preg_match($patternIn, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getDurationTinderIndicator()),
                current($groupDecoderItem->getDurationTinderIndicator()),
                $groupData[1],
                "Wrong duration of daily sunshine in group 55SSS - {$groupData[1]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }

    /**
     * Returns the result of checking the validity of group Additional cloud information
     * transfer data of section three - 333 8NsChshs
     *
     * @param GroupDecoderInterface $groupDecoderItem Group decoder instance class
     * @param string $groupIndicator Group indicator
     * @param array $groupData Components of a weather group
     * @return bool
     */
    public function additionalCloudInformationGroupValid(
        GroupDecoderInterface $groupDecoderItem,
        string $groupIndicator,
        array $groupData
    ): bool {
        $patternIn = '/^8$/';
        if (!preg_match($patternIn, $groupData[0])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getIndicatorGroup()),
                current($groupDecoderItem->getIndicatorGroup()),
                $groupData[0],
                "Wrong indicator of group Additional cloud information transfer data of section three - {$groupData[0]}"
            );
        }

        $patternA = '/^\d$|^\/$/';
        if (!preg_match($patternA, $groupData[1])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getAmountCloudLayerIndicator()),
                current($groupDecoderItem->getAmountCloudLayerIndicator()),
                $groupData[1],
                "Wrong amount of individual cloud layer of group Additional cloud information transfer data '
                 . 'of section three - {$groupData[1]}"
            );
        }

        $patternF = '/^\d$|^\/$/';
        if (!preg_match($patternF, $groupData[2])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getFormCloudIndicator()),
                current($groupDecoderItem->getFormCloudIndicator()),
                $groupData[2],
                "Wrong form of cloud of group Additional cloud information transfer data of '
                 . 'section three - {$groupData[2]}"
            );
        }

        $patternF = '/^\d{2}$|^\/\/$/';
        if (!preg_match($patternF, $groupData[3])) {
            $this->setError(
                $groupIndicator,
                key($groupDecoderItem->getHeightCloudIndicator()),
                current($groupDecoderItem->getHeightCloudIndicator()),
                $groupData[3],
                "Wrong height of base cloud of group Additional cloud information transfer data '
                 . 'of section three - {$groupData[3]}"
            );
        }

        return $this->notExistsError($groupIndicator);
    }
}
