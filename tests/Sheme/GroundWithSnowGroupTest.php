<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\GroundWithSnowGroup;

class GroundWithSnowGroupTest extends TestCase
{
    private $groundWithSnowGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $unit = Mockery::mock(Unit::class);

        $this->groundWithSnowGroup = new GroundWithSnowGroup('49998', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->groundWithSnowGroup);
        Mockery::close();
    }

    public function testSuccessSetDepthSnow()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getDepthSnow')->once()->andReturn(['value' => 'Snow cover, not continuous']);

        $this->groundWithSnowGroup->setDepthSnow($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals(['value' => 'Snow cover, not continuous'], $value);
    }

    public function testNullSetDepthSnow()
    {
        $this->groundWithSnowGroup->setDepthSnow(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetDepthSnow()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getDepthSnow')->once()->andReturn(['value' => 'Snow cover, not continuous']);

        $this->groundWithSnowGroup->setDepthSnow($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals(['value' => 'Measurement impossible or inaccurate'], $value);
    }

    public function testSuccessSetState()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Uneven layer of loose dry snow covering ground completely');

        $this->groundWithSnowGroup->setState($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals('Uneven layer of loose dry snow covering ground completely', $value);
    }

    public function testNullSetState()
    {
        $this->groundWithSnowGroup->setState(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetState()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Uneven layer of loose dry snow covering ground completely');

        $this->groundWithSnowGroup->setState($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals('Snow covering ground completely; deep drifts', $value);
    }

    public function testSuccessSetCodeState()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(8);

        $this->groundWithSnowGroup->setCodeState($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals(8, $value);
    }

    public function testSuccessIsIntSetCodeState()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(8);

        $this->groundWithSnowGroup->setCodeState($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertIsInt($value);
    }

    public function testNullSetCodeState()
    {
        $this->groundWithSnowGroup->setCodeState(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetCodeState()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(8);

        $this->groundWithSnowGroup->setCodeState($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals(9, $value);
    }

    public function testSuccessIsDrWthSnGroup()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->groundWithSnowGroup->isDrWthSnGroup($decoder, $validate));
    }

    public function testErrorIsDrWthSnGroup()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->groundWithSnowGroup->isDrWthSnGroup($decoder, $validate));
    }

    public function testSuccessSetGroundWithSnowGroup()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCodeGroundState')->once()->andReturn(8);
        $decoder->shouldReceive('getGroundState')->once()->andReturn('Uneven layer of loose dry snow covering ground completely');
        $decoder->shouldReceive('getDepthSnow')->once()->andReturn(['value' => 'Snow cover, not continuous']);

        $validate = Mockery::mock(Validate::class);

        $this->groundWithSnowGroup->setGroundWithSnowGroup($decoder, $validate);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $ropertyCrSt = $reflector->getProperty('codeState');
        $ropertyCrSt->setAccessible(true);
        $valueGrSt = $ropertyCrSt->getValue($this->groundWithSnowGroup);

        $ropertySt = $reflector->getProperty('state');
        $ropertySt->setAccessible(true);
        $valueSt = $ropertySt->getValue($this->groundWithSnowGroup);

        $ropertyDpSn = $reflector->getProperty('depthSnow');
        $ropertyDpSn->setAccessible(true);
        $valueDpSn = $ropertyDpSn->getValue($this->groundWithSnowGroup);

        $this->assertEquals(
            [8, 'Uneven layer of loose dry snow covering ground completely', ['value' => 'Snow cover, not continuous']],
            [$valueGrSt, $valueSt, $valueDpSn]
        );
    }

    public function testSNullSetGroundWithSnowGroup()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->groundWithSnowGroup->setGroundWithSnowGroup($decoder, $validate);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $ropertyCrSt = $reflector->getProperty('codeState');
        $ropertyCrSt->setAccessible(true);
        $valueGrSt = $ropertyCrSt->getValue($this->groundWithSnowGroup);

        $ropertySt = $reflector->getProperty('state');
        $ropertySt->setAccessible(true);
        $valueSt = $ropertySt->getValue($this->groundWithSnowGroup);

        $ropertyDpSn = $reflector->getProperty('depthSnow');
        $ropertyDpSn->setAccessible(true);
        $valueDpSn = $ropertyDpSn->getValue($this->groundWithSnowGroup);

        $this->assertEquals(
            [null, null, null],
            [$valueGrSt, $valueSt, $valueDpSn]
        );
    }

    public function testSuccessGetDepthSnowValue()
    {
        $this->assertEquals(['value' => 'Snow cover, not continuous'], $this->groundWithSnowGroup->getDepthSnowValue());
    }

    public function testErrorGetDepthSnowValue()
    {
        $this->assertNotEquals(['value' => 'Measurement impossible or inaccurate'], $this->groundWithSnowGroup->getDepthSnowValue());
    }

    public function testSuccessGetStateValue()
    {
        $this->assertEquals('Snow covering ground completely; deep drifts', $this->groundWithSnowGroup->getStateValue());
    }

    public function testSuccessIsIntegerGetStateValue()
    {
        $this->assertIsString($this->groundWithSnowGroup->getStateValue());
    }

    public function testErrorGetStateValue()
    {
        $this->assertNotEquals('Loose dry snow covering less than one-half of the ground', $this->groundWithSnowGroup->getStateValue());
    }

    public function testSuccessGetCodeStateValue()
    {
        $this->assertEquals(9, $this->groundWithSnowGroup->getCodeStateValue());
    }

    public function testErrorGetCodeStateValue()
    {
        $this->assertNotEquals(8, $this->groundWithSnowGroup->getCodeStateValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->groundWithSnowGroup->getDecoder());
    }

    public function testSuccessGetRawGroundWithSnow()
    {
        $this->assertEquals('49998', $this->groundWithSnowGroup->getRawGroundWithSnow());
    }

    public function testSuccessIsStringGetRawGroundWithSnow()
    {
        $this->assertIsString($this->groundWithSnowGroup->getRawGroundWithSnow());
    }

    public function testErrorGetRawGroundWithSnow()
    {
        $this->assertNotEquals('49999', $this->groundWithSnowGroup->getRawGroundWithSnow());
    }

    public function testSuccessSetDepthSnowValue()
    {
        $this->groundWithSnowGroup->setDepthSnowValue(['value' => 'Snow cover, not continuous']);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals(['value' => 'Snow cover, not continuous'], $value);
    }

    public function testSuccessIsArraySetDepthSnowValue()
    {
        $this->groundWithSnowGroup->setDepthSnowValue(['value' => 'Snow cover, not continuous']);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertIsArray($value);
    }

    public function testNullySetDepthSnowValue()
    {
        $this->groundWithSnowGroup->setDepthSnowValue(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetDepthSnowValue()
    {
        $this->groundWithSnowGroup->setDepthSnowValue(['value' => 'Snow cover, not continuous']);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('depthSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals(['value' => 'Measurement impossible or inaccurate'], $value);
    }

    public function testSuccessSetStateValue()
    {
        $this->groundWithSnowGroup->setStateValue('Uneven layer of loose dry snow covering ground completely');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals('Uneven layer of loose dry snow covering ground completely', $value);
    }

    public function testSuccessIsStringSetStateValue()
    {
        $this->groundWithSnowGroup->setStateValue('Uneven layer of loose dry snow covering ground completely');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertIsString($value);
    }

    public function testNullSetStateValue()
    {
        $this->groundWithSnowGroup->setStateValue(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testErrorSetStateValue()
    {
        $this->groundWithSnowGroup->setStateValue('Uneven layer of loose dry snow covering ground completely');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('state');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals('Snow covering ground completely; deep drifts', $value);
    }

    public function testSuccessSetCodeStateValue()
    {
        $this->groundWithSnowGroup->setCodeStateValue(8);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals(8, $value);
    }

    public function testNullSetCodeStateValue()
    {
        $this->groundWithSnowGroup->setCodeStateValue(null);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNull($value);
    }

    public function testSuErrorSetCodeStateValue()
    {
        $this->groundWithSnowGroup->setCodeStateValue(8);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('codeState');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals(9, $value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(GroundWithSnowDecoder::class);
        $this->groundWithSnowGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('decoder');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetRawGroundWithSnow()
    {
        $this->groundWithSnowGroup->setRawGroundWithSnow('49999');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('rawGroundWithSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertEquals('49999', $value);
    }

    public function testSuccessIsStringSetRawGroundWithSnow()
    {
        $this->groundWithSnowGroup->setRawGroundWithSnow('49999');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('rawGroundWithSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertIsString($value);
    }

    public function testErrorSetRawGroundWithSnow()
    {
        $this->groundWithSnowGroup->setRawGroundWithSnow('49999');

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $roperty = $reflector->getProperty('rawGroundWithSnow');
        $roperty->setAccessible(true);
        $value = $roperty->getValue($this->groundWithSnowGroup);

        $this->assertNotEquals('49998', $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);
        $this->groundWithSnowGroup->setData('49999', $validate);

        $reflector = new \ReflectionClass(GroundWithSnowGroup::class);
        $ropertyRawData = $reflector->getProperty('rawGroundWithSnow');
        $ropertyRawData->setAccessible(true);
        $valueRawData = $ropertyRawData->getValue($this->groundWithSnowGroup);

        $ropertyDec = $reflector->getProperty('decoder');
        $ropertyDec->setAccessible(true);
        $valueDec = $ropertyDec->getValue($this->groundWithSnowGroup);

        $ropertyCrSt = $reflector->getProperty('codeState');
        $ropertyCrSt->setAccessible(true);
        $valueGrSt = $ropertyCrSt->getValue($this->groundWithSnowGroup);

        $ropertySt = $reflector->getProperty('state');
        $ropertySt->setAccessible(true);
        $valueSt = $ropertySt->getValue($this->groundWithSnowGroup);

        $ropertyDpSn = $reflector->getProperty('depthSnow');
        $ropertyDpSn->setAccessible(true);
        $valueDpSn = $ropertyDpSn->getValue($this->groundWithSnowGroup);

        $this->assertEquals(
            ['49999', true, 9, 'Snow covering ground completely; deep drifts', ['value' => 'Measurement impossible or inaccurate']],
            [$valueRawData, $valueDec instanceof GroundWithSnowDecoder, $valueGrSt, $valueSt, $valueDpSn]
        );
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->groundWithSnowGroup->setData('', $validate);
    }
}
