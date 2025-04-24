<?php

namespace Soandso\Synop\Tests\Fabrication;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\PartData;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Sheme\AdditionalCloudInformationGroup;
use Soandso\Synop\Sheme\AirTemperatureGroup;
use Soandso\Synop\Sheme\AmountRainfallGroup;
use Soandso\Synop\Sheme\BaricTendencyGroup;
use Soandso\Synop\Sheme\BaseGroupWithUnits;
use Soandso\Synop\Sheme\CloudPresentGroup;
use Soandso\Synop\Sheme\CloudWindGroup;
use Soandso\Synop\Sheme\DateGroup;
use Soandso\Synop\Sheme\DewPointTemperatureGroup;
use Soandso\Synop\Sheme\GroundWithoutSnowGroup;
use Soandso\Synop\Sheme\GroundWithSnowGroup;
use Soandso\Synop\Sheme\GroupInterface;
use Soandso\Synop\Sheme\IndexGroup;
use Soandso\Synop\Sheme\LowCloudVisibilityGroup;
use Soandso\Synop\Sheme\MaxAirTemperatureGroup;
use Soandso\Synop\Sheme\MinAirTemperatureGroup;
use Soandso\Synop\Sheme\MslPressureGroup;
use Soandso\Synop\Sheme\PresentWeatherGroup;
use Soandso\Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\StLPressureGroup;
use Soandso\Synop\Sheme\SunshineRadiationDataGroup;
use Soandso\Synop\Sheme\TypeGroup;

class PartDataTest extends TestCase
{
    private $rawBlocksData;

    private $partData;

    protected function setUp(): void
    {
        $this->partData = new PartData();

        $sections = new Section('All Sections');
        //$sections->setBody(new Section('Section Zero'));
        //$sections->setBody(new Section('General Section'));

        $this->rawBlocksData = $sections;
    }

    protected function tearDown(): void
    {
        unset($this->rawBlocksData);
        unset($this->partData);
    }

    public function testSuccessSetUnit()
    {
        $unit = Mockery::mock(Unit::class);

        $this->partData->setUnit($unit);

        $reflector = new \ReflectionClass(PartData::class);
        $property = $reflector->getProperty('unit');
        $property->setAccessible(true);
        $value = $property->getValue($this->partData);

        $this->assertInstanceOf(UnitInterface::class, $value);
    }

    public function testSuccessGetBodyOfSection()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $this->assertEquals(0, count($this->partData->getBodyOfSection($this->rawBlocksData, 'Section Zero')));
    }

    public function testSuccessIsArrayGetBodyOfSection()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $this->assertIsArray($this->partData->getBodyOfSection($this->rawBlocksData, 'Section Zero'));
    }

    public function testSuccessGetGroupData()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $date = Mockery::mock(DateGroup::class);

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');

        $section->setBody($date);

        $body = $this->partData->getBodyOfSection($this->rawBlocksData, 'Section Zero');

        $this->assertInstanceOf(GroupInterface::class, $this->partData->getGroupData($body, DateGroup::class));
    }

    public function testNullGetGroupData()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $body = $this->partData->getBodyOfSection($this->rawBlocksData, 'Section Zero');

        $this->assertNull($this->partData->getGroupData($body, TypeGroup::class));
    }

    public function testSuccessGetTypeStation()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $typeGroup = Mockery::mock(TypeGroup::class);
        $typeGroup->shouldReceive('getType')->once()->andReturn('AAXX');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($typeGroup);

        $this->assertEquals('AAXX', $this->partData->getTypeStation($this->rawBlocksData));
    }

    public function testNullGetTypeStation()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $typeGroup = Mockery::mock(TypeGroup::class);
        $typeGroup->shouldReceive('getType')->once()->andReturn(null);

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($typeGroup);

        $this->assertNull($this->partData->getTypeStation($this->rawBlocksData));
    }

    public function testSuccessGetDayReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getDayValue')->once()->andReturn('08');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertEquals('08', $this->partData->getDayReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDayReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getDayValue')->once()->andReturn('08');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertIsString($this->partData->getDayReport($this->rawBlocksData));
    }

    public function testNullGetDayReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $this->assertNull($this->partData->getDayReport($this->rawBlocksData));
    }

    public function testSuccessGetHourReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getHourValue')->once()->andReturn('15');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertEquals('15', $this->partData->getHourReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetHourReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getHourValue')->once()->andReturn('15');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertIsString($this->partData->getHourReport($this->rawBlocksData));
    }

    public function testNullGetHourReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getHourReport($this->rawBlocksData));
    }

    public function testSuccessGetUnitWindReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $unit = new Unit();
        $this->partData->setUnit($unit);

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getIwValue')->once()->andReturn(['Instrumental', 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertEquals('m/s', $this->partData->getUnitWindReport($this->rawBlocksData));
    }

    public function testNullGetUnitWindReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getUnitWindReport($this->rawBlocksData));
    }

    public function testSuccessGetWindDetectionReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getIwValue')->once()->andReturn(['Instrumental', 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertEquals('Instrumental', $this->partData->getWindDetectionReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetWindDetectionReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $dateGroup = Mockery::mock(DateGroup::class);
        $dateGroup->shouldReceive('getIwValue')->once()->andReturn(['Instrumental', 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($dateGroup);

        $this->assertIsString($this->partData->getWindDetectionReport($this->rawBlocksData));
    }

    public function testNullGetWindDetectionReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getWindDetectionReport($this->rawBlocksData));
    }

    public function testSuccessGetAreaNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getAreaNumberValue')->once()->andReturn('33');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertEquals('33', $this->partData->getAreaNumberReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetAreaNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getAreaNumberValue')->once()->andReturn('33');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertIsString($this->partData->getAreaNumberReport($this->rawBlocksData));
    }

    public function testNullGetAreaNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAreaNumberReport($this->rawBlocksData));
    }

    public function testSuccessGetStationNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getStationNumberValue')->once()->andReturn('837');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertEquals('837', $this->partData->getStationNumberReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetStationNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getStationNumberValue')->once()->andReturn('837');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertIsString($this->partData->getStationNumberReport($this->rawBlocksData));
    }

    public function testNullGetStationNumberReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getStationNumberReport($this->rawBlocksData));
    }

    public function testSuccessGetStationIndexReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getStationIndexValue')->once()->andReturn('33837');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertEquals('33837', $this->partData->getStationIndexReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetStationIndexReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $indexGroup = Mockery::mock(IndexGroup::class);
        $indexGroup->shouldReceive('getStationIndexValue')->once()->andReturn('33837');

        $section = $this->rawBlocksData->getBodyByTitle('Section Zero');
        $section->setBody($indexGroup);

        $this->assertIsString($this->partData->getStationIndexReport($this->rawBlocksData));
    }

    public function testNullgGetStationIndexReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getStationIndexReport($this->rawBlocksData));
    }

    public function testSuccessGetInclusionPrecipitationReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncPrecipValue')->once()->andReturn('Included in section 1');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertEquals('Included in section 1', $this->partData->getInclusionPrecipitationReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetInclusionPrecipitationReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncPrecipValue')->once()->andReturn('Included in section 1');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertIsString($this->partData->getInclusionPrecipitationReport($this->rawBlocksData));
    }

    public function testNullGetInclusionPrecipitationReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Zero'));

        $this->assertNull($this->partData->getInclusionPrecipitationReport($this->rawBlocksData));
    }

    public function testSuccessGetInclusionWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncWeatherValue')->once()->andReturn(['Included', 'Manned']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertEquals('Included', $this->partData->getInclusionWeatherReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetInclusionWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncWeatherValue')->once()->andReturn(['Included', 'Manned']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertIsString($this->partData->getInclusionWeatherReport($this->rawBlocksData));
    }

    public function testNullGetInclusionWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getInclusionWeatherReport($this->rawBlocksData));
    }

    public function testSuccessGetTypeStationOperationReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncWeatherValue')->once()->andReturn(['Included', 'Manned']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertEquals('Manned', $this->partData->getTypeStationOperationReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetTypeStationOperationReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getIncWeatherValue')->once()->andReturn(['Included', 'Manned']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertIsString($this->partData->getTypeStationOperationReport($this->rawBlocksData));
    }

    public function testNullGetTypeStationOperationReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getTypeStationOperationReport($this->rawBlocksData));
    }

    public function testSuccessGetHeightLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getHeightLowValue')->once()->andReturn('600-1000');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertEquals('600-1000', $this->partData->getHeightLowCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetHeightLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $lowCloudGroup = Mockery::mock(LowCloudVisibilityGroup::class);
        $lowCloudGroup->shouldReceive('getHeightLowValue')->once()->andReturn('600-1000');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($lowCloudGroup);

        $this->assertIsString($this->partData->getHeightLowCloudReport($this->rawBlocksData));
    }

    public function testNullGetHeightLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getHeightLowCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetHeightLowCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getHeightLowValue')->once()->andReturn('600-1000');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['h' => 'm', 'VV' => 'km']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('m', $this->partData->getHeightLowCloudUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetHeightLowCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getHeightLowValue')->once()->andReturn('600-1000');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['h' => 'm', 'VV' => 'km']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getHeightLowCloudUnitReport($this->rawBlocksData));
    }

    public function testNullGetHeightLowCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getHeightLowCloudUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetVisibilityReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getVisibilityValue')->once()->andReturn('45');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('45', $this->partData->getVisibilityReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetVisibilityReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getVisibilityValue')->once()->andReturn('45');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getVisibilityReport($this->rawBlocksData));
    }

    public function testNullGetVisibilityReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getVisibilityReport($this->rawBlocksData));
    }

    public function testSuccessGetVisibilityUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getVisibilityValue')->once()->andReturn('45');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['h' => 'm', 'VV' => 'km']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('km', $this->partData->getVisibilityUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetVisibilityUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(LowCloudVisibilityGroup::class);
        $group->shouldReceive('getVisibilityValue')->once()->andReturn('45');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['h' => 'm', 'VV' => 'km']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getVisibilityUnitReport($this->rawBlocksData));
    }

    public function testNullGetVisibilityUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getVisibilityUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetTotalAmountCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getTotalCloudsValue')->once()->andReturn('10');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('10', $this->partData->getTotalAmountCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetTotalAmountCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getTotalCloudsValue')->once()->andReturn('10');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getTotalAmountCloudReport($this->rawBlocksData));
    }

    public function testNullGetTotalAmountCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getTotalAmountCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetWindDirectionReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getDirectionWindValue')->once()->andReturn('310');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('310', $this->partData->getWindDirectionReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetWindDirectionReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getDirectionWindValue')->once()->andReturn('310');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getWindDirectionReport($this->rawBlocksData));
    }

    public function testNullGetWindDirectionReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getWindDirectionReport($this->rawBlocksData));
    }

    public function testSuccessGetWindDirectionUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getDirectionWindValue')->once()->andReturn('310');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['dd' => 'degrees', 'ff' => 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('degrees', $this->partData->getWindDirectionUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetWindDirectionUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getDirectionWindValue')->once()->andReturn('310');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['dd' => 'degrees', 'ff' => 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getWindDirectionUnitReport($this->rawBlocksData));
    }

    public function testNullGetWindDirectionUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getWindDirectionUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetWindSpeedReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getWindSpeedValue')->once()->andReturn('2');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('2', $this->partData->getWindSpeedReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetWindSpeedReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getWindSpeedValue')->once()->andReturn('2');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getWindSpeedReport($this->rawBlocksData));
    }

    public function testNullGetWindSpeedReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getWindSpeedReport($this->rawBlocksData));
    }

    public function testSuccessGetWindSpeedUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getWindSpeedValue')->once()->andReturn('2');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['dd' => 'degrees', 'ff' => 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('m/s', $this->partData->getWindSpeedUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetWindSpeedUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudWindGroup::class);
        $group->shouldReceive('getWindSpeedValue')->once()->andReturn('2');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['dd' => 'degrees', 'ff' => 'm/s']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getWindSpeedUnitReport($this->rawBlocksData));
    }

    public function testNullGetWindSpeedUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getWindSpeedUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(3.9, $this->partData->getAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getAirTemperatureReport($this->rawBlocksData));
    }

    public function testNullGetAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessGetAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TTT' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('degree C', $this->partData->getAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(3.9);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TTT' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSNullGetAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetDewPointTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(DewPointTemperatureGroup::class);
        $group->shouldReceive('getResultDewPointValue')->once()->andReturn(-0.7);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(-0.7, $this->partData->getDewPointTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetDewPointTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(DewPointTemperatureGroup::class);
        $group->shouldReceive('getResultDewPointValue')->once()->andReturn(-0.7);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getDewPointTemperatureReport($this->rawBlocksData));
    }

    public function testNullGetDewPointTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDewPointTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessGetDewPointTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(DewPointTemperatureGroup::class);
        $group->shouldReceive('getResultDewPointValue')->once()->andReturn(-0.7);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TdTdTd' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('degree C', $this->partData->getDewPointTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDewPointTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(DewPointTemperatureGroup::class);
        $group->shouldReceive('getResultDewPointValue')->once()->andReturn(-0.7);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TdTdTd' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getDewPointTemperatureUnitReport($this->rawBlocksData));
    }

    public function testNullGetDewPointTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDewPointTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetStationLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(StLPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1004.9);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(1004.9, $this->partData->getStationLevelPressureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetStationLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(StLPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1004.9);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getStationLevelPressureReport($this->rawBlocksData));
    }

    public function testNullGetStationLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getStationLevelPressureReport($this->rawBlocksData));
    }

    public function testSuccessGetStationLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(StLPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1004.9);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['PoPoPoPo' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('hPa', $this->partData->getStationLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetStationLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(StLPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1004.9);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['PoPoPoPo' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getStationLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testNullGetStationLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getStationLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetSeaLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(MslPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1010.1);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(1010.1, $this->partData->getSeaLevelPressureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetSeaLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(MslPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1010.1);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getSeaLevelPressureReport($this->rawBlocksData));
    }

    public function testNullGetSeaLevelPressureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getSeaLevelPressureReport($this->rawBlocksData));
    }

    public function testSuccessGetSeaLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(MslPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1010.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['PPPP' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('hPa', $this->partData->getSeaLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetSeaLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(MslPressureGroup::class);
        $group->shouldReceive('getPressureValue')->once()->andReturn(1010.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['PPPP' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getSeaLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testNullGetSeaLevelPressureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getSeaLevelPressureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetBaricTendencyReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(BaricTendencyGroup::class);
        $group->shouldReceive('getTendencyValueData')->once()->andReturn(3.5);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(3.5, $this->partData->getBaricTendencyReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetBaricTendencyReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(BaricTendencyGroup::class);
        $group->shouldReceive('getTendencyValueData')->once()->andReturn('3.5');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getBaricTendencyReport($this->rawBlocksData));
    }

    public function testNullGetBaricTendencyReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getBaricTendencyReport($this->rawBlocksData));
    }

    public function testSuccessGetBaricTendencyUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(BaricTendencyGroup::class);
        $group->shouldReceive('getTendencyValueData')->once()->andReturn('3.5');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['ppp' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('hPa', $this->partData->getBaricTendencyUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetBaricTendencyUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(BaricTendencyGroup::class);
        $group->shouldReceive('getTendencyValueData')->once()->andReturn('3.5');
        $group->shouldReceive('getUnitValue')->once()->andReturn(['ppp' => 'hPa']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getBaricTendencyUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetAmountRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(1, $this->partData->getAmountRainfallReport($this->rawBlocksData));
    }

    public function testNullGetAmountRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAmountRainfallReport($this->rawBlocksData));
    }

    public function testSuccessGetAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['RRR' => 'mm']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('mm', $this->partData->getAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['RRR' => 'mm']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testNullGetAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AmountRainfallGroup::class);
        $group->shouldReceive('getDurationPeriodValue')->once()->andReturn('At 0600 and 1800 GMT');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('At 0600 and 1800 GMT', $this->partData->getDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(AmountRainfallGroup::class);
        $group->shouldReceive('getDurationPeriodValue')->once()->andReturn('At 0600 and 1800 GMT');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testNullGetDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testSuccessGetPresentWeatherCodeFigureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPresentWeatherSymbolValue')->once()->andReturn(02);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(02, $this->partData->getPresentWeatherCodeFigureReport($this->rawBlocksData));
    }

    public function testSuccessGetPresentWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPresentWeatherValue')->once()->andReturn('State of sky on the whole unchanged');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('State of sky on the whole unchanged', $this->partData->getPresentWeatherReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetPresentWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPresentWeatherValue')->once()->andReturn('State of sky on the whole unchanged');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getPresentWeatherReport($this->rawBlocksData));
    }

    public function testNullGetPresentWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getPresentWeatherReport($this->rawBlocksData));
    }

    public function testSuccessGetPastWeatherCodeFigureReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPastWeatherSymbolValue')->once()->andReturn(82);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals(82, $this->partData->getPastWeatherCodeFigureReport($this->rawBlocksData));
    }

    public function testSuccessGetPastWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPastWeatherValue')->once()->andReturn(['W1' => 'Shower(s)', 'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $expected = ['W1' => 'Shower(s)', 'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period'];

        $this->assertEquals($expected, $this->partData->getPastWeatherReport($this->rawBlocksData));
    }

    public function testSuccessIsArrayGetPastWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(PresentWeatherGroup::class);
        $group->shouldReceive('getPastWeatherValue')->once()->andReturn(['W1' => 'Shower(s)', 'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period']);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsArray($this->partData->getPastWeatherReport($this->rawBlocksData));
    }

    public function testNullGetPastWeatherReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getPastWeatherReport($this->rawBlocksData));
    }

    public function testSuccessGetAmountLowOrMiddleCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getAmountLowCloudValue')->once()->andReturn('2 eight of sky covered');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals('2 eight of sky covered', $this->partData->getAmountLowOrMiddleCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetAmountLowOrMiddleCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getAmountLowCloudValue')->once()->andReturn('2 eight of sky covered');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getAmountLowOrMiddleCloudReport($this->rawBlocksData));
    }

    public function testNullGetAmountLowOrMiddleCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAmountLowOrMiddleCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetFormLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormLowCloudValue')->once()->andReturn('Stratocumulus not resulting from the spreading out of Cumulus');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $expected = 'Stratocumulus not resulting from the spreading out of Cumulus';

        $this->assertEquals($expected, $this->partData->getFormLowCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetFormLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormLowCloudValue')->once()->andReturn('Stratocumulus not resulting from the spreading out of Cumulus');

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getFormLowCloudReport($this->rawBlocksData));
    }

    public function testNullGetFormLowCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getFormLowCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetFormMediumCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $value = 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole';

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormMediumCloudValue')->once()->andReturn($value);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals($value, $this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetFormMediumCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $value = 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole';

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormMediumCloudValue')->once()->andReturn($value);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testNullGetFormMediumCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetFormHighCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $value = 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds';

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormMediumCloudValue')->once()->andReturn($value);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertEquals($value, $this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetFormHighCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('General Section'));

        $value = 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds';

        $group = Mockery::mock(CloudPresentGroup::class);
        $group->shouldReceive('getFormMediumCloudValue')->once()->andReturn($value);

        $section = $this->rawBlocksData->getBodyByTitle('General Section');
        $section->setBody($group);

        $this->assertIsString($this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testNullGetFormHighCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getFormMediumCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetMaxAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MaxAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(9.1);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(9.1, $this->partData->getMaxAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetMaxAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MaxAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(9.1);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getMaxAirTemperatureReport($this->rawBlocksData));
    }

    public function testNullGetMaxAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMaxAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessGetMaxAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MaxAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(9.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TxTxTx' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('degree C', $this->partData->getMaxAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetMaxAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MaxAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(9.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TxTxTx' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getMaxAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testNullGetMaxAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMaxAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetMinAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MinAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(2.1);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(2.1, $this->partData->getMinAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetMinAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MinAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(2.1);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getMinAirTemperatureReport($this->rawBlocksData));
    }

    public function testNullGetMinAirTemperatureReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMinAirTemperatureReport($this->rawBlocksData));
    }

    public function testSuccessGetMinAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MinAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(2.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TnTnTn' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('degree C', $this->partData->getMinAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetMinAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(MinAirTemperatureGroup::class);
        $group->shouldReceive('getTemperatureValue')->once()->andReturn(2.1);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TnTnTn' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getMinAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testNullGetMinAirTemperatureUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMinAirTemperatureUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetStateGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getStateValue')->once()->andReturn('Surface of ground frozen');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString('Surface of ground frozen', $this->partData->getStateGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetStateGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getStateValue')->once()->andReturn('Surface of ground frozen');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getStateGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testNullGetStateGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Three Two'));

        $this->assertNull($this->partData->getStateGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testSuccessGetMinTemperatureOfGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getResultMinTemperature')->once()->andReturn(-3);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(-3, $this->partData->getMinTemperatureOfGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testSuccessIsIntGetMinTemperatureOfGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getResultMinTemperature')->once()->andReturn(-3);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsInt($this->partData->getMinTemperatureOfGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testNullGetMinTemperatureOfGroundWithoutSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMinTemperatureOfGroundWithoutSnowReport($this->rawBlocksData));
    }

    public function testSuccessGetMinTemperatureOfGroundWithoutSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getResultMinTemperature')->once()->andReturn(-3);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TgTg' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('degree C', $this->partData->getMinTemperatureOfGroundWithoutSnowUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetMinTemperatureOfGroundWithoutSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithoutSnowGroup::class);
        $group->shouldReceive('getResultMinTemperature')->once()->andReturn(-3);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['TgTg' => 'degree C']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getMinTemperatureOfGroundWithoutSnowUnitReport($this->rawBlocksData));
    }

    public function testNullGetMinTemperatureOfGroundWithoutSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getMinTemperatureOfGroundWithoutSnowUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetStateGroundWithSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getStateValue')->once()->andReturn('Loose dry snow covering less than one-half of the ground');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $expected = 'Loose dry snow covering less than one-half of the ground';

        $this->assertEquals($expected, $this->partData->getStateGroundWithSnowReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetStateGroundWithSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getStateValue')->once()->andReturn('Loose dry snow covering less than one-half of the ground');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getStateGroundWithSnowReport($this->rawBlocksData));
    }

    public function testNullGetStateGroundWithSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getStateGroundWithSnowReport($this->rawBlocksData));
    }

    public function testSuccessGetDepthSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getDepthSnowValue')->once()->andReturn(['value' => 'Less than 0.5 cm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('Less than 0.5 cm', $this->partData->getDepthSnowReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDepthSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getDepthSnowValue')->once()->andReturn(['value' => 'Less than 0.5 cm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getDepthSnowReport($this->rawBlocksData));
    }

    public function testNullGetDepthSnowReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDepthSnowReport($this->rawBlocksData));
    }

    public function testSuccessGetDepthSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getDepthSnowValue')->once()->andReturn(['value' => 'Less than 0.5 cm']);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['sss' => 'cm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('cm', $this->partData->getDepthSnowUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDepthSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(GroundWithSnowGroup::class);
        $group->shouldReceive('getDepthSnowValue')->once()->andReturn(['value' => 'Less than 0.5 cm']);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['sss' => 'cm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getDepthSnowUnitReport($this->rawBlocksData));
    }

    public function testNullGetDepthSnowUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDepthSnowUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetDurationSunshineReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(SunshineRadiationDataGroup::class);
        $group->shouldReceive('getSunshineValue')->once()->andReturn(8);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(8, $this->partData->getDurationSunshineReport($this->rawBlocksData));
    }

    public function testSuccessIsFloatGetDurationSunshineReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(SunshineRadiationDataGroup::class);
        $group->shouldReceive('getSunshineValue')->once()->andReturn(8);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsFloat($this->partData->getDurationSunshineReport($this->rawBlocksData));
    }

    public function testNullGetDurationSunshineReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDurationSunshineReport($this->rawBlocksData));
    }

    public function testSuccessGetDurationSunshineUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(SunshineRadiationDataGroup::class);
        $group->shouldReceive('getSunshineValue')->once()->andReturn(8);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['SSS' => 'hour']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('hour', $this->partData->getDurationSunshineUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetDurationSunshineUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(SunshineRadiationDataGroup::class);
        $group->shouldReceive('getSunshineValue')->once()->andReturn(8);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['SSS' => 'hour']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getDurationSunshineUnitReport($this->rawBlocksData));
    }

    public function testNullGetDurationSunshineUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getDurationSunshineUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetRegionalExchangeAmountRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(1, $this->partData->getRegionalExchangeAmountRainfallReport($this->rawBlocksData));
    }

    public function testSuccessIsIntGetRegionalExchangeAmountRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsInt($this->partData->getRegionalExchangeAmountRainfallReport($this->rawBlocksData));
    }

    public function testNullGetRegionalExchangeAmountRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getRegionalExchangeAmountRainfallReport($this->rawBlocksData));
    }

    public function testSuccessGetRegionalExchangeAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['RRR' => 'mm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('mm', $this->partData->getRegionalExchangeAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetRegionalExchangeAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getAmountRainfallValue')->once()->andReturn([null, 1]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['RRR' => 'mm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getRegionalExchangeAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testNullGetRegionalExchangeAmountRainfallUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getRegionalExchangeAmountRainfallUnitReport($this->rawBlocksData));
    }

    public function testSuccessGetRegionalExchangeDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getDurationPeriodValue')->once()->andReturn('At 0001 and 1200 GMT');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString('At 0001 and 1200 GMT', $this->partData->getRegionalExchangeDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetRegionalExchangeDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(RegionalExchangeAmountRainfallGroup::class);
        $group->shouldReceive('getDurationPeriodValue')->once()->andReturn('At 0001 and 1200 GMT');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getRegionalExchangeDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testNullGetRegionalExchangeDurationPeriodRainfallReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getRegionalExchangeDurationPeriodRainfallReport($this->rawBlocksData));
    }

    public function testSuccessGetAmountIndividualCloudLayerReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getAmountCloudValue')->once()->andReturn('Sky completely covered');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('Sky completely covered', $this->partData->getAmountIndividualCloudLayerReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetAmountIndividualCloudLayerReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getAmountCloudValue')->once()->andReturn('Sky completely covered');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getAmountIndividualCloudLayerReport($this->rawBlocksData));
    }

    public function testNullGetAmountIndividualCloudLayerReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getAmountIndividualCloudLayerReport($this->rawBlocksData));
    }

    public function testSuccessGetFormClodReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getFormCloudValue')->once()->andReturn('Nimbostratus (Ns)');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('Nimbostratus (Ns)', $this->partData->getFormClodReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetFormClodReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getFormCloudValue')->once()->andReturn('Nimbostratus (Ns)');

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getFormClodReport($this->rawBlocksData));
    }

    public function testNullGetFormClodReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getFormClodReport($this->rawBlocksData));
    }

    public function testSuccessGetHeightCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getHeightCloudValue')->once()->andReturn(['Height' => 540]);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals(540, $this->partData->getHeightCloudReport($this->rawBlocksData));
    }

    public function testNullGetHeightCloudReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getHeightCloudReport($this->rawBlocksData));
    }

    public function testSuccessGetHeightCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getHeightCloudValue')->once()->andReturn(['Height' => 540]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['hshs' => 'm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertEquals('m', $this->partData->getHeightCloudUnitReport($this->rawBlocksData));
    }

    public function testSuccessIsStringGetHeightCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Three'));

        $group = Mockery::mock(AdditionalCloudInformationGroup::class);
        $group->shouldReceive('getHeightCloudValue')->once()->andReturn(['Height' => 540]);
        $group->shouldReceive('getUnitValue')->once()->andReturn(['hshs' => 'm']);

        $section = $this->rawBlocksData->getBodyByTitle('Section Three');
        $section->setBody($group);

        $this->assertIsString($this->partData->getHeightCloudUnitReport($this->rawBlocksData));
    }

    public function testNullGetHeightCloudUnitReport()
    {
        $this->rawBlocksData->setBody(new Section('Section Two'));

        $this->assertNull($this->partData->getHeightCloudUnitReport($this->rawBlocksData));
    }
}
