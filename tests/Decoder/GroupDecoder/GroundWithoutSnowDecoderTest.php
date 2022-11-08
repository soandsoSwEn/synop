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

        $this->assertEquals('3', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49020');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals('3', $result);
    }

    public function testSuccessGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals('4', $result);
    }

    public function testSuccessIsStringGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureStateGround()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49020');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals('4', $result);
    }

    public function testSuccessGetCodeFigureSignTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureSignTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals('0', $result);
    }

    public function testSuccessIsStringGetCodeFigureSignTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureSignTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureSignTemperature()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49120');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureSignTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals('0', $result);
    }

    public function testSuccessGetCodeFigureMinTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureMinTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertEquals('08', $result);
    }

    public function testSuccessIsStringGetCodeFigureMinTemperature()
    {
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureMinTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithoutSnowDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureMinTemperature()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('49120');
        $reflector = new \ReflectionClass(GroundWithoutSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureMinTemperature');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithoutSnowDecoder);

        $this->assertNotEquals('08', $result);
    }

    public function testSuccessGetCodeGroundState()
    {
        $this->assertEquals(4, $this->groundWithoutSnowDecoder->getCodeGroundState());
    }

    public function testSuccessIsStringGetCodeGroundState()
    {
        $this->assertIsInt($this->groundWithoutSnowDecoder->getCodeGroundState());
    }

    public function testErrorGetCodeGroundState()
    {
        $this->assertNotEquals(8, $this->groundWithoutSnowDecoder->getCodeGroundState());
    }

    public function testExceptionGetCodeGroundState()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('AAXX');
        $this->expectException(Exception::class);

        $groundWithoutSnowDecoder->getCodeGroundState();
    }

    public function testSuccessGetGroundState()
    {
        $this->assertEquals('Surface of ground frozen', $this->groundWithoutSnowDecoder->getGroundState());
    }

    public function testSuccessIsStringGetGroundState()
    {
        $this->assertIsString($this->groundWithoutSnowDecoder->getGroundState());
    }

    public function testErrorGetGroundState()
    {
        $this->assertNotEquals(
            'Moderate or thick cover of loose dry dust or sand covering ground completely',
            $this->groundWithoutSnowDecoder->getGroundState()
        );
    }

    public function testExceptionGetGroundState()
    {
        $groundWithoutSnowDecoder = new GroundWithoutSnowDecoder('AAXX');
        $this->expectException(Exception::class);

        $groundWithoutSnowDecoder->getGroundState();
    }

    public function testSuccessGetGroundSignTemperature()
    {
        $this->assertEquals('0', $this->groundWithoutSnowDecoder->getGroundSignTemperature());
    }

    public function testSuccessIsStringGetGroundSignTemperature()
    {
        $this->assertIsString($this->groundWithoutSnowDecoder->getGroundSignTemperature());
    }

    public function testErrorGetGroundSignTemperature()
    {
        $this->assertNotEquals('1', $this->groundWithoutSnowDecoder->getGroundSignTemperature());
    }

    public function testSuccessGetGroundMinTemperature()
    {
        $this->assertEquals(8, $this->groundWithoutSnowDecoder->getGroundMinTemperature());
    }

    public function testSuccessIsStringGetGroundMinTemperature()
    {
        $this->assertIsInt($this->groundWithoutSnowDecoder->getGroundMinTemperature());
    }

    public function testErrorGetGroundMinTemperature()
    {
        $this->assertNotEquals(15, $this->groundWithoutSnowDecoder->getGroundMinTemperature());
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

    public function testSuccessGetGetIndicatorGroup()
    {
        $expected = ['3' => 'Indicator'];

        $this->assertEquals($expected, $this->groundWithoutSnowDecoder->getGetIndicatorGroup());
    }

    public function testSuccessIsArrayGetGetIndicatorGroup()
    {
        $this->assertIsArray($this->groundWithoutSnowDecoder->getGetIndicatorGroup());
    }

    public function testSuccessGetStateGroundIndicator()
    {
        $expected = ['E' => 'State of ground without snow or measurable ice cover'];

        $this->assertEquals($expected, $this->groundWithoutSnowDecoder->getStateGroundIndicator());
    }

    public function testSuccessIsArrayGetStateGroundIndicator()
    {
        $this->assertIsArray($this->groundWithoutSnowDecoder->getStateGroundIndicator());
    }

    public function testSuccessGetSignTemperatureIndicator()
    {
        $expected = ['Sn' => 'Sign of temperature'];

        $this->assertEquals($expected, $this->groundWithoutSnowDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessIsArrayGetSignTemperatureIndicator()
    {
        $this->assertIsArray($this->groundWithoutSnowDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessGetMinimumTemperature()
    {
        $expected = ['TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'];

        $this->assertEquals($expected, $this->groundWithoutSnowDecoder->getMinimumTemperature());
    }

    public function testSuccessIsArrayGetMinimumTemperature()
    {
        $this->assertIsArray($this->groundWithoutSnowDecoder->getMinimumTemperature());
    }
}
