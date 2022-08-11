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

        $this->assertEquals('8', $result);
    }

    public function testSuccessIsStringGetCodeFigure()
    {
        $reflector = new \ReflectionClass(CloudPresentDecoder::class);
        $method = $reflector->getMethod('getCodeFigure');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudPresentDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigure()
    {
        $reflector = new \ReflectionClass(CloudPresentDecoder::class);
        $method = $reflector->getMethod('getCodeFigure');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudPresentDecoder);

        $this->assertNotEquals('A', $result);
    }

    public function testSuccessGetAmountLowCloudSymbol()
    {
        $this->assertEquals('2', $this->cloudPresentDecoder->getAmountLowCloudSymbol());
    }

    public function testSuccessIsStringGetAmountLowCloudSymbol()
    {
        $this->assertIsString($this->cloudPresentDecoder->getAmountLowCloudSymbol());
    }

    public function testErrorGetAmountLowCloudSymbol()
    {
        $this->assertNotEquals('/', $this->cloudPresentDecoder->getAmountLowCloudSymbol());
    }

    public function testSuccessGetAmountLowCloud()
    {
        $this->assertEquals('2 eight of sky covered', $this->cloudPresentDecoder->getAmountLowCloud());
    }

    public function testSuccessIsStringGetAmountLowCloud()
    {
        $this->assertIsString($this->cloudPresentDecoder->getAmountLowCloud());
    }

    public function testErrorGetAmountLowCloud()
    {
        $this->assertNotEquals(
            '1 eight of sky covered, or less, but not zero',
            $this->cloudPresentDecoder->getAmountLowCloud()
        );
    }

    public function testExceptionGetAmountLowCloud()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $this->expectException(\Exception::class);

        $cloudPresentDecoder->getAmountLowCloud();
    }

    public function testSuccessGetFormLowCloudSymbol()
    {
        $this->assertEquals('5', $this->cloudPresentDecoder->getFormLowCloudSymbol());
    }

    public function testSuccessIsStringGetFormLowCloudSymbol()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormLowCloudSymbol());
    }

    public function testErrorGetFormLowCloudSymbol()
    {
        $this->assertNotEquals('/', $this->cloudPresentDecoder->getFormLowCloudSymbol());
    }

    public function testSuccessGetFormLowCloud()
    {
        $this->assertEquals(
            'Stratocumulus not resulting from the spreading out of Cumulus',
            $this->cloudPresentDecoder->getFormLowCloud()
        );
    }

    public function testSuccessIsStringGetFormLowCloud()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormLowCloud());
    }

    public function testErrorGetFormLowCloud()
    {
        $this->assertNotEquals(
            'Cumulus moderate or string vertical extent, generally with protuberances in the form of domes or towers either accompanied or not by other Cumulus or by Stratocumulus, all having their bases at the same level',
            $this->cloudPresentDecoder->getFormLowCloud()
        );
    }

    public function testExceptionGetFormLowCloud()
    {
        $cloudPresentDecoder = new CloudPresentDecoder('AAXX');
        $this->expectException(\Exception::class);

        $cloudPresentDecoder->getFormLowCloud();
    }

    public function testSuccessGetFormMediumCloudSymbol()
    {
        $this->assertEquals('5', $this->cloudPresentDecoder->getFormMediumCloudSymbol());
    }

    public function testSuccessIsStringGetFormMediumCloudSymbol()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormMediumCloudSymbol());
    }

    public function testErrorGetFormMediumCloudSymbol()
    {
        $this->assertNotEquals('//', $this->cloudPresentDecoder->getFormMediumCloudSymbol());
    }

    public function testSuccessGetFormMediumCloud()
    {
        $this->assertEquals(
            'Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole',
            $this->cloudPresentDecoder->getFormMediumCloud()
        );
    }

    public function testSuccessIsStringGetFormMediumCloud()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormMediumCloud());
    }

    public function testErrorGetFormMediumCloud()
    {
        $this->assertNotEquals(
            'Altocumulus of a chaotic sky, generally at several levels',
            $this->cloudPresentDecoder->getFormMediumCloud()
        );
    }

    public function testSuccessGetFormHighCloudSymbol()
    {
        $this->assertEquals('/', $this->cloudPresentDecoder->getFormHighCloudSymbol());
    }

    public function testSuccessIsStringGetFormHighCloudSymbol()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormHighCloudSymbol());
    }

    public function testErrorGetFormHighCloudSymbol()
    {
        $this->assertNotEquals('1', $this->cloudPresentDecoder->getFormHighCloudSymbol());
    }

    public function testSuccessGetFormHighCloud()
    {
        $this->assertEquals(
            'Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds',
            $this->cloudPresentDecoder->getFormHighCloud()
        );
    }

    public function testSuccessIsStringGetFormHighCloud()
    {
        $this->assertIsString($this->cloudPresentDecoder->getFormHighCloud());
    }

    public function testErrorGetFormHighCloud()
    {
        $this->assertNotEquals(
            'No Cirrus, Cirrocumulus or Cirrostartus',
            $this->cloudPresentDecoder->getFormHighCloud()
        );
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
