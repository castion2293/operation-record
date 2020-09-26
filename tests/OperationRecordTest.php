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

    /**
     * 測試 建立一筆 操作記錄
     */
    public function testCreate()
    {
    }

    /**
     * 測試 搜尋操作紀錄
     */
    public function testFind()
    {
    }

    /**
     * 測試 移除 $date 後的 操作記錄
     */
    public function testRemoveBefore()
    {
    }

    /**
     * 移除 $date 後的 操作記
     */
    public function testRemoveAfter()
    {
    }

    /**
     * 清空操作記錄
     */
    public function testTruncate()
    {
    }
}
