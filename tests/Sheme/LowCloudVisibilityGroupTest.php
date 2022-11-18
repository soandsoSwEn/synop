<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\LowCloudVisibilityGroup;

class LowCloudVisibilityGroupTest extends TestCase
{
    private $lowCloudVisibilityGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->lowCloudVisibilityGroup = new LowCloudVisibilityGroup('11583', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->lowCloudVisibilityGroup);
        Mockery::close();
    }

    public function testSuccessSetVisibility()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getVV')->andReturn(2);

        $this->lowCloudVisibilityGroup->setVisibility($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('visibility');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals(2, $value);
    }

    public function testErrorSetVisibility()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getVV')->andReturn(45);

        $this->lowCloudVisibilityGroup->setVisibility($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('visibility');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals(2, $value);
    }

    public function testSuccessSetHlowClouds()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getH')->andReturn('1000-1500');

        $this->lowCloudVisibilityGroup->setHlowClouds($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals('1000-1500', $value);
    }

    public function testSuccessIsStringSetHlowClouds()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getH')->andReturn('1000-1500');

        $this->lowCloudVisibilityGroup->setHlowClouds($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetHlowClouds()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getH')->andReturn('1000-1500');

        $this->lowCloudVisibilityGroup->setHlowClouds($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals('600-1000', $value);
    }

    public function testSuccessSetIncWeather()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIx')->andReturn(['Omitted (no significant phenomenon to report)', 'Manned']);

        $this->lowCloudVisibilityGroup->setIncWeather($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals(['Omitted (no significant phenomenon to report)', 'Manned'], $value);
    }

    public function testSuccessIsStringSetIncWeather()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIx')->andReturn(['Omitted (no significant phenomenon to report)', 'Manned']);

        $this->lowCloudVisibilityGroup->setIncWeather($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertIsArray($value);
    }

    public function testErrorSetIncWeather()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIx')->andReturn(['Omitted (no significant phenomenon to report)', 'Manned']);

        $this->lowCloudVisibilityGroup->setIncWeather($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals(['Included', 'Manned'], $value);
    }

    public function testSuccessSetIncPrecip()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIr')->andReturn('Included in section 3');

        $this->lowCloudVisibilityGroup->setIncPrecip($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals('Included in section 3', $value);
    }

    public function testSuccessIsStringSetIncPrecip()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIr')->andReturn('Included in section 3');

        $this->lowCloudVisibilityGroup->setIncPrecip($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetIncPrecip()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('getIr')->andReturn('Included in section 3');

        $this->lowCloudVisibilityGroup->setIncPrecip($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals('Included in section 1', $value);
    }

    public function testSuccessSetLcvGroup()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getVV')->once()->andReturn(2);
        $decoder->shouldReceive('getH')->once()->andReturn('1000-1500');
        $decoder->shouldReceive('getIx')->once()->andReturn(['Omitted (no significant phenomenon to report)', 'Manned']);
        $decoder->shouldReceive('getIr')->once()->andReturn('Included in section 3');

        $validate = Mockery::mock(Validate::class);

        $this->lowCloudVisibilityGroup->setLcvGroup($decoder, $validate);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $propertyVis = $reflector->getProperty('visibility');
        $propertyVis->setAccessible(true);
        $valueVis = $propertyVis->getValue($this->lowCloudVisibilityGroup);

        $propertyHlc = $reflector->getProperty('heightLowClouds');
        $propertyHlc->setAccessible(true);
        $valueHlc = $propertyHlc->getValue($this->lowCloudVisibilityGroup);

        $propertyInc = $reflector->getProperty('incWeatherGroup');
        $propertyInc->setAccessible(true);
        $valueInc = $propertyInc->getValue($this->lowCloudVisibilityGroup);

        $propertyIncPr = $reflector->getProperty('incPrecipGroup');
        $propertyIncPr->setAccessible(true);
        $valueIncPr = $propertyIncPr->getValue($this->lowCloudVisibilityGroup);

        $expected = [
            2,
            '1000-1500',
            ['Omitted (no significant phenomenon to report)', 'Manned'],
            'Included in section 3'
        ];

        $this->assertEquals($expected, [$valueVis, $valueHlc, $valueInc, $valueIncPr]);
    }

    public function testErrorSetLcvGroup()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->lowCloudVisibilityGroup->setLcvGroup($decoder, $validate);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $propertyVis = $reflector->getProperty('visibility');
        $propertyVis->setAccessible(true);
        $valueVis = $propertyVis->getValue($this->lowCloudVisibilityGroup);

        $propertyHlc = $reflector->getProperty('heightLowClouds');
        $propertyHlc->setAccessible(true);
        $valueHlc = $propertyHlc->getValue($this->lowCloudVisibilityGroup);

        $propertyInc = $reflector->getProperty('incWeatherGroup');
        $propertyInc->setAccessible(true);
        $valueInc = $propertyInc->getValue($this->lowCloudVisibilityGroup);

        $propertyIncPr = $reflector->getProperty('incPrecipGroup');
        $propertyIncPr->setAccessible(true);
        $valueIncPr = $propertyIncPr->getValue($this->lowCloudVisibilityGroup);

        $expected = [null, null, null, null];

        $this->assertEquals($expected, [$valueVis, $valueHlc, $valueInc, $valueIncPr]);
    }

    public function testSuccessGetVisibilityValue()
    {
        $this->assertEquals(45, $this->lowCloudVisibilityGroup->getVisibilityValue());
    }

    public function testErrorGetVisibilityValue()
    {
        $this->assertNotEquals(2, $this->lowCloudVisibilityGroup->getVisibilityValue());
    }

    public function testSuccessGetHeightLowValue()
    {
        $this->assertEquals('600-1000', $this->lowCloudVisibilityGroup->getHeightLowValue());
    }

    public function testErrorGetHeightLowValue()
    {
        $this->assertNotEquals('1000-1500', $this->lowCloudVisibilityGroup->getHeightLowValue());
    }

    public function testSuccessGetIncWeatherValue()
    {
        $this->assertEquals(['Included', 'Manned'], $this->lowCloudVisibilityGroup->getIncWeatherValue());
    }

    public function testSuccessInArrayGetIncWeatherValue()
    {
        $this->assertIsArray($this->lowCloudVisibilityGroup->getIncWeatherValue());
    }

    public function testErrorGetIncWeatherValue()
    {
        $this->assertNotEquals(['Omitted (no significant phenomenon to report)', 'Manned'], $this->lowCloudVisibilityGroup->getIncWeatherValue());
    }

    public function testSuccessGetIncPrecipValue()
    {
        $this->assertEquals('Included in section 1', $this->lowCloudVisibilityGroup->getIncPrecipValue());
    }

    public function testSuccessIsStringGetIncPrecipValue()
    {
        $this->assertIsString($this->lowCloudVisibilityGroup->getIncPrecipValue());
    }

    public function testErrorGetIncPrecipValue()
    {
        $this->assertNotEquals('Included in section 3', $this->lowCloudVisibilityGroup->getIncPrecipValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->lowCloudVisibilityGroup->getDecoder());
    }

    public function testSuccessSetVisibilityValue()
    {
        $this->lowCloudVisibilityGroup->setVisibilityValue('2');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('visibility');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals('2', $value);
    }

    public function testSuccessNullSetVisibilityValue()
    {
        $this->lowCloudVisibilityGroup->setVisibilityValue(null);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('visibility');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNull($value);
    }

    public function testErrorSetVisibilityValue()
    {
        $this->lowCloudVisibilityGroup->setVisibilityValue('2');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('visibility');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals('45', $value);
    }

    public function testSuccessSetHeightLowValue()
    {
        $this->lowCloudVisibilityGroup->setHeightLowValue('1000-1500');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals('1000-1500', $value);
    }

    public function testSuccessNullSetHeightLowValue()
    {
        $this->lowCloudVisibilityGroup->setHeightLowValue(null);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNull($value);
    }

    public function testErrorSetHeightLowValue()
    {
        $this->lowCloudVisibilityGroup->setHeightLowValue('1000-1500');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('heightLowClouds');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals('600-1000', $value);
    }

    public function testSuccessSetIncWeatherValue()
    {
        $this->lowCloudVisibilityGroup->setIncWeatherValue(['Omitted (no significant phenomenon to report)', 'Manned']);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals(['Omitted (no significant phenomenon to report)', 'Manned'], $value);
    }

    public function testSuccessIsArraySetIncWeatherValue()
    {
        $this->lowCloudVisibilityGroup->setIncWeatherValue(['Omitted (no significant phenomenon to report)', 'Manned']);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertIsArray($value);
    }

    public function testSuccessNullSetIncWeatherValue()
    {
        $this->lowCloudVisibilityGroup->setIncWeatherValue(null);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNull($value);
    }

    public function testErrorSetIncWeatherValue()
    {
        $this->lowCloudVisibilityGroup->setIncWeatherValue(['Omitted (no significant phenomenon to report)', 'Manned']);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incWeatherGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals(['Included', 'Manned'], $value);
    }

    public function testSuccessSetIncPrecipValue()
    {
        $this->lowCloudVisibilityGroup->setIncPrecipValue('Included in section 3');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertEquals('Included in section 3', $value);
    }

    public function testSuccessIsStringSetIncPrecipValue()
    {
        $this->lowCloudVisibilityGroup->setIncPrecipValue('Included in section 3');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertIsString($value);
    }

    public function testSuccessNullSetIncPrecipValue()
    {
        $this->lowCloudVisibilityGroup->setIncPrecipValue(null);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNull($value);
    }

    public function testErrorSetIncPrecipValue()
    {
        $this->lowCloudVisibilityGroup->setIncPrecipValue('Included in section 3');

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('incPrecipGroup');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertNotEquals('Included in section 1', $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(LowCloudVisibilityDecoder::class);

        $this->lowCloudVisibilityGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->lowCloudVisibilityGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->lowCloudVisibilityGroup->setData('11485', $validate);

        $reflector = new \ReflectionClass(LowCloudVisibilityGroup::class);
        $propertyVis = $reflector->getProperty('visibility');
        $propertyVis->setAccessible(true);
        $valueVis = $propertyVis->getValue($this->lowCloudVisibilityGroup);

        $propertyHlc = $reflector->getProperty('heightLowClouds');
        $propertyHlc->setAccessible(true);
        $valueHlc = $propertyHlc->getValue($this->lowCloudVisibilityGroup);

        $propertyInc = $reflector->getProperty('incWeatherGroup');
        $propertyInc->setAccessible(true);
        $valueInc = $propertyInc->getValue($this->lowCloudVisibilityGroup);

        $propertyIncPr = $reflector->getProperty('incPrecipGroup');
        $propertyIncPr->setAccessible(true);
        $valueIncPr = $propertyIncPr->getValue($this->lowCloudVisibilityGroup);

        $expected = [
            55,
            '300-600',
            ['Included', 'Manned'],
            'Included in section 1'
        ];

        $this->assertEquals($expected, [$valueVis, $valueHlc, $valueInc, $valueIncPr]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->lowCloudVisibilityGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('irixhVV', $this->lowCloudVisibilityGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->lowCloudVisibilityGroup->getGroupIndicator());
    }
}
