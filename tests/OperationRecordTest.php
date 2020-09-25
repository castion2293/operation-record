<?php

namespace Pharaoh\OperationRecord\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Pharaoh\OperationRecord\Facades\OperationRecord;
use Pharaoh\OperationRecord\Models\OperationRecord as OperationRecordModel;

class OperationRecordTest extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

//        $operationRecord = OperationRecordModel::factory()->create();
    }

    public function testCreate()
    {
        OperationRecord::create();
        $this->assertTrue(true);
    }
}
