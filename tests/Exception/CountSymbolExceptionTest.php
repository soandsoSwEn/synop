<?php

namespace Exception;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Exception\CountSymbolException;

class CountSymbolExceptionTest extends TestCase
{
    public function testSuccessGetGroup()
    {
        $group = '041800Z';

        $countSymbolException = new CountSymbolException($group);

        $this->assertEquals($group, $countSymbolException->getGroup());
    }

    public function testSuccessIsStringGetGroup()
    {
        $group = '041800Z';

        $countSymbolException = new CountSymbolException($group);

        $this->assertIsString($countSymbolException->getGroup());
    }
}
