<?php

namespace Pharaoh\OperationRecord\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Pharaoh\OperationRecord\Facades\OperationRecord;
use Pharaoh\OperationRecord\Tests\Models\Post;
use Pharaoh\OperationRecord\Tests\Models\User;

class ModelOperationRecordTest extends BaseTestCase
{
    use DatabaseMigrations;

    protected Model $user;
    protected Model $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->post = new Post();

        $this->user->id = 1;
        $this->post->id = 2;
    }

    /**
     * 測試 User Model 修改 Post Model
     */
    public function testUserUpdatePost()
    {
        // Arrange
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $funcKey = 1101;
        $action = config('operation_record.action.update');
        $ip = '192.168.0.1';

        // Act
        $this->user->operating($this->post, $funcKey, $action, $old, $new, $ip);

        // Assert
        $this->assertDatabaseHas(
            'operation_records',
            [
                'operator_id' => $this->user->id,
                'operator_type' => $this->user::class,
                'subject_id' => $this->post->id,
                'subject_type' => $this->post::class,
                'func_key' => $funcKey,
                'action' => $action,
                'ip' => $ip,
            ]
        );
    }

    /**
     * 測試 Post Model 被 User Model 修改
     */
    public function testPostBeUpdatedByUser()
    {
        // Arrange
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $funcKey = 1101;
        $action = config('operation_record.action.update');
        $ip = '192.168.0.1';

        // Act
        $this->post->operatedBy($this->user, $funcKey, $action, $old, $new, $ip);

        // Assert
        $this->assertDatabaseHas(
            'operation_records',
            [
                'operator_id' => $this->user->id,
                'operator_type' => $this->user::class,
                'subject_id' => $this->post->id,
                'subject_type' => $this->post::class,
                'func_key' => $funcKey,
                'action' => $action,
                'ip' => $ip,
            ]
        );
    }

    /**
     * 測試 修改者獲取修改記錄
     */
    public function testUserGetOperatorRecords()
    {
        // Arrange
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $funcKey = '1101';
        $action = config('operation_record.action.update');
        $ip = '192.168.0.1';

        for ($i = 0; $i < 10; $i++) {
            OperationRecord::create(
                $this->user->id,
                $this->user::class,
                $this->post->id,
                $this->post::class,
                $funcKey,
                $action,
                $old,
                $new,
                $ip,
            );
        }

        // Act
        $records = $this->user->getOperatorRecords()->get()->toArray();

        // Assert
        $this->assertCount(10, $records);

        $record = Arr::first($records);
        $this->assertEquals(Arr::get($record, 'operator_id'), $this->user->id);
    }

    /**
     * 測試 被修改者獲取修改記錄
     */
    public function testPostGetSubjectRecord()
    {
        // Arrange
        $old = ['title' => 'old_title'];
        $new = ['title' => 'new_title'];
        $funcKey = 1101;
        $action = config('operation_record.action.update');
        $ip = '192.168.0.1';

        for ($i = 0; $i < 7; $i++) {
            OperationRecord::create(
                $this->user->id,
                $this->user::class,
                $this->post->id,
                $this->post::class,
                $funcKey,
                $action,
                $old,
                $new,
                $ip,
            );
        }

        for ($i = 0; $i < 3; $i++) {
            OperationRecord::create(
                $this->user->id,
                $this->user::class,
                3,
                $this->post::class,
                $funcKey,
                $action,
                $old,
                $new,
                $ip,
            );
        }

        // Act
        $records = $this->post->getSubjectRecords()->get()->toArray();

        // Assert
        $this->assertCount(7, $records);

        $record = Arr::first($records);
        $this->assertEquals(Arr::get($record, 'subject_id'), $this->post->id);
    }
}
