<?php

namespace Soandso\Synop\Tests\Fabrication;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Soandso\Synop\Fabrication\Validate;

class ValidateTest extends TestCase
{
    private $validate;

    protected function setUp(): void
    {
        $this->validate = new Validate('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=');
    }

    protected function tearDown(): void
    {
        unset($this->validate);
    }

    public function testSuccessTypeValid()
    {
        $this->assertTrue($this->validate->typeValid(['AAXX']));
    }

    public function testSuccessErrorsEmptyTypeValid()
    {
        $this->validate->typeValid(['AAXX']);

        $reflector = new \ReflectionClass(Validate::class);
        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $this->assertEquals(count($value), 0);
    }

    public function testErrorTypeValid()
    {
        $this->assertFalse($this->validate->typeValid(['AACC']));
    }

    public function testErrorSourceErrorsTypeValid()
    {
        $this->validate->typeValid(['AACC']);

        $reflector = new \ReflectionClass(Validate::class);
        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $expected = ["AACC" => "The summary type group data does not match the specified format; Code group - AACC"];

        $this->assertEquals($expected, $value);
    }

    public function testSuccessDateValid()
    {
        $this->assertTrue($this->validate->dateValid(['07', '18', ['Instrumental', 'm/s']]));
    }

    public function testErrorDayDateValid()
    {
        $this->assertFalse($this->validate->dateValid(['32', '18', ['Instrumental', 'm/s']]));
    }

    public function testErrorHourDateValid()
    {
        $this->assertFalse($this->validate->dateValid(['31', 'two', ['Instrumental', 'm/s']]));
    }

    public function testErrorIndexSpeedUnitDateValid()
    {
        $this->assertFalse($this->validate->dateValid(['31', 'two', 'm/s']));
    }

    public function testSuccessIndexValid()
    {
        $this->assertTrue($this->validate->indexValid(['33', '837']));
    }

    public function testErrorIndexValid()
    {
        $this->assertFalse($this->validate->indexValid(['tree', '83']));
    }

    public function testErrorAreaNumberIndexValid()
    {
        $this->assertFalse($this->validate->indexValid(['tree', '837']));
    }

    public function testErrorStationNumberIndexValid()
    {
        $this->assertFalse($this->validate->indexValid(['33', '83']));
    }

    public function testSuccessLowCloudVisibilityValid()
    {
        $this->assertTrue($this->validate->lowCloudVisibilityValid(['1', '1', '5', '83']));
    }

    public function testErrorLowCloudVisibilityValid()
    {
        $this->assertFalse($this->validate->lowCloudVisibilityValid(['5', '9', '55', '8']));
    }

    public function testErrorIndexPrecipitationLowCloudVisibilityValid()
    {
        $this->assertFalse($this->validate->lowCloudVisibilityValid(['5', '1', '5', '83']));
    }

    public function testErrorValuesTypeIndicatorLowCloudVisibilityValid()
    {
        $this->assertFalse($this->validate->lowCloudVisibilityValid(['1', '9', '5', '83']));
    }

    public function testErrorValueHeightLowCloudVisibilityValid()
    {
        $this->assertFalse($this->validate->lowCloudVisibilityValid(['1', '1', '55', '83']));
    }

    public function testErrormeteorologicaVisibilityLowCloudVisibilityValid()
    {
        $this->assertFalse($this->validate->lowCloudVisibilityValid(['1', '1', '5', '8']));
    }

    public function testSuccessCloudWindGroupValid()
    {
        $this->assertTrue($this->validate->cloudWindGroupValid(['8', '31', '02']));
    }

    public function testErrorCloudWindGroupValid()
    {
        $this->assertFalse($this->validate->cloudWindGroupValid(['\\', '\\', '38']));
    }

    public function testErrorNumberCloudsCloudWindGroupValid()
    {
        $this->assertFalse($this->validate->cloudWindGroupValid(['\\', '31', '02']));
    }

    public function testErrorWindDirectionCloudsCloudWindGroupValid()
    {
        $this->assertFalse($this->validate->cloudWindGroupValid(['8', '\\\\', '02']));
    }

    public function testErrorWindDirectionValueCloudWindGroupValid()
    {
        $this->assertFalse($this->validate->cloudWindGroupValid(['8', '38', '02']));
    }

    public function testErrorWindSpeedCloudsCloudWindGroupValid()
    {
        $this->assertFalse($this->validate->cloudWindGroupValid(['8', '31', '2']));
    }

    public function testSuccessAirTemperatureGroupValid()
    {
        $this->assertTrue($this->validate->airTemperatureGroupValid(['1', '0', '039']));
    }

    public function testErrorAirTemperatureGroupValid()
    {
        $this->assertFalse($this->validate->airTemperatureGroupValid(['\\', '5', 'nil']));
    }

    public function testErrorDistinctiveNumberAirTemperatureGroupValid()
    {
        $this->assertFalse($this->validate->airTemperatureGroupValid(['5', '0', '039']));
    }

    public function testErrorSignAirTemperatureGroupValid()
    {
        $this->assertFalse($this->validate->airTemperatureGroupValid(['1', '5', '039']));
    }

    public function testErrorAirTemperatureAirTemperatureGroupValid()
    {
        $this->assertFalse($this->validate->airTemperatureGroupValid(['1', '0', '/39']));
    }

    public function testSuccessDewPointTemperatureGroupValid()
    {
        $this->assertTrue($this->validate->dewPointTemperatureGroupValid(['2', '1', '007']));
    }

    public function testSuccessStLPressureGroupValid()
    {
        $this->assertTrue($this->validate->stLPressureGroupValid(['3', '0049']));
    }

    public function testErrorStLPressureGroupValid()
    {
        $this->assertFalse($this->validate->stLPressureGroupValid(['three', '49']));
    }

    public function testErrorIndicatorStLPressureGroupValid()
    {
        $this->assertFalse($this->validate->stLPressureGroupValid(['1', '0049']));
    }

    public function testErrorAtmosphericPressureStLPressureGroupValid()
    {
        $this->assertFalse($this->validate->stLPressureGroupValid(['3', '/049']));
    }

    public function testSuccessMslPressureGroupValid()
    {
        $this->assertTrue($this->validate->mslPressureGroupValid(['4', '0101']));
    }

    public function testErrorMslPressureGroupValid()
    {
        $this->assertFalse($this->validate->mslPressureGroupValid(['5', '/101']));
    }

    public function testErrorIndicatorMslPressureGroupValid()
    {
        $this->assertFalse($this->validate->mslPressureGroupValid(['5', '0101']));
    }

    public function testErrorPressureMslPressureGroupValid()
    {
        $this->assertFalse($this->validate->mslPressureGroupValid(['4', '/101']));
    }

    public function testSuccessBaricTendencyGroupValid()
    {
        $this->assertTrue($this->validate->baricTendencyGroupValid(['5', '2', '035']));
    }

    public function testErrorBaricTendencyGroupValid()
    {
        $this->assertFalse($this->validate->baricTendencyGroupValid(['4', '\\', '/35']));
    }

    public function testErrorIndicatorBaricTendencyGroupValid()
    {
        $this->assertFalse($this->validate->baricTendencyGroupValid(['4', '2', '035']));
    }

    public function testErrorCharacteristicBaricTendencyGroupValid()
    {
        $this->assertFalse($this->validate->baricTendencyGroupValid(['5', 'two', '035']));
    }

    public function testErrorPressureChangeBaricTendencyGroupValid()
    {
        $this->assertFalse($this->validate->baricTendencyGroupValid(['5', '2', '/35']));
    }

    public function testSuccessAmountRainfallGroupValid()
    {
        $this->assertTrue($this->validate->amountRainfallGroupValid(['6', '001', '2']));
    }

    public function testErrorAmountRainfallGroupValid()
    {
        $this->assertFalse($this->validate->amountRainfallGroupValid(['5', '0011', '25']));
    }

    public function testErrorIndicatorAmountRainfallGroupValid()
    {
        $this->assertFalse($this->validate->amountRainfallGroupValid(['5', '001', '2']));
    }

    public function testErrorValueAmountRainfallAmountRainfallGroupValid()
    {
        $this->assertFalse($this->validate->amountRainfallGroupValid(['6', '/01', '2']));
    }

    public function testErrorDurationPeriodAmountRainfallGroupValid()
    {
        $this->assertFalse($this->validate->amountRainfallGroupValid(['6', '001', '\\']));
    }

    public function testSuccessPresentWeatherGroupValid()
    {
        $this->assertTrue($this->validate->presentWeatherGroupValid(['7', '02', '82']));
    }

    public function testErrorPresentWeatherGroupValid()
    {
        $this->assertFalse($this->validate->presentWeatherGroupValid(['8', '022', '822']));
    }

    public function testErrorIndicatorPresentWeatherGroupValid()
    {
        $this->assertFalse($this->validate->presentWeatherGroupValid(['8', '02', '82']));
    }

    public function testErrorPresentWeatherPresentWeatherGroupValid()
    {
        $this->assertFalse($this->validate->presentWeatherGroupValid(['7', 'ICE', '82']));
    }

    //TODO Analyse
    public function testErrorPastWeatherPresentWeatherGroupValid()
    {
        $this->assertFalse($this->validate->presentWeatherGroupValid(['7', '02', '2']));
    }

    public function testSuccessCloudPresentGroupValid()
    {
        $this->assertTrue($this->validate->cloudPresentGroupValid(['8', '2', '5', '5', '/']));
    }

    public function testErrorCloudPresentGroupValid()
    {
        $this->assertFalse($this->validate->cloudPresentGroupValid(['88', '22', '55', '55', '//']));
    }

    public function testErrorIndicatorCloudPresentGroupValid()
    {
        $this->assertFalse($this->validate->cloudPresentGroupValid(['88', '2', '5', '5', '/']));
    }

    public function testSuccessGroundWithoutSnowGroupValid()
    {
        $this->assertTrue($this->validate->groundWithoutSnowGroupValid(['3', '4', '0', '08']));
    }

    public function testErrorGroundWithoutSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithoutSnowGroupValid(['33', '44', '00', '088']));
    }

    public function testErrorIndicatorGroundWithoutSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithoutSnowGroupValid(['33', '4', '0', '08']));
    }

    public function testErrorStateGroundGroundWithoutSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithoutSnowGroupValid(['3', '44', '0', '08']));
    }

    public function testErrorSignTemperatureGroundGroundWithoutSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithoutSnowGroupValid(['3', '4', '2', '08']));
    }

    public function testErrorMinimumTemperatureGroundGroundWithoutSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithoutSnowGroupValid(['3', '4', '0', '088']));
    }

    public function testSuccessGroundWithSnowGroupValid()
    {
        $this->assertTrue($this->validate->groundWithSnowGroupValid(['4', '9', '998']));
    }

    public function testErrorGroundWithSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithSnowGroupValid(['44', '99', '9981']));
    }

    public function testErrorIndicatorGroundWithSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithSnowGroupValid(['44', '9', '998']));
    }

    public function testErrorStateGroundGroundWithSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithSnowGroupValid(['4', '99', '998']));
    }

    public function testErrorDepthSnowGroundWithSnowGroupValid()
    {
        $this->assertFalse($this->validate->groundWithSnowGroupValid(['4', '9', '99']));
    }

    public function testSuccessSunshineRadiationDataGroupValid()
    {
        $this->assertTrue($this->validate->sunshineRadiationDataGroupValid(['55', '118']));
    }

    public function testErrorSunshineRadiationDataGroupValid()
    {
        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid(['5', '11']));
    }

    public function testErrorIndicatorSunshineRadiationDataGroupValid()
    {
        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid(['5', '118']));
    }

    public function testErrorDurationSunshineSunshineRadiationDataGroupValid()
    {
        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid(['55', '11']));
    }

    public function testSuccessAdditionalCloudInformationGroupValid()
    {
        $this->assertTrue($this->validate->additionalCloudInformationGroupValid(['8', '8', '5', '18']));
    }

    public function testErrorAdditionalCloudInformationGroupValid()
    {
        $this->assertFalse($this->validate->additionalCloudInformationGroupValid(['88', '89', '59', '189']));
    }

    public function testErrorIndicatorAdditionalCloudInformationGroupValid()
    {
        $this->assertFalse($this->validate->additionalCloudInformationGroupValid(['88', '8', '5', '18']));
    }

    public function testErrorAmountIndividualCloudAdditionalCloudInformationGroupValid()
    {
        $this->assertFalse($this->validate->additionalCloudInformationGroupValid(['8', '89', '5', '18']));
    }

    public function testErrorFormCloudAdditionalCloudInformationGroupValid()
    {
        $this->assertFalse($this->validate->additionalCloudInformationGroupValid(['8', '8', '55', '18']));
    }

    public function testErrorHeightBaseAdditionalCloudInformationGroupValid()
    {
        $this->assertFalse($this->validate->additionalCloudInformationGroupValid(['8', '8', '5', '189']));
    }

    public function testSuccessEmpryGetErrorsWithGroups()
    {
        $this->assertEquals([], $this->validate->getErrorsWithGroups());
    }

    public function testSuccessNotEmpryGetErrorsWithGroups()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertEquals(['AACC' => 'The summary type group data does not match the specified format; Code group - AACC'], $this->validate->getErrorsWithGroups());
    }

    public function testSuccessIsArrayGetErrorsWithGroups()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertIsArray($this->validate->getErrorsWithGroups());
    }

    public function testSuccessGetErrors()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $expected = ['The summary type group data does not match the specified format; Code group - AACC'];

        $this->assertEquals($expected, $this->validate->getErrors());
    }

    public function testSuccessEmptyErrorsGetErrors()
    {
        $this->assertEquals([], $this->validate->getErrors());
    }

    public function testSuccessIsArrayGetErrors()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertIsArray($this->validate->getErrors());
    }

    public function testSuccessGetErrorByGroup()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $expected = 'The summary type group data does not match the specified format; Code group - AACC';

        $this->assertEquals($expected, $this->validate->getErrorByGroup('AACC'));
    }

    public function testSuccessIsStringGetErrorByGroup()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertIsString($this->validate->getErrorByGroup('AACC'));
    }

    public function testSuccessTrueNotExistsError()
    {
        $this->assertTrue($this->validate->notExistsError(['AAXX']));
    }

    public function testSuccessFalseNotExistsError()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertFalse($this->validate->notExistsError(['AACC']));
    }

    public function testSuccessSetError()
    {
        $reflector = new \ReflectionClass(Validate::class);
        $method = $reflector->getMethod('setError');
        $method->setAccessible(true);
        $method->invokeArgs($this->validate, ['AACC', 'The summary type group data does not match the specified format; Code group - AACC']);

        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $expected = ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC'];

        $this->assertEquals($expected, $value);
    }

    public function testSuccessIsArraySetError()
    {
        $reflector = new \ReflectionClass(Validate::class);
        $method = $reflector->getMethod('setError');
        $method->setAccessible(true);
        $method->invokeArgs($this->validate, ['AACC', 'The summary type group data does not match the specified format; Code group - AACC']);

        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $this->assertIsArray($value);
    }

    public function testSuccessPreparation()
    {
        $raw = 'AAXX 07181  33837 11583 83102 10039 21007 30049    40101 52035 60012 70282  8255/ 333 10091 555 1/004=';
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $this->validate->preparation($raw));
    }

    public function testSuccessIsStringPreparation()
    {
        $raw = 'AAXX 07181  33837 11583 83102 10039 21007 30049    40101 52035 60012 70282  8255/ 333 10091 555 1/004=';

        $this->assertIsString($this->validate->preparation($raw));
    }

    public function testSuccessIsValid()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertTrue($this->validate->isValid());
    }

    public function testExceptionIsValid()
    {
        $report = null;

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->expectException(\Exception::class);

        $this->validate->isValid();
    }

    public function testErrorEndEqualSignIsValid()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertFalse($this->validate->isValid());
    }

    public function testErrorCountSymbolIsValid()
    {
        $report = 'AAXX 0718 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertFalse($this->validate->isValid());
    }

    public function testSuccessIsValidGroup()
    {
        $this->assertTrue($this->validate->isValidGroup(IndexDecoder::class, ['33', '837']));
    }

    public function testErrorIsValidGroup()
    {
        $this->assertFalse($this->validate->isValidGroup(IndexDecoder::class, ['3', '837']));
    }
}
