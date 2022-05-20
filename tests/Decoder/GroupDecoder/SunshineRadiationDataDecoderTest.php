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

        $this->assertEquals($result, '55');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(SunshineRadiationDataDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->sunshineRadiation);

        $this->assertNotEquals($result, '58');
    }

    public function testSuccessGetCodeSunshineData()
    {
        $this->assertEquals($this->sunshineRadiation->getCodeSunshineData(), '118');
    }

    public function testErrorGetCodeSunshineData()
    {
        $this->assertNotEquals($this->sunshineRadiation->getCodeSunshineData(), '115');
    }

    public function testSuccessGetSunshineData()
    {
        $this->assertEquals($this->sunshineRadiation->getSunshineData(), 11.8);
    }

    public function testErrorGetSunshineData()
    {
        $this->assertNotEquals($this->sunshineRadiation->getSunshineData(), 11.5);
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
}
