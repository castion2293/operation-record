<?php

namespace Pharaoh\OperationRecord\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class OperationRecord extends Model
{
    protected $guarded = [];

    use HasFactory;

    /**
     * 篩選 操作者ID
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeOperatorId($query, array $attributes)
    {
        $operatorId = Arr::get($attributes, 'operatorId');

        $operate = (is_array($operatorId)) ? 'whereIn' : 'where';

        return $query->$operate('operator_id', $operatorId);
    }

    /**
     * 篩選 功能ID
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeSubjectId($query, array $attributes)
    {
        $subjectId = Arr::get($attributes, 'subjectId');

        $operate = (is_array($subjectId)) ? 'whereIn' : 'where';

        return $query->$operate('subject_id', $subjectId);
    }

    /**
     * 篩選 功能key
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeFuncKey($query, array $attributes)
    {
        $funcKey = Arr::get($attributes, 'funcKey');

        $operate = (is_array($funcKey)) ? 'whereIn' : 'where';

        return $query->$operate('func_key', $funcKey);
    }

    /**
     * 篩選 操作者類型
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeType($query, array $attributes)
    {
        $type = Arr::get($attributes, 'type');

        $operate = (is_array($type)) ? 'whereIn' : 'where';

        return $query->$operate('type', $type);
    }

    /**
     * 篩選 顯示狀態
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeStatus($query, array $attributes)
    {
        $status = Arr::get($attributes, 'status');

        $operate = (is_array($status)) ? 'whereIn' : 'where';

        return $query->$operate('status', $status);
    }

    /**
     * 篩選 時間區間
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeTimeBetween($query, array $attributes)
    {
        $beginAt = Arr::get($attributes, 'timeBetween.beginAt');
        $endAt = Arr::get($attributes, 'timeBetween.endAt');

        return $query->whereBetween('created_at', [$beginAt, $endAt]);
    }

    /**
     * 排去 時間
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeTimeSort($query, array $attributes)
    {
        $sort = Arr::get($attributes, 'timeSort');

        return $query->orderBy('created_at', $sort);
    }

    public function getCreatedAtDateTimeAttribute()
    {
        return  $this->created_at->toDateTimeString();
    }

    public function getUpdatedAtDateTimeAttribute()
    {
        return  $this->updated_at->toDateTimeString();
    }
}
