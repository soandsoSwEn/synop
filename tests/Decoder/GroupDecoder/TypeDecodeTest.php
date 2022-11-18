<?php


namespace Soandso\Synop\Tests\Decoder\GroupDecoder;


use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\TypeDecoder;
use Soandso\Synop\Fabrication\Validate;

class TypeDecodeTest extends TestCase
{
    private $synopTitle = 'AAXX';
    private $shipTitle = 'SHIP';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->synopTitle);

        $this->assertSame($this->synopTitle, $typeDecoderObject->getTypeValue());
    }

    public function testGetNotSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertNull($typeDecoderObject->getTypeValue());
    }

    /** This version of the library only supports SYNOP ***/
    /*
    public function testGetShipTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertSame($this->shipTitle, $typeDecoderObject->getTypeValue());
    }*/

    public function testGetIsSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->synopTitle);

        $this->assertTrue($typeDecoderObject->getIsSynopValue());
    }

    public function testGetIsNotSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertNull($typeDecoderObject->getIsSynopValue());
    }

    /** This version of the library only supports SYNOP ***/
    /*public function testGetIsShipTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertTrue($typeDecoderObject->getIsSynopValue());
    }*/

    public function testSuccessIsGroup()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($typeDecoderObject->isGroup($validate, 'AAXX/BBXX'));
    }

    public function testErrorIsGroup()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(false);

        $this->assertFalse($typeDecoderObject->isGroup($validate, 'AAXX/BBXX'));
    }

    public function testSuccessGetTypeReportIndicator()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);
        $expected = ['AAXX/BBXX' => 'Synoptic Code Identifier'];

        $this->assertEquals($expected, $typeDecoderObject->getTypeReportIndicator());
    }

    public function testSuccessIsArrayGetTypeReportIndicator()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertIsArray($typeDecoderObject->getTypeReportIndicator());
    }
}
