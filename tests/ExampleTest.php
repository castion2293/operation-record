<?php

namespace Pharaoh\OperationRecord\Tests;

use Pharaoh\OperationRecord\Facades\OperationRecord;

class ExampleTest extends BaseTestCase
{
    public function testBasic()
    {
        OperationRecord::create();

        $this->assertTrue(true);
    }
}
