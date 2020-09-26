<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_records', function (Blueprint $table) {
            $table->id()->from(1000000);
            $table->unsignedInteger('operator_id')->default(0)->comment('操作者ID');
            $table->unsignedSmallInteger('func_key')->default(0)->comment('功能Key');
            $table->unsignedBigInteger('subject_id')->default(0)->comment('功能ID');
            $table->string('type')->default('')->comment('操作者類型');
            $table->unsignedTinyInteger('status')->default(2)->comment('是否排除顯示於單筆歷程資料(1:是, 2:否)');
            $table->string('targets', 100)->comment('操作對象');
            $table->text('content')->comment('操作內容');
            $table->string('ip', 46)->default('')->comment('操作者IP');
            // 建立時間
            $table->datetime('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('建立時間');

            // 最後更新
            $table->datetime('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
                ->comment('最後更新');

            $table->index(['operator_id', 'created_at', 'func_key', ], 'idx_operation_records_1');
            $table->index(['subject_id', 'created_at', 'func_key'], 'idx_operation_records_2');
        });

        DB::statement("ALTER TABLE operation_records COMMENT '控端 - 操作日誌'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_records');
    }
}
