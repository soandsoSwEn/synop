<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Soandso\Synop\Fabrication\Validate;

class CloudPresentDecoderTest extends TestCase
{
    private $cloudPresentDecoder;

    protected function setUp(): void
    {
        $this->cloudPresentDecoder = new CloudPresentDecoder('8255/');
    }

    protected function tearDown(): void
    {
        unset($this->cloudPresentDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigure()
    {
        $reflector = new \ReflectionClass(CloudPresentDecoder::class);
        $method = $reflector->getMethod('getCodeFigure');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudPresentDecoder);

        $this->assertEquals($result, '8');
    }

    public function testErrorGetCodeFigure()
    {
        $reflector = new \ReflectionClass(CloudPresentDecoder::class);
        $method = $reflector->getMethod('getCodeFigure');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudPresentDecoder);

        $this->assertNotEquals($result, 'A');
    }

    public function testSuccessGetAmountLowCloudSymbol()
    {
        $this->assertEquals($this->cloudPresentDecoder->getAmountLowCloudSymbol(), '2');
    }

    public function testErrorGetAmountLowCloudSymbol()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getAmountLowCloudSymbol(), '/');
    }

    public function testSuccessGetAmountLowCloud()
    {
        $this->assertEquals($this->cloudPresentDecoder->getAmountLowCloud(), '2 eight of sky covered');
    }

    public function testErrorGetAmountLowCloud()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getAmountLowCloud(), '1 eight of sky covered, or less, but not zero');
    }

    public function testExceptionGetAmountLowCloud()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $this->expectException(\Exception::class);

        $cloudPresentDecoder->getAmountLowCloud();
    }

    public function testSuccessGetFormLowCloudSymbol()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormLowCloudSymbol(), '5');
    }

    public function testErrorGetFormLowCloudSymbol()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormLowCloudSymbol(), '/');
    }

    public function testSuccessGetFormLowCloud()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormLowCloud(), 'Stratocumulus not resulting from the spreading out of Cumulus');
    }

    public function testErrorGetFormLowCloud()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormLowCloud(), 'Cumulus moderate or string vertical extent, generally with protuberances in the form of domes or towers either accompanied or not by other Cumulus or by Stratocumulus, all having their bases at the same level');
    }

    public function testExceptionGetFormLowCloud()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $this->expectException(\Exception::class);

        $cloudPresentDecoder->getFormLowCloud();
    }

    public function testSuccessGetFormMediumCloudSymbol()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormMediumCloudSymbol(), '5');
    }

    public function testErrorGetFormMediumCloudSymbol()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormMediumCloudSymbol(), '//');
    }

    public function testSuccessGetFormMediumCloud()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormMediumCloud(), 'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole');
    }

    public function testErrorGetFormMediumCloud()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormMediumCloud(), 'Altocumulus of a chaotic sky, generally at several levels');
    }

    public function testSuccessGetFormHighCloudSymbol()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormHighCloudSymbol(), '/');
    }

    public function testErrorGetFormHighCloudSymbol()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormHighCloudSymbol(), '1');
    }

    public function testSuccessGetFormHighCloud()
    {
        $this->assertEquals($this->cloudPresentDecoder->getFormHighCloud(), 'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds');
    }

    public function testErrorGetFormHighCloud()
    {
        $this->assertNotEquals($this->cloudPresentDecoder->getFormHighCloud(), 'No Cirrus, Cirrocumulus or Cirrostartus');
    }

    public function testExceptionGetFormHighCloud()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $this->expectException(\Exception::class);

        $cloudPresentDecoder->getFormHighCloud();
    }

    public function testSuccessIsGroup()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->cloudPresentDecoder->isGroup($validator));
    }

    public function testErrorIsGroup()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($cloudPresentDecoder->isGroup($validator));
    }
}