<?php

namespace Pharaoh\OperationRecord\Traits;

use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;
use Pharaoh\OperationRecord\Facades\OperationRecord;
use Pharaoh\OperationRecord\Models\OperationRecord as OperationRecordModel;

trait HasOperationRecord
{
    /**
     * 操作者 修改資料行為
     *
     * @param Model $model
     * @param string $funcKey
     * @param array $old
     * @param array $new
     * @param string $ip
     * @param string $comment
     */
    public function operating(Model $model, string $funcKey, array $old = [], array $new = [], string $ip = '', string $comment = ''): void
    {
        OperationRecord::create($this->id, $this::class, $model->id, $model::class, $funcKey, $old, $new, $ip, $comment);
    }

    /**
     * 被操作者 被修改資料行為
     *
     * @param Model $model
     * @param string $funcKey
     * @param array $old
     * @param array $new
     * @param string $ip
     * @param string $comment
     */
    public function operatedBy(Model $model, string $funcKey, array $old = [], array $new = [], string $ip = '', string $comment = '')
    {
        OperationRecord::create($model->id, $model::class, $this->id, $this::class, $funcKey, $old, $new, $ip, $comment);
    }

    /**
     * 修改者紀錄
     */
    public function getOperatorRecords(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(OperationRecordModel::class, 'operator')
            ->select(['operator_id', 'subject_id', 'old', 'new', 'ip', 'created_at', 'updated_at']);
    }

    /**
     * 被修改者紀錄
     */
    public function getSubjectRecords(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(OperationRecordModel::class, 'subject')
            ->select(['operator_id', 'subject_id', 'old', 'new', 'ip', 'created_at', 'updated_at']);
    }
}
