<?php

namespace Pharaoh\OperationRecord\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Pharaoh\OperationRecord\Facades\OperationRecord;
use Pharaoh\OperationRecord\Jobs\OperationRecordCreateJob;
use Pharaoh\OperationRecord\Models\OperationRecord as OperationRecordModel;
use Pharaoh\OperationRecord\Tests\Models\Post;
use Pharaoh\OperationRecord\Tests\Models\User;

class OperationRecordTest extends BaseTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
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
        $action = config('operation_record.action.update');
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $ip = '127.0.0.1';

        // Act
        $result = OperationRecord::create($operatorId, User::class, $subjectId, Post::class, $funcKey, $action, $old, $new, $ip);

        // Assert
        $this->assertTrue($result);

        $this->assertDatabaseHas(
            'operation_records',
            [
                'operator_id' => $operatorId,
                'operator_type' => User::class,
                'subject_id' => $subjectId,
                'subject_type' => Post::class,
                'func_key' => $funcKey,
                'action' => $action,
                'ip' => $ip
            ]
        );
    }

    /**
     * 測試 建立一筆 操作記錄 使用 queue job 的方式
     */
    public function testDispatch()
    {
        // Arrange
        $operatorId = 1;
        $subjectId = 2;
        $funcKey = 1104;
        $action = config('operation_record.action.update');
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $ip = '127.0.0.1';

        Queue::fake();

        // Act
        OperationRecord::dispatch($operatorId, User::class, $subjectId, Post::class, $funcKey, $action, $old, $new, $ip);

        // Assert
        Queue::assertPushed(
            function (OperationRecordCreateJob $job) use ($operatorId) {
                return Arr::get($job->params, 'operator_id') === $operatorId;
            }
        );
        Queue::assertPushed(OperationRecordCreateJob::class, 1);
    }

    /**
     * 測試 搜尋操作紀錄
     */
    public function testFind()
    {
        // Arrange
        // 建立假的 user table
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('PK');
            $table->string('name');
        });

        $operatorId = 1;
        $subjectId = 2;
        $funcKey = 1104;
        $action = config('operation_record.action.update');
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $ip = '127.0.0.1';

        OperationRecordModel::factory()->count(20)->create(
            [
                'operator_id' => $operatorId,
                'operator_type' => User::class,
                'subject_id' => $subjectId,
                'subject_type' => Post::class,
                'func_key' => $funcKey,
                'action' => $action,
                'old' => $old,
                'new' => $new,
                'ip' => $ip
            ]
        );

        $params = [
            'operator' => [
                'id' => [1, 3],
                'type' => User::class,
            ],
            'subject' => [
                'id' => [2, 4],
                'type' => Post::class,
            ],
            'func_key' => [$funcKey, 1120],
            'action' => $action,
            'begin_at' => now()->startOfDay()->toDateTimeString(),
            'end_at' => now()->endOfDay()->toDateTimeString(),
            'sort' => 'desc',
            'page' => 1,
            'per_page' => 10
        ];

        // Act
        $result = OperationRecord::find($params);

        // Assert
        $data = Arr::get($result, 'data');
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
        // Arrange
        OperationRecordModel::factory()->create(
            [
                'operator_type' => User::class,
                'subject_type' => Post::class,
            ]
        );

        OperationRecordModel::factory()->count(20)->create(
            [
                'operator_type' => User::class,
                'subject_type' => Post::class,
                'created_at' => now()->subMonths(4)->toDateTimeString(),
                'updated_at' => now()->subMonths(4)->toDateTimeString()
            ]
        );

        $dataTime = now()->subMonths(3)->toDateTimeString();

        // Act
        $result = OperationRecord::removeBefore($dataTime);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseCount('operation_records', 1);
    }

    /**
     * 移除 $date 後的 操作記錄
     */
    public function testRemoveAfter()
    {
        // Arrange
        OperationRecordModel::factory()->count(20)->create(
            [
                'operator_type' => User::class,
                'subject_type' => Post::class,
            ]
        );

        OperationRecordModel::factory()->create(
            [
                'operator_type' => User::class,
                'subject_type' => Post::class,
                'created_at' => now()->subMonths(4)->toDateTimeString(),
                'updated_at' => now()->subMonths(4)->toDateTimeString()
            ]
        );

        $dataTime = now()->subMonths(3)->toDateTimeString();

        // Act
        $result = OperationRecord::removeAfter($dataTime);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseCount('operation_records', 1);
    }

    /**
     * 清空操作記錄
     */
    public function testTruncate()
    {
        // Arrange
        OperationRecordModel::factory()->count(20)->create(
            [
                'operator_type' => User::class,
                'subject_type' => Post::class,
            ]
        );

        // Act
        $result = OperationRecord::truncate();

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseCount('operation_records', 0);
    }
}
