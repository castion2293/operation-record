<?php

namespace Pharaoh\OperationRecord\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Pharaoh\OperationRecord\Facades\OperationRecord;
use Pharaoh\OperationRecord\Models\OperationRecord as OperationRecordModel;

class OperationRecordTest extends BaseTestCase
{
    use DatabaseMigrations;

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
        // Arrange
        $operatorId = 1;
        $subjectId = 2;
        $funcKey = 1104;
        $status = 1;
        $type = 'admin';
        $targets = '修改目標';
        $content = '修改內容';
        $ip = '127.0.0.1';

        // Act
        $result = OperationRecord::create($operatorId, $subjectId, $funcKey, $status, $type, $targets, $content, $ip);

        // Assert
        $code = Arr::get($result, 'code');
        $data = Arr::get($result, 'data');

        $this->assertEquals('200', $code);
        $this->assertTrue($data);

        $this->assertDatabaseHas(
            'operation_records',
            [
                'operator_id' => $operatorId,
                'func_key' => $funcKey,
                'subject_id' => $subjectId,
                'type' => $type,
                'status' => $status,
                'targets' => $targets,
                'content' => $content,
                'ip' => $ip
            ]
        );
    }

    /**
     * 測試 搜尋操作紀錄
     */
    public function testFind()
    {
        // Arrange
        $operatorId = 1;
        $funcKey = 1104;
        $status = 1;
        $type = 'admin';

        OperationRecordModel::factory()->count(20)->create(
            [
                'operator_id' => $operatorId,
                'func_key' => $funcKey,
                'type' => $type,
                'status' => $status,
            ]
        );

//        $params = [
//            'operator_id' => $operatorId,
//            'func_key' => $funcKey,
//            'status' => $status,
//            'type' => $type,
//            'begin_at' => now()->startOfDay()->toDateTimeString(),
//            'end_at' => now()->endOfDay()->toDateTimeString(),
//            'sort' => 'desc',
//            'page' => 1,
//            'per_page' => 10
//        ];
        $params = [];

        // Act
        $result = OperationRecord::find($params);

        // Assert
        $code = Arr::get($result, 'code');
        $data = Arr::get($result, 'data');

        $this->assertEquals('200', $code);
        $this->assertArrayHasKey('total', $data);
        $this->assertArrayHasKey('current_page', $data);
        $this->assertArrayHasKey('last_page', $data);
        $this->assertArrayHasKey('per_page', $data);
        $this->assertArrayHasKey('items', $data);
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