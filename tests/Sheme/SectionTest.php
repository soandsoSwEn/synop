<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Sheme\DateGroup;
use Soandso\Synop\Sheme\GroupInterface;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\TypeGroup;

class SectionTest extends TestCase
{
    private $section;

    protected function setUp(): void
    {
        $this->section = new Section('All Section');
        $this->section->setBody(new Section('Zero Section'));
    }

    protected function tearDown(): void
    {
        unset($this->section);
        Mockery::close();
    }

    public function testSuccessSetTitle()
    {
        $this->section->setTitle('Section Zero');

        $reflector = new \ReflectionClass(Section::class);
        $property = $reflector->getProperty('title');
        $property->setAccessible(true);
        $value = $property->getValue($this->section);

        $this->assertEquals('Section Zero', $value);
    }

    public function testSuccessIsStringSetTitle()
    {
        $this->section->setTitle('Section Zero');

        $reflector = new \ReflectionClass(Section::class);
        $property = $reflector->getProperty('title');
        $property->setAccessible(true);
        $value = $property->getValue($this->section);

        $this->assertIsString($value);
    }

    public function testErrorSetTitle()
    {
        $this->section->setTitle('Section Zero');

        $reflector = new \ReflectionClass(Section::class);
        $property = $reflector->getProperty('title');
        $property->setAccessible(true);
        $value = $property->getValue($this->section);

        $this->assertNotEquals('All Section', $value);
    }

    public function testExceptionSetTitle()
    {
        $this->expectException(\Exception::class);

        $this->section->setTitle('');
    }

    public function testSuccessGetTitle()
    {
        $this->assertEquals('All Section', $this->section->getTitle());
    }

    public function testSuccessIsStringGetTitle()
    {
        $this->assertIsString($this->section->getTitle());
    }

    public function testExceptionGetTitle()
    {
        $reflectorProperty = new \ReflectionProperty(Section::class, 'title');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->section, null);

        $this->expectException(\Exception::class);

        $this->section->getTitle();
    }

    public function testSuccessSetBody()
    {
        $dateGroup = Mockery::mock(DateGroup::class);
        $this->section->setBody($dateGroup);

        $reflector = new \ReflectionClass(Section::class);
        $property = $reflector->getProperty('body');
        $property->setAccessible(true);
        $value = $property->getValue($this->section);

        $this->assertIsArray($value);
    }

    public function testSuccessGetBody()
    {
        $this->assertIsArray($this->section->getBody());
    }

    public function testSuccessGetBodyByTitle()
    {
        $this->assertInstanceOf(Section::class, $this->section->getBodyByTitle('Zero Section'));
    }
}
