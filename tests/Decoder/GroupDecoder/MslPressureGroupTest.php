<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Soandso\Synop\Fabrication\Validate;

class MslPressureGroupTest extends TestCase
{
    private $mlsPressure;

    protected function setUp(): void
    {
        $this->mlsPressure = new MslPressureDecoder('40101');
    }

    protected function tearDown(): void
    {
        unset($this->mlsPressure);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->mlsPressure);

        $this->assertEquals('4', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->mlsPressure);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $mlsPressure = new MslPressureDecoder('30049');
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($mlsPressure);

        $this->assertNotEquals('4', $result);
    }

    public function testSuccessGetCodeFigurePressure()
    {
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($this->mlsPressure);

        $this->assertEquals('0101', $result);
    }

    public function testSuccessIsStringGetCodeFigurePressure()
    {
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($this->mlsPressure);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigurePressure()
    {
        $mlsPressure = new MslPressureDecoder('30049');
        $reflector = new \ReflectionClass(MslPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($mlsPressure);

        $this->assertNotEquals('0101', $result);
    }

    public function testSuccessGetMslPressure()
    {
        $this->assertEquals(1010.1, $this->mlsPressure->getMslPressure());
    }

    public function testSuccessIsStringGetMslPressure()
    {
        $this->assertIsFloat($this->mlsPressure->getMslPressure());
    }

    public function testErrorGetMslPressure()
    {
        $mlsPressure = new MslPressureDecoder('30049');
        $this->assertNotEquals(1010.1, $mlsPressure->getMslPressure());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->mlsPressure->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $mlsPressure = new MslPressureDecoder('30049');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($mlsPressure->isGroup($validate));
    }
}
