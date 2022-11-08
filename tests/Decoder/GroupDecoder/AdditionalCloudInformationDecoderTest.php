<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Soandso\Synop\Fabrication\Validate;

class AdditionalCloudInformationDecoderTest extends TestCase
{
    private $additionalCloudInformationDecoder;

    protected function setUp(): void
    {
        $this->additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('88518');
    }

    protected function tearDown(): void
    {
        unset($this->additionalCloudInformationDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals('8', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals('8', $result);
    }

    public function testSuccessGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals('8', $result);
    }

    public function testSuccessIsStringGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureAmount()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals('8', $result);
    }

    public function testSuccessGetCodeFigureForm()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureForm');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals('5', $result);
    }

    public function testSuccessIsStringGetCodeFigureForm()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureForm');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureForm()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureForm');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals('5', $result);
    }

    public function testSuccessGetCodeFigureHeight()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureHeight');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals('18', $result);
    }

    public function testSuccessIsStringGetCodeFigureHeight()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureHeight');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertIsString( $result);
    }

    public function testErrorGetCodeFigureHeight()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureHeight');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals('18', $result);
    }

    public function testSuccessGet8189Height()
    {
        $this->assertEquals(15000, $this->additionalCloudInformationDecoder->get8189Height(84));
    }

    public function testSuccess89Get8189Height()
    {
        $this->assertEquals('> 21000', $this->additionalCloudInformationDecoder->get8189Height(89));
    }

    public function testErrorGet8189Height()
    {
        $this->assertNotEquals(15000, $this->additionalCloudInformationDecoder->get8189Height(82));
    }

    public function testSuccessGet5080Height()
    {
        $this->assertEquals(3000, $this->additionalCloudInformationDecoder->get5080Height(60));
    }

    public function testErrorGet5080Height()
    {
        $this->assertNotEquals(3000, $this->additionalCloudInformationDecoder->get5080Height(65));
    }

    public function testSuccessGet0050Height()
    {
        $this->assertEquals(540, $this->additionalCloudInformationDecoder->get0050Height(18));
    }

    public function testSuccess30Get0050Height()
    {
        $this->assertEquals('< 30', $this->additionalCloudInformationDecoder->get0050Height(0));
    }

    public function testErrorGet0050Height()
    {
        $this->assertNotEquals('< 30', $this->additionalCloudInformationDecoder->get0050Height(18));
    }

    public function testSuccessGetHeightCloud()
    {
        $this->assertEquals(['Height' => 540], $this->additionalCloudInformationDecoder->getHeightCloud());
    }

    public function testSuccess90GetHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('88592');
        $this->assertEquals(['Height' => '100-200'], $additionalCloudInformationDecoder->getHeightCloud());
    }

    public function testSuccessNullGetHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNull($additionalCloudInformationDecoder->getHeightCloud());
    }

    public function testExceptionGetHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('88551');
        $this->expectException(\Exception::class);

        $additionalCloudInformationDecoder->getHeightCloud();
    }

    public function testErrorGetHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNotEquals(570, $additionalCloudInformationDecoder->getHeightCloud());
    }

    public function testSuccessGetCodeHeightCloud()
    {
        $this->assertEquals('18', $this->additionalCloudInformationDecoder->getCodeHeightCloud());
    }

    public function testSuccessIsStringGetCodeHeightCloud()
    {
        $this->assertIsString($this->additionalCloudInformationDecoder->getCodeHeightCloud());
    }

    public function testSuccessIsNullGetCodeHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNull($additionalCloudInformationDecoder->getCodeHeightCloud());
    }

    public function testErrorGetCodeHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNotEquals('18', $additionalCloudInformationDecoder->getCodeHeightCloud());
    }

    public function testSuccessGetFormCloud()
    {
        $this->assertEquals('Nimbostratus (Ns)', $this->additionalCloudInformationDecoder->getFormCloud());
    }

    public function testSuccessStringGetFormCloud()
    {
        $this->assertIsString($this->additionalCloudInformationDecoder->getFormCloud());
    }

    public function testSuccessExceptionStringGetFormCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8/\//');
        $this->expectException(\Exception::class);

        $additionalCloudInformationDecoder->getFormCloud();
    }

    public function testErrorGetFormCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('88418');
        $this->assertNotEquals('Nimbostratus (Ns)', $additionalCloudInformationDecoder->getFormCloud());
    }

    public function testSuccessGetAmountCloud()
    {
        $this->assertEquals('Sky completely covered', $this->additionalCloudInformationDecoder->getAmountCloud());
    }

    public function testSuccessIsStringGetAmountCloud()
    {
        $this->assertIsString($this->additionalCloudInformationDecoder->getAmountCloud());
    }

    public function testNullGetAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNull($additionalCloudInformationDecoder->getAmountCloud());
    }

    public function testErrorGetAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('85518');
        $this->assertNotEquals('Sky completely covered', $additionalCloudInformationDecoder->getAmountCloud());
    }

    public function testSuccessGetCodeAmountCloud()
    {
        $this->assertEquals('8', $this->additionalCloudInformationDecoder->getCodeAmountCloud());
    }

    public function testSuccessIsStringGetCodeAmountCloud()
    {
        $this->assertIsString($this->additionalCloudInformationDecoder->getCodeAmountCloud());
    }

    public function testNullGetCodeAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNull($additionalCloudInformationDecoder->getCodeAmountCloud());
    }

    public function testExceptionGetCodeAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8\///');
        $this->expectException(\Exception::class);

        $additionalCloudInformationDecoder->getCodeAmountCloud();
    }

    public function testSuccessIsGroup()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->additionalCloudInformationDecoder->isGroup($validator));
    }

    public function testErrorIsGroup()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('AAXX');
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($additionalCloudInformationDecoder->isGroup($validator));
    }

    public function testSuccessGetIndicatorGroup()
    {
        $expected = ['8' => 'Indicator'];

        $this->assertEquals($expected, $this->additionalCloudInformationDecoder->getIndicatorGroup());
    }

    public function testSuccessIsArrayGetIndicatorGroup()
    {
        $this->assertIsArray($this->additionalCloudInformationDecoder->getIndicatorGroup());
    }

    public function testSuccessGetAmountCloudLayerIndicator()
    {
        $expected = ['Ns' => 'Amount of individual cloud layer'];

        $this->assertEquals($expected, $this->additionalCloudInformationDecoder->getAmountCloudLayerIndicator());
    }

    public function testSuccessIsArrayGetAmountCloudLayerIndicator()
    {
        $this->assertIsArray($this->additionalCloudInformationDecoder->getAmountCloudLayerIndicator());
    }

    public function testSuccessGetFormCloudIndicator()
    {
        $expected = ['C' => 'Form of cloud'];

        $this->assertEquals($expected, $this->additionalCloudInformationDecoder->getFormCloudIndicator());
    }

    public function testSuccessIsArrayGetFormCloudIndicator()
    {
        $this->assertIsArray($this->additionalCloudInformationDecoder->getFormCloudIndicator());
    }

    public function testSuccessGetHeightCloudIndicator()
    {
        $expected = ['hshs' => 'Height of base cloud layer'];

        $this->assertEquals($expected, $this->additionalCloudInformationDecoder->getHeightCloudIndicator());
    }

    public function testSuccessIsArrayGetHeightCloudIndicator()
    {
        $this->assertIsArray($this->additionalCloudInformationDecoder->getHeightCloudIndicator());
    }
}
