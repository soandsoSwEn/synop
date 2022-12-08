<?php

namespace Soandso\Synop\Tests\Fabrication;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\ValidateBase;

class ValidateBaseTest extends TestCase
{
    private $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

    private $validateBase;

    protected function setUp(): void
    {
        $this->validateBase = new ValidateBase();
    }

    protected function tearDown(): void
    {
        unset($this->validateBase);
    }

    public function testSuccessIsSpecificGroup()
    {
        $this->assertTrue($this->validateBase->isSpecificGroup('AAXX'));
    }

    public function testErrorIsSpecificGroup()
    {
        $this->assertFalse($this->validateBase->isSpecificGroup('07181'));
    }

    public function testSuccessIsGroup()
    {
        $this->assertTrue($this->validateBase->isGroup('07181'));
    }

    public function testSuccessSpecificIsGroup()
    {
        $this->assertTrue($this->validateBase->isGroup('AAXX'));
    }

    public function testErrorIsGroup()
    {
        $this->assertFalse($this->validateBase->isGroup('AACC'));
    }

    public function testSuccessIsCountSymbol()
    {
        $this->assertTrue($this->validateBase->isCountSymbol($this->report));
    }

    public function testErrorIsCountSymbol()
    {
        $report = 'AAXX 071815 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';
        $this->assertFalse($this->validateBase->isCountSymbol($report));
    }

    public function testSuccessIsEndEqualSign()
    {
        $this->assertTrue($this->validateBase->isEndEqualSign($this->report));
    }

    public function testErrorIsEndEqualSign()
    {
        $report = 'AAXX 071815 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';
        $this->assertFalse($this->validateBase->isEndEqualSign($report));
    }

    public function testSuccessClearDoubleSpacing()
    {
        $report = 'AAXX 07181 33837  11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555   1/004=';
        $this->assertEquals($this->report, $this->validateBase->clearDoubleSpacing($report));
    }

    public function testSuccessIsNil()
    {
        $this->assertTrue($this->validateBase->isNil('AAXX 24124 40272 NIL='));
    }

    public function testSuccessNotIsNil()
    {
        $this->assertFalse($this->validateBase->isNil($this->report));
    }
}
