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

        $this->assertEquals($result, '7');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals($result, '7');
    }

    public function testSuccessGetCodeFigurePresentWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePresentWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertEquals($result, '02');
    }

    public function testErrorGetCodeFigurePresentWeather()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePresentWeather');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals($result, '02');
    }

    public function testSuccessGetCodeFigurePastWeather()
    {
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePastWeather');
        $method->setAccessible(true);
        $result = $method->invoke($this->presentWeather);

        $this->assertEquals($result, '82');
    }

    public function testErrorGetCodeFigurePastWeather()
    {
        $presentWeather = new PresentWeatherDecoder('8255/');
        $reflector = new \ReflectionClass(PresentWeatherDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePastWeather');
        $method->setAccessible(true);
        $result = $method->invoke($presentWeather);

        $this->assertNotEquals($result, '82');
    }

    public function testSuccessGetPastWeather()
    {
        $actual = [
            'W1' => 'Shower(s)',
            'W2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period',
        ];

        $this->assertEquals($this->presentWeather->getPastWeather(), $actual);
    }

    public function testErrorGetPastWeather()
    {
        $actual = [
            'W1' => 'Shower(s)',
            'W2' => 'Fog or ice fog or thick haze (visibility less than 1,000 m)',
        ];

        $this->assertNotEquals($this->presentWeather->getPastWeather(), $actual);
    }

    public function testSuccessGetPastWeatherSymbol()
    {
        $this->assertEquals($this->presentWeather->getPastWeatherSymbol(), '82');
    }

    public function testErrorGetPastWeatherSymbol()
    {
        $this->assertNotEquals($this->presentWeather->getPastWeatherSymbol(), '85');
    }

    public function testSuccessGetPresentWeather()
    {
        $this->assertEquals($this->presentWeather->getPresentWeather(), 'State of sky on the whole unchanged');
    }

    public function testErrorGetPresentWeather()
    {
        $this->assertNotEquals($this->presentWeather->getPresentWeather(), 'recipitation within sight, not reaching the ground or the surface of the sea');
    }

    public function testSuccesGetPresentWeatherSymbol()
    {
        $this->assertEquals($this->presentWeather->getPresentWeatherSymbol(), '02');
    }

    public function testErrorGetPresentWeatherSymbol()
    {
        $this->assertNotEquals($this->presentWeather->getPresentWeatherSymbol(), '05');
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