<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\GroundWithoutSnowGroup;

class GroundWithoutSnowGroupTest extends TestCase
{
    private $groundWithoutSnowGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->groundWithoutSnowGroup = new GroundWithoutSnowGroup('34008', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->groundWithoutSnowGroup);
        Mockery::close();
    }

    public function testSuccessBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(1, 8);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(-8, $value);
    }

    public function testSuccessInIntBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(1, 8);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertIsInt($value);
    }

    public function testSuccessPositivTempBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(0, 8);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(8, $value);
    }

    public function testNullSignBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(null, 8);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testNullTemperatureBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(1, null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testNullSignAndTemperatureBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(null, null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorTempBuildMinTemperature()
    {
        $this->groundWithoutSnowGroup->buildMinTemperature(1, 8);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(8, $value);
    }

    public function testSuccessSetMinTemperature()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundMinTemperature')->once()->andReturn(11);

        $this->groundWithoutSnowGroup->setMinTemperature($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(11, $value);
    }

    public function testSuccessIsIntSetMinTemperature()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundMinTemperature')->once()->andReturn(11);

        $this->groundWithoutSnowGroup->setMinTemperature($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetMinTemperature()
    {
        $this->groundWithoutSnowGroup->setMinTemperature(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetMinTemperature()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundMinTemperature')->once()->andReturn(11);

        $this->groundWithoutSnowGroup->setMinTemperature($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(5, $value);
    }

    public function testSuccessSetSign()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundSignTemperature')->once()->andReturn(0);

        $this->groundWithoutSnowGroup->setSign($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(0, $value);
    }

    public function testNullSetSign()
    {
        $this->groundWithoutSnowGroup->setSign(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetSign()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundSignTemperature')->once()->andReturn(1);

        $this->groundWithoutSnowGroup->setSign($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(0, $value);
    }

    public function testSuccessSetState()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Moderate or thick cover of loose dry dust or sand covering ground completely');

        $this->groundWithoutSnowGroup->setState($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals('Moderate or thick cover of loose dry dust or sand covering ground completely', $value);
    }

    public function testNullSetState()
    {
        $this->groundWithoutSnowGroup->setState(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetState()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Moderate or thick cover of loose dry dust or sand covering ground completely');

        $this->groundWithoutSnowGroup->setState($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals('Surface of ground frozen', $value);
    }

    public function testSuccessSetCodeState()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(4);

        $this->groundWithoutSnowGroup->setCodeState($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('codeState');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(4, $value);
    }

    public function testNullSetCodeState()
    {
        $this->groundWithoutSnowGroup->setCodeState(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('codeState');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetCodeState()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(4);

        $this->groundWithoutSnowGroup->setCodeState($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('codeState');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(5, $value);
    }

    public function testSuccessIsDrWtSnGroup()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->groundWithoutSnowGroup->isDrWtSnGroup($decoder, $validate));
    }

    public function testErrorIsDrWtSnGroup()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->groundWithoutSnowGroup->isDrWtSnGroup($decoder, $validate));
    }

    public function testSuccessSetGroundWithoutSnowGroup()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(4);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Moderate or thick cover of loose dry dust or sand covering ground completely');
        $decoder->shouldReceive('getGroundSignTemperature')->once()->andReturn(0);
        $decoder->shouldReceive('getGroundMinTemperature')->once()->andReturn(11);

        $validate = Mockery::mock(Validate::class);

        $this->groundWithoutSnowGroup->setGroundWithoutSnowGroup($decoder, $validate);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $propertyCodeState = $reflector->getProperty('codeState');
        $propertyCodeState->setAccessible(true);
        $valueCodeState = $propertyCodeState->getValue($this->groundWithoutSnowGroup);

        $propertyState = $reflector->getProperty('state');
        $propertyState->setAccessible(true);
        $valueState = $propertyState->getValue($this->groundWithoutSnowGroup);

        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->groundWithoutSnowGroup);

        $propertyMinTemp = $reflector->getProperty('minTemperature');
        $propertyMinTemp->setAccessible(true);
        $valueMinTemp = $propertyMinTemp->getValue($this->groundWithoutSnowGroup);

        $expected = [
            4,
            'Moderate or thick cover of loose dry dust or sand covering ground completely',
            0,
            11
        ];

        $this->assertEquals($expected, [$valueCodeState, $valueState, $valueSign, $valueMinTemp]);
    }

    public function testErrorSetGroundWithoutSnowGroup()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->groundWithoutSnowGroup->setGroundWithoutSnowGroup($decoder, $validate);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $propertyCodeState = $reflector->getProperty('codeState');
        $propertyCodeState->setAccessible(true);
        $valueCodeState = $propertyCodeState->getValue($this->groundWithoutSnowGroup);

        $propertyState = $reflector->getProperty('state');
        $propertyState->setAccessible(true);
        $valueState = $propertyState->getValue($this->groundWithoutSnowGroup);

        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->groundWithoutSnowGroup);

        $propertyMinTemp = $reflector->getProperty('minTemperature');
        $propertyMinTemp->setAccessible(true);
        $valueMinTemp = $propertyMinTemp->getValue($this->groundWithoutSnowGroup);

        $expected = [null, null, null, null];

        $this->assertEquals($expected, [$valueCodeState, $valueState, $valueSign, $valueMinTemp]);
    }

    public function testSuccessGetResultMinTemperature()
    {
        $this->assertEquals(8, $this->groundWithoutSnowGroup->getResultMinTemperature());
    }

    public function testErrorGetResultMinTemperature()
    {
        $this->assertNotEquals(5, $this->groundWithoutSnowGroup->getResultMinTemperature());
    }

    public function testSuccessGetMinTemperatureValue()
    {
        $this->assertEquals(8, $this->groundWithoutSnowGroup->getMinTemperatureValue());
    }

    public function testErrorGetMinTemperatureValue()
    {
        $this->assertNotEquals(5, $this->groundWithoutSnowGroup->getMinTemperatureValue());
    }

    public function testSuccessGetSignValue()
    {
        $this->assertEquals(0, $this->groundWithoutSnowGroup->getSignValue());
    }

    public function testErrorGetSignValue()
    {
        $this->assertNotEquals(1, $this->groundWithoutSnowGroup->getSignValue());
    }

    public function testSuccessGetStateValue()
    {
        $this->assertEquals('Surface of ground frozen', $this->groundWithoutSnowGroup->getStateValue());
    }

    public function testErrorGetStateValue()
    {
        $this->assertNotEquals('Moderate or thick cover of loose dry dust or sand covering ground completely', $this->groundWithoutSnowGroup->getStateValue());
    }

    public function testSuccessGetCodeStateValue()
    {
        $this->assertEquals(4, $this->groundWithoutSnowGroup->getCodeStateValue());
    }

    public function testErrorGetCodeStateValue()
    {
        $this->assertNotEquals(5, $this->groundWithoutSnowGroup->getCodeStateValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->groundWithoutSnowGroup->getDecoder());
    }

    public function testSuccessGetRawGroundWithoutSnow()
    {
        $this->assertEquals('34008', $this->groundWithoutSnowGroup->getRawGroundWithoutSnow());
    }

    public function testSuccessIsStringGetRawGroundWithoutSnow()
    {
        $this->assertIsString($this->groundWithoutSnowGroup->getRawGroundWithoutSnow());
    }

    public function testErrorGetRawGroundWithoutSnow()
    {
        $this->assertNotEquals('34005', $this->groundWithoutSnowGroup->getRawGroundWithoutSnow());
    }

    public function testSuccessSetResultMinTemperature()
    {
        $this->groundWithoutSnowGroup->setResultMinTemperature(-5);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(-5, $value);
    }

    public function testNullSetResultMinTemperature()
    {
        $this->groundWithoutSnowGroup->setResultMinTemperature(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetResultMinTemperature()
    {
        $this->groundWithoutSnowGroup->setResultMinTemperature(-5);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperatureValue');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(5, $value);
    }

    public function testSuccessSetMinTemperatureValue()
    {
        $this->groundWithoutSnowGroup->setMinTemperatureValue(5);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(5, $value);
    }

    public function testNullSetMinTemperatureValue()
    {
        $this->groundWithoutSnowGroup->setMinTemperatureValue(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetMinTemperatureValue()
    {
        $this->groundWithoutSnowGroup->setMinTemperatureValue(5);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('minTemperature');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(-5, $value);
    }

    public function testSuccessSetSignValue()
    {
        $this->groundWithoutSnowGroup->setSignValue(1);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(1, $value);
    }

    public function testNullSetSignValue()
    {
        $this->groundWithoutSnowGroup->setSignValue(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetSignValue()
    {
        $this->groundWithoutSnowGroup->setSignValue(1);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('sign');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals(0, $value);
    }

    public function testSuccessSetStateValue()
    {
        $this->groundWithoutSnowGroup->setStateValue('Moderate or thick cover of loose dry dust or sand covering ground completely');

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals('Moderate or thick cover of loose dry dust or sand covering ground completely', $value);
    }

    public function testNullSetStateValue()
    {
        $this->groundWithoutSnowGroup->setStateValue(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetStateValue()
    {
        $this->groundWithoutSnowGroup->setStateValue('Surface of ground frozen');

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('state');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNotEquals('Moderate or thick cover of loose dry dust or sand covering ground completely', $value);
    }

    public function testSuccessSetCodeStateValue()
    {
        $this->groundWithoutSnowGroup->setCodeStateValue(4);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('codeState');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals(4, $value);
    }

    public function testNullSetCodeStateValue()
    {
        $this->groundWithoutSnowGroup->setCodeStateValue(null);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('codeState');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $this->groundWithoutSnowGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetRawGroundWithoutSnow()
    {
        $this->groundWithoutSnowGroup->setRawGroundWithoutSnow('34008');

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('rawGroundWithoutSnow');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertEquals('34008', $value);
    }

    public function testSuccessIsStringSetRawGroundWithoutSnow()
    {
        $this->groundWithoutSnowGroup->setRawGroundWithoutSnow('34008');

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $property = $reflector->getProperty('rawGroundWithoutSnow');
        $property->setAccessible(true);
        $value = $property->getValue($this->groundWithoutSnowGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);
        $this->groundWithoutSnowGroup->setData('34008', $validate);

        $reflector = new \ReflectionClass(GroundWithoutSnowGroup::class);
        $propertyRawData = $reflector->getProperty('rawGroundWithoutSnow');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->groundWithoutSnowGroup);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->groundWithoutSnowGroup);

        $propertyCodeState = $reflector->getProperty('codeState');
        $propertyCodeState->setAccessible(true);
        $valueCodeState = $propertyCodeState->getValue($this->groundWithoutSnowGroup);

        $propertyState = $reflector->getProperty('state');
        $propertyState->setAccessible(true);
        $valueState = $propertyState->getValue($this->groundWithoutSnowGroup);

        $propertySign = $reflector->getProperty('sign');
        $propertySign->setAccessible(true);
        $valueSign = $propertySign->getValue($this->groundWithoutSnowGroup);

        $propertyMinTemp = $reflector->getProperty('minTemperature');
        $propertyMinTemp->setAccessible(true);
        $valueMinTemp = $propertyMinTemp->getValue($this->groundWithoutSnowGroup);

        $expected = [
            '34008',
            true,
            4,
            'Surface of ground frozen',
            0,
            8
        ];

        $this->assertEquals(
            $expected,
            [
                $valueRawData, $valueDecoder instanceof GroupDecoderInterface, $valueCodeState, $valueState, $valueSign,
                $valueMinTemp
            ]
        );
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->groundWithoutSnowGroup->setData('', $validate);
    }
}
