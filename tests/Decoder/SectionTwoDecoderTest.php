<?php

namespace Soandso\Synop\Tests\Decoder;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\SectionTwoDecoder;
use Soandso\Synop\Sheme\Section;

class SectionTwoDecoderTest extends TestCase
{
    private $sectionTwoDecoder;

    protected function setUp(): void
    {
        $this->sectionTwoDecoder = new SectionTwoDecoder(new Section('Section Two'), false, true);
    }

    protected function tearDown(): void
    {
        unset($this->sectionTwoDecoder);
    }

    public function testIsSynopTypeReport()
    {
        $reflector = new \ReflectionClass(SectionTwoDecoder::class);
        $property = $reflector->getProperty('synopReport');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionTwoDecoder);

        $this->assertFalse($value);
    }

    public function testIsShipTypeReport()
    {
        $reflector = new \ReflectionClass(SectionTwoDecoder::class);
        $property = $reflector->getProperty('shipReport');
        $property->setAccessible(true);
        $value = $property->getValue($this->sectionTwoDecoder);

        $this->assertTrue($value);
    }
}