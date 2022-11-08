<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Soandso\Synop\Fabrication\Validate;

class SunshineRadiationDataDecoderTest extends TestCase
{
    private $sunshineRadiation;

    protected function setUp(): void
    {
        $this->sunshineRadiation = new SunshineRadiationDataDecoder('55118');
    }

    protected function tearDown(): void
    {
        unset($this->rawSunshineRadiation);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(SunshineRadiationDataDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->sunshineRadiation);

        $this->assertEquals('55', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(SunshineRadiationDataDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->sunshineRadiation);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(SunshineRadiationDataDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->sunshineRadiation);

        $this->assertNotEquals('58', $result);
    }

    public function testSuccessGetCodeSunshineData()
    {
        $this->assertEquals('118', $this->sunshineRadiation->getCodeSunshineData());
    }

    public function testSuccessIsStringGetCodeSunshineData()
    {
        $this->assertIsString($this->sunshineRadiation->getCodeSunshineData());
    }

    public function testErrorGetCodeSunshineData()
    {
        $this->assertNotEquals('115', $this->sunshineRadiation->getCodeSunshineData());
    }

    public function testSuccessGetSunshineData()
    {
        $this->assertEquals(11.8, $this->sunshineRadiation->getSunshineData());
    }

    public function testSuccessIsFloatGetSunshineData()
    {
        $this->assertIsFloat($this->sunshineRadiation->getSunshineData());
    }

    public function testErrorGetSunshineData()
    {
        $this->assertNotEquals(11.5, $this->sunshineRadiation->getSunshineData());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->sunshineRadiation->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $sunshineRadiation = new SunshineRadiationDataDecoder('54118');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->withArgs(['Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder', ['54', '118']])->andReturn(false);

        $this->assertFalse($sunshineRadiation->isGroup($validate));
    }

    public function testSuccessGetGetIndicatorGroup()
    {
        $expected = ['55' => 'Indicator'];

        $this->assertEquals($expected, $this->sunshineRadiation->getGetIndicatorGroup());
    }

    public function testSuccessIsArrayGetGetIndicatorGroup()
    {
        $this->assertIsArray($this->sunshineRadiation->getGetIndicatorGroup());
    }

    public function testSuccessGetDurationTinderIndicator()
    {
        $expected = ['SSS' => 'Duration of daily sunshine'];

        $this->assertEquals($expected, $this->sunshineRadiation->getDurationTinderIndicator());
    }

    public function testSuccessIsArrayGetDurationTinderIndicator()
    {
        $this->assertIsArray($this->sunshineRadiation->getDurationTinderIndicator());
    }
}
