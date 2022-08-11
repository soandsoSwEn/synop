<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Soandso\Synop\Fabrication\Validate;

class PresentWeatherDecoderTest extends TestCase
{
    private $presentWeather;

    protected function setUp(): void
    {
        $this->presentWeather = new PresentWeatherDecoder('70282');
    }

    protected function tearDown(): void
    {
        unset($this->presentWeather);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertEquals('7', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals('7', $result);
    }

    public function testSuccessGetCodeFigurePresentWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePresentWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertEquals('02', $result);
    }

    public function testSuccessIsStringGetCodeFigurePresentWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePresentWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigurePresentWeather()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePresentWeather');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals('02', $result);
    }

    public function testSuccessGetCodeFigurePastWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePastWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertEquals('82', $result);
    }

    public function testSuccessIsStringGetCodeFigurePastWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePastWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigurePastWeather()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePastWeather');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals('82', $result);
    }

    public function testSuccessGetPastWeather()
    {
        $expected = [
            'W1' => 'Shower(s)',
            'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period',
        ];

        $this->assertEquals($expected, $this->presentWeather->getPastWeather());
    }

    public function testSuccessIsArrayGetPastWeather()
    {
        $expected = [
            'W1' => 'Shower(s)',
            'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period',
        ];

        $this->assertIsArray($this->presentWeather->getPastWeather());
    }

    public function testErrorGetPastWeather()
    {
        $expected = [
            'W1' => 'Shower(s)',
            'W2' => 'Fog or ice fog or thick haze (visibility less than 1,000 m)',
        ];

        $this->assertNotEquals($expected, $this->presentWeather->getPastWeather());
    }

    public function testSuccessGetPastWeatherSymbol()
    {
        $this->assertEquals('82', $this->presentWeather->getPastWeatherSymbol());
    }

    public function testSuccessIsStringGetPastWeatherSymbol()
    {
        $this->assertIsString($this->presentWeather->getPastWeatherSymbol());
    }

    public function testErrorGetPastWeatherSymbol()
    {
        $this->assertNotEquals('85', $this->presentWeather->getPastWeatherSymbol());
    }

    public function testSuccessGetPresentWeather()
    {
        $this->assertEquals('State of sky on the whole unchanged', $this->presentWeather->getPresentWeather());
    }

    public function testSuccessIsStringGetPresentWeather()
    {
        $this->assertIsString($this->presentWeather->getPresentWeather());
    }

    public function testErrorGetPresentWeather()
    {
        $this->assertNotEquals(
            'recipitation within sight, not reaching the ground or the surface of the sea',
            $this->presentWeather->getPresentWeather()
        );
    }

    public function testSuccesGetPresentWeatherSymbol()
    {
        $this->assertEquals('02', $this->presentWeather->getPresentWeatherSymbol());
    }

    public function testSuccesIsStringGetPresentWeatherSymbol()
    {
        $this->assertIsString($this->presentWeather->getPresentWeatherSymbol());
    }

    public function testErrorGetPresentWeatherSymbol()
    {
        $this->assertNotEquals('05', $this->presentWeather->getPresentWeatherSymbol());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->presentWeather->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertFalse($presentWeather->isGroup($validate));
    }
}
