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
            $table->bigIncrements('id')->comment('PK');
            $table->unsignedInteger('operator_id')->comment('修改者ID');
            $table->string('operator_type')->comment('修改者類型');
            $table->unsignedBigInteger('subject_id')->comment('被修改者ID');
            $table->string('subject_type')->comment('被修改者類型');
            $table->string('func_key', 10)->comment('功能key');
            $table->json('old')->nullable()->comment('修改前內容');
            $table->json('new')->nullable()->comment('修改後內容');
            $table->string('ip', 46)->default('')->comment('IP');
            $table->text('comment')->nullable()->comment('備註');

            // 建立時間
            $table->datetime('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('建立時間');

            // 最後更新
            $table->datetime('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
                ->comment('最後更新');

            $table->index(['operator_id', 'created_at'], 'idx_operation_records_1');
            $table->index(['subject_id', 'created_at'], 'idx_operation_records_2');
        });

        DB::statement("ALTER TABLE operation_records COMMENT '操作日誌'");
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
