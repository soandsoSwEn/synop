<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Soandso\Synop\Fabrication\Validate;

class GroundWithoutSnowDecoderTest extends TestCase
{
    private $groundWithoutSnowDecoder;

    protected function setUp(): void
    {
        $this->groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('34008');
    }

    protected function tearDown(): void
    {
        unset($this->groundWithoutSnowDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals($result, '3');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49020');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals($result, '3');
    }

    public function testSuccessGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals($result, '4');
    }

    public function testErrorGetCodeFigureStateGround()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49020');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals($result, '4');
    }

    public function testSuccessGetCodeFigureSignTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureSignTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals($result, '0');
    }

    public function testErrorGetCodeFigureSignTemperature()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49120');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureSignTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals($result, '0');
    }

    public function testSuccessGetCodeFigureMinTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureMinTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals($result, '08');
    }

    public function testErrorGetCodeFigureMinTemperature()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49120');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureMinTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals($result, '08');
    }

    public function testSuccessGetCodeGroundState()
    {
        $this->assertEquals($this->groundWithoutSnowDecoder->getCodeGroundState(), 4);
    }

    public function testErrorGetCodeGroundState()
    {
        $this->assertNotEquals($this->groundWithoutSnowDecoder->getCodeGroundState(), 8);
    }

    public function testExceptionGetCodeGroundState()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('AAXX');
        $this->expectException(Exception::class);

        $groundWithoutSnowDecoder->getCodeGroundState();
    }

    public function testSuccessGetGroundState()
    {
        $this->assertEquals($this->groundWithoutSnowDecoder->getGroundState(), 'Surface of ground frozen');
    }

    public function testErrorGetGroundState()
    {
        $this->assertNotEquals($this->groundWithoutSnowDecoder->getGroundState(), 'Moderate or thick cover of loose dry dust or sand covering ground completely');
    }

    public function testExceptionGetGroundState()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('AAXX');
        $this->expectException(Exception::class);

        $groundWithoutSnowDecoder->getGroundState();
    }

    public function testSuccessGetGroundSignTemperature()
    {
        $this->assertEquals($this->groundWithoutSnowDecoder->getGroundSignTemperature(), '0');
    }

    public function testErrorGetGroundSignTemperature()
    {
        $this->assertNotEquals($this->groundWithoutSnowDecoder->getGroundSignTemperature(), '1');
    }

    public function testSuccessGetGroundMinTemperature()
    {
        $this->assertEquals($this->groundWithoutSnowDecoder->getGroundMinTemperature(), 8);
    }

    public function testErrorGetGroundMinTemperature()
    {
        $this->assertNotEquals($this->groundWithoutSnowDecoder->getGroundMinTemperature(), 15);
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->groundWithoutSnowDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49120');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($groundWithoutSnowDecoder->isGroup($validate));
    }
}
