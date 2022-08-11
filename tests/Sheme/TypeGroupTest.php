<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\TypeDecoder;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\TypeGroup;

class TypeGroupTest extends TestCase
{
    private $typeGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->typeGroup = new TypeGroup('AAXX', $validate);
    }

    protected function tearDown(): void
    {
        unset($this->typeGroup);
        Mockery::close();
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(TypeDecoder::class);

        $this->typeGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertInstanceOf(TypeDecoder::class, $value);
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(TypeDecoder::class, $this->typeGroup->getDecoder());
    }

    public function testSuccessIsTypeGroup()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->typeGroup->isTypeGroup($decoder, $validate));
    }

    public function testSuccessIsShip()
    {
        $reflectorProperty = new \ReflectionProperty(TypeGroup::class, 'isShip');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->typeGroup, true);

        $this->assertTrue($this->typeGroup->isShip());
    }

    public function testErrorIsShip()
    {
        $this->assertFalse($this->typeGroup->isShip());
    }

    public function testExceptionIsShip()
    {
        $reflectorProperty = new \ReflectionProperty(TypeGroup::class, 'isShip');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->typeGroup, null);

        $this->expectException(\Exception::class);

        $this->typeGroup->isShip();
    }

    public function testSuccessIsSynop()
    {
        $this->assertTrue($this->typeGroup->isSynop());
    }

    public function testErrorIsSynop()
    {
        $reflectorProperty = new \ReflectionProperty(TypeGroup::class, 'isSynop');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->typeGroup, false);

        $this->assertFalse($this->typeGroup->isSynop());
    }

    public function testExceptionIsSynop()
    {
        $reflectorProperty = new \ReflectionProperty(TypeGroup::class, 'isSynop');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->typeGroup, null);

        $this->expectException(\Exception::class);

        $this->typeGroup->isSynop();
    }

    public function testSuccessGetType()
    {
        $this->assertEquals('AAXX', $this->typeGroup->getType());
    }

    public function testErrorGetType()
    {
        $this->assertNotEquals('BBXX', $this->typeGroup->getType());
    }

    public function testSuccessSetShip()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getIsShipValue')->once()->andReturn(true);

        $this->typeGroup->setShip($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('isShip');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertTrue($value);
    }

    public function testErrorSetShip()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getIsShipValue')->once()->andReturn(false);

        $this->typeGroup->setShip($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('isShip');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertFalse($value);
    }

    public function testSuccessSetSynop()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getIsSynopValue')->once()->andReturn(true);

        $this->typeGroup->setSynop($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('isSynop');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertTrue($value);
    }

    public function testErrorSetSynop()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getIsSynopValue')->once()->andReturn(false);

        $this->typeGroup->setSynop($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('isSynop');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertFalse($value);
    }

    public function testSuccessSetType()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getTypeValue')->once()->andReturn('AAXX');

        $this->typeGroup->setType($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('type');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertEquals('AAXX', $value);
    }

    public function testErrorSetType()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getTypeValue')->once()->andReturn('BBXX');

        $this->typeGroup->setType($decoder);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $property = $reflector->getProperty('type');
        $property->setAccessible(true);
        $value = $property->getValue($this->typeGroup);

        $this->assertNotEquals('AAXX', $value);
    }

    public function testSuccessSetTypeGroup()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('getTypeValue')->once()->andReturn('AAXX');
        $decoder->shouldReceive('getIsSynopValue')->once()->andReturn(true);
        $decoder->shouldReceive('getIsShipValue')->once()->andReturn(false);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);

        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(true);

        $this->typeGroup->setTypeGroup($decoder, $validator);

        $reflector = new \ReflectionClass(TypeGroup::class);
        $type = $reflector->getProperty('type');
        $type->setAccessible(true);
        $typeValue = $type->getValue($this->typeGroup);
        $synop = $reflector->getProperty('isSynop');
        $synop->setAccessible(true);
        $synopValue = $synop->getValue($this->typeGroup);
        $ship = $reflector->getProperty('isShip');
        $ship->setAccessible(true);
        $shipValue = $ship->getValue($this->typeGroup);

        $expected = [
            'AAXX', true, false
        ];

        $this->assertEquals($expected, [$typeValue, $synopValue, $shipValue]);
    }

    public function testExceptionSetTypeGroup()
    {
        $decoder = Mockery::mock(TypeDecoder::class);
        $decoder->shouldReceive('isGroup')->andReturn(false);

        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->typeGroup->setTypeGroup($decoder, $validator);
    }

    public function testSuccessSetData()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->typeGroup->setData('AAXX', $validator);
        $reflector = new \ReflectionClass(TypeGroup::class);
        $type = $reflector->getProperty('rawTypeData');
        $type->setAccessible(true);
        $typeValue = $type->getValue($this->typeGroup);

        $this->assertEquals('AAXX', $typeValue);
    }

    public function testExceptionSetData()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(false);

        $this->expectException(\Exception::class);

        $this->typeGroup->setData('BBXX', $validator);
    }
}
