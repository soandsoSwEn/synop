<?php


namespace Synop\Tests\Decoder\GroupDecoder;


use PHPUnit\Framework\TestCase;
use Synop\Decoder\GroupDecoder\TypeDecoder;

class TypeDecodeTest extends TestCase
{
    private $synopTitle = 'AAXX';
    private $shipTitle = 'SHIP';

    public function testGetSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->synopTitle);

        $this->assertSame($this->synopTitle, $typeDecoderObject->getTypeValue());
    }

    public function testGetNotSynopTypeValue()
    {
        $typeDecoderObject = new TypeDecoder($this->shipTitle);

        $this->assertNull($typeDecoderObject->getTypeValue(), $this->synopTitle);
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
}