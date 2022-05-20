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

        $this->assertEquals($result, '8');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals($result, '8');
    }

    public function testSuccessGetCodeFigureAmount()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals($result, '8');
    }

    public function testErrorGetCodeFigureAmount()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureAmount');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals($result, '8');
    }

    public function testSuccessGetCodeFigureForm()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureForm');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals($result, '5');
    }

    public function testErrorGetCodeFigureForm()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureForm');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals($result, '5');
    }

    public function testSuccessGetCodeFigureHeight()
    {
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureHeight');
        $method->setAccessible(true);
        $result = $method->invoke($this->additionalCloudInformationDecoder);

        $this->assertEquals($result, '18');
    }

    public function testErrorGetCodeFigureHeight()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('1/004');
        $reflector = new \ReflectionClass(AdditionalCloudInformationDecoder::class);
        $method = $reflector->getMethod('getCodeFigureHeight');
        $method->setAccessible(true);
        $result = $method->invoke($additionalCloudInformationDecoder);

        $this->assertNotEquals($result, '18');
    }

    public function testSuccessGet8189Height()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->get8189Height(84), 15000);
    }

    public function testSuccess89Get8189Height()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->get8189Height(89), '> 21000');
    }

    public function testErrorGet8189Height()
    {
        $this->assertNotEquals($this->additionalCloudInformationDecoder->get8189Height(82), 15000);
    }

    public function testSuccessGet5080Height()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->get5080Height(60), 3000);
    }

    public function testErrorGet5080Height()
    {
        $this->assertNotEquals($this->additionalCloudInformationDecoder->get5080Height(65), 3000);
    }

    public function testSuccessGet0050Height()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->get0050Height(18), 540);
    }

    public function testSuccess30Get0050Height()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->get0050Height(0), '< 30');
    }

    public function testErrorGet0050Height()
    {
        $this->assertNotEquals($this->additionalCloudInformationDecoder->get0050Height(18), '< 30');
    }

    public function testSuccessGetHeightCloud()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->getHeightCloud(), ['Height' => 540]);
    }

    public function testSuccess90GetHeightCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('88592');
        $this->assertEquals($additionalCloudInformationDecoder->getHeightCloud(), ['Height' => '100-200']);
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
        $this->assertNotEquals($additionalCloudInformationDecoder->getHeightCloud(), 570);
    }

    public function testSuccessGetCodeHeightCloud()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->getCodeHeightCloud(), '18');
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
        $this->assertNotEquals($additionalCloudInformationDecoder->getCodeHeightCloud(), '18');
    }

    public function testSuccessGetFormCloud()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->getFormCloud(), 'Nimbostratus (Ns)');
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
        $this->assertNotEquals($additionalCloudInformationDecoder->getFormCloud(), 'Nimbostratus (Ns)');
    }

    public function testSuccessGetAmountCloud()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->getAmountCloud(), 'Sky completely covered');
    }

    public function testNullGetAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('8////');
        $this->assertNull($additionalCloudInformationDecoder->getAmountCloud());
    }

    public function testErrorGetAmountCloud()
    {
        $additionalCloudInformationDecoder = new AdditionalCloudInformationDecoder('85518');
        $this->assertNotEquals($additionalCloudInformationDecoder->getAmountCloud(), 'Sky completely covered');
    }

    public function testSuccessGetCodeAmountCloud()
    {
        $this->assertEquals($this->additionalCloudInformationDecoder->getCodeAmountCloud(), '8');
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
}