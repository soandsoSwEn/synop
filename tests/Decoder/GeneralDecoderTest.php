<?php

namespace Soandso\Synop\Tests\Decoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GeneralDecoder;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\SectionInterface;

class GeneralDecoderTest extends TestCase
{
    private $generalDecoder;

    protected function setUp(): void
    {
        $this->generalDecoder = new GeneralDecoder(new Section('General Section'), new Unit());
    }

    protected function tearDown(): void
    {
        unset($this->generalDecoder);
        Mockery::close();
    }

    public function testSuccessGetSectionByTitle()
    {
        $reflector = new \ReflectionClass(GeneralDecoder::class);
        $method = $reflector->getMethod('getSectionByTitle');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->generalDecoder, ['General Section']);

        $this->assertInstanceOf(SectionInterface::class, $result);
    }

    public function testSuccessTitleGetSectionByTitle()
    {
        $reflector = new \ReflectionClass(GeneralDecoder::class);
        $method = $reflector->getMethod('getSectionByTitle');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->generalDecoder, ['General Section']);

        $this->assertEquals('General Section', $result->getTitle());
    }

    public function testSuccessGetType()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->getType($rawReport, $validate));
    }

    public function testExceptionGetType()
    {
        $report = 'ABCD 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->expectException(\Exception::class);

        $this->generalDecoder->getType($rawReport, $validate);
    }

    public function testSuccessIsGroup()
    {
        $this->assertTrue($this->generalDecoder->isGroup('07181', 5));
    }

    public function testErrorIsGroup()
    {
        $this->assertFalse($this->generalDecoder->isGroup('AAXX', 5));
    }

    public function testSuccessParse()
    {
        $reflector = new \ReflectionClass(GeneralDecoder::class);
        $method = $reflector->getMethod('parse');
        $method->setAccessible(true);
        $result = $method->invoke($this->generalDecoder);

        $this->assertInstanceOf(SectionInterface::class, $result);
    }

    public function testSuccessPutSection()
    {
        $reflector = new \ReflectionClass(GeneralDecoder::class);
        $method = $reflector->getMethod('putSection');
        $method->setAccessible(true);

        $section = Mockery::mock(Section::class);

        $result = $method->invokeArgs($this->generalDecoder, [$section]);

        $this->assertTrue($result);
    }

    public function testSuccessPutInSection()
    {
        $data = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $reflector = new \ReflectionClass(GeneralDecoder::class);
        $method = $reflector->getMethod('putInSection');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->generalDecoder, [$data]);

        $this->assertTrue($result);
    }

    public function testSuccessFoSynopGetShipSign()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->andReturn($report);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $this->assertFalse($this->generalDecoder->getShipSign($rawReport));
    }

    public function testExceptionShipGetShipSign()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->andReturn($report);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $this->expectException(\Exception::class);

        $this->generalDecoder->getShipSign($rawReport);
    }

    public function testSuccessGetYYGGiw()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->getYYGGiw($rawReport, $validate));
    }

    public function testSuccessGetIIiii()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->getIIiii($rawReport, $validate));
    }

    public function testSuccessGet99LaLaLa()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $report = '33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertFalse($this->generalDecoder->get99LaLaLa($rawReport));
    }

    public function testSuccessGetQcL0L0L0L0()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $report = '33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $this->assertFalse($this->generalDecoder->getQcL0L0L0L0($rawReport));
    }

    public function testSuccessGetirixhVV()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->getirixhVV($rawReport, $validate));
    }

    public function testSuccessGetNddff()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->getNddff($rawReport, $validate));
    }

    public function testSuccessGet1SnTTT()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get1SnTTT($rawReport, $validate));
    }

    public function testNullGet1SnTTT()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '1003 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get1SnTTT($rawReport, $validate));
    }

    public function testErrorGet1SnTTT()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get1SnTTT($rawReport, $validate));
    }

    public function testSuccessGet2SnTdTdTd()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get2SnTdTdTd($rawReport, $validate));
    }

    public function testNullGet2SnTdTdTd()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '2100 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get2SnTdTdTd($rawReport, $validate));
    }

    public function testErrorGet2SnTdTdTd()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get2SnTdTdTd($rawReport, $validate));
    }

    public function testSuccessGet3P0P0P0P0()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get3P0P0P0P0($rawReport, $validate));
    }

    public function testNullGet3P0P0P0P0()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '3004 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get3P0P0P0P0($rawReport, $validate));
    }

    public function testErrorGet3P0P0P0P0()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get3P0P0P0P0($rawReport, $validate));
    }

    public function testSuccessGet4PPPP()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get4PPPP($rawReport, $validate));
    }

    public function testNullGet4PPPP()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '4010 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get4PPPP($rawReport, $validate));
    }

    public function testErrorGet4PPPP()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '52035 60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get4PPPP($rawReport, $validate));
    }

    public function testSuccessGet5appp()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get5appp($rawReport, $validate));
    }

    public function testNullGet5appp()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '5203 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get5appp($rawReport, $validate));
    }

    public function testErrorGet5appp()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '60012 70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get5appp($rawReport, $validate));
    }

    public function testSuccessGet6RRRtr()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testNullGet6RRRtr()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '6001 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testErrorGet6RRRtr()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '52035 60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '70282 8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testSuccessGet7wwW1W2()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get7wwW1W2($rawReport, $validate));
    }

    public function testNullGet7wwW1W2()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '7028 8255/ 333 10091 555 1/004=';
        $updatedReport = '8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get7wwW1W2($rawReport, $validate));
    }

    public function testErrorGet7wwW1W2()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '60012 70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '8255/ 333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get7wwW1W2($rawReport, $validate));
    }

    public function testSuccessGet8NhClCmCh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '8255/ 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get8NhClCmCh($rawReport, $validate));
    }

    public function testNullGet8NhClCmCh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '8255 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get8NhClCmCh($rawReport, $validate));
    }

    public function testErrorGet8NhClCmCh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '70282 8255/ 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get8NhClCmCh($rawReport, $validate));
    }

    public function testSuccessGet9hh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '9//// 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get9hh($rawReport, $validate));
    }

    public function testNullGet9hh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '9/// 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get9hh($rawReport, $validate));
    }

    public function testErrorGet9hh()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report = '8255/ 9//// 333 10091 555 1/004=';
        $updatedReport = '333 10091 555 1/004=';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->generalDecoder->get9hh($rawReport, $validate));
    }

    public function testSuccessGet222DsVs()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $report = '222// 03012 1///// 2//// 3//// 4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report2 = '03012 1///// 2//// 3//// 4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report3 = '1///// 2//// 3//// 4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report4 = '2//// 3//// 4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report5 = '3//// 4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report6 = '4//// 5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report7 = '5//// 6//// ICE 42994 333 10091 555 1/004=';
        $report8 = '6//// ICE 42994 333 10091 555 1/004=';
        $report9 = 'ICE 42994 333 10091 555 1/004=';
        $report10 = '42994 333 10091 555 1/004=';

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report2);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report3);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report4);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report5);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report6);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report7);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report8);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report9);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report10);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get222DsVs($rawReport, $validate));
    }

    public function testSuccessGetTwoPipes()
    {
        $expected = [
            '222DsVs',
            '0SnTwTwTw',
            '1PwaPwaHwaHwa',
            '2PwPwHwHw',
            '3dw1dw1dw2dw2',
            '4Pw1Pw1Hw1Hw1',
            '5Pw2Pw2Hw2Hw2',
            '6IsEsEsPs',
            'ISE'
        ];

        $this->assertEquals($expected, $this->generalDecoder->getTwoPipes());
    }

    public function testErrorGetTwoPipes()
    {
        $expected = [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];

        $this->assertNotEquals($expected, $this->generalDecoder->getTwoPipes());
    }

    public function testSuccessGet333()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report1 = '333 10179 21021 32103 45997 55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report2 = '10179 21021 32103 45997 55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report3 = '21021 32103 45997 55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report4 = '32103 45997 55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report5 = '45997 55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report6 = '55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report7 = '60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report8 = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report9 = '91124 444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report10 = '444 92952 555 10178 3/021 41022 60021 77182 92952=';

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report1);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report2);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report3);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report4);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report5);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report6);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report7);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report8);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report9);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report10);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get333($rawReport, $validate));
    }

    public function testSuccessGetThreePipes()
    {
        $expected = [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '3ESnTgTg',
            '4Esss',
            '55SSS',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];

        $this->assertEquals($expected, $this->generalDecoder->getThreePipes());
    }

    public function testErrorGetThreePipes()
    {
        $expected = [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];

        $this->assertNotEquals($expected, $this->generalDecoder->getThreePipes());
    }

    public function testSuccessGet444()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report1 = '444 92952 555 10178 3/021 41022 60021 77182 92952=';
        $report2 = '92952 555 10178 3/021 41022 60021 77182 92952=';
        $report3 = '555 10178 3/021 41022 60021 77182 92952=';

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report1);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report2);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report3);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get444($rawReport, $validate));
    }

    public function testSuccessGetFourPipes()
    {
        $expected = [
            'NCHHCt'
        ];

        $this->assertEquals($expected, $this->generalDecoder->getFourPipes());
    }

    public function testErrorGetFourPipes()
    {
        $expected = [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];

        $this->assertNotEquals($expected, $this->generalDecoder->getFourPipes());
    }

    public function testSuccessGet555()
    {
        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'synop_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, true);

        $reflectionSynop = new \ReflectionProperty(GeneralDecoder::class, 'ship_report');
        $reflectionSynop->setAccessible(true);
        $reflectionSynop->setValue($this->generalDecoder, false);

        $report1 = '555 10178 3/021 41022 60021 77182 92952=';
        $report2 = '10178 3/021 41022 60021 77182 92952=';
        $report3 = '3/021 41022 60021 77182 92952=';
        $report4 = '41022 60021 77182 92952=';
        $report5 = '60021 77182 92952=';
        $report6 = '77182 92952=';
        $report7 = '92952=';
        $report8 = '';

        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report1);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report2);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report3);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report4);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report5);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report6);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report7);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report8);
        $rawReport->shouldReceive('updateReport');

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->generalDecoder->get555($rawReport, $validate));
    }

    public function testSuccessGetFivePipes()
    {
        $expected = [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];

        $this->assertEquals($expected, $this->generalDecoder->getFivePipes());
    }

    public function testErrorGetFivePipes()
    {
        $expected = [
            'NCHHCt'
        ];

        $this->assertNotEquals($expected, $this->generalDecoder->getFivePipes());
    }
}