<?php

namespace Pharaoh\OperationRecord\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Arr;

class OperationRecordScope implements Scope
{
    /**
     * @var array
     */
    protected $extensions = [
        'OperatorId',
        'SubjectId',
        'FuncKey',
        'Type',
        'Status',
        'TimeBetween',
        'TimeSort'
    ];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * 篩選 操作者ID
     *
     * @param Builder $builder
     */
    protected function addOperatorId(Builder $builder)
    {
        $builder->macro('operatorId', function (Builder $builder, array $attributes) {
            $operatorId = Arr::get($attributes, 'operatorId');

            $operate = (is_array($operatorId)) ? 'whereIn' : 'where';

            return $builder->$operate('operator_id', $operatorId);
        });
    }

    /**
     * 篩選 對象ID
     *
     * @param Builder $builder
     */
    protected function addSubjectId(Builder $builder)
    {
        $builder->macro('subjectId', function (Builder $builder, array $attributes) {
            $subjectId = Arr::get($attributes, 'subjectId');

            $operate = (is_array($subjectId)) ? 'whereIn' : 'where';

            return $builder->$operate('subject_id', $subjectId);
        });
    }

    /**
     * 篩選 功能key
     *
     * @param Builder $builder
     */
    protected function addFuncKey(Builder $builder)
    {
        $builder->macro('funcKey', function (Builder $builder, array $attributes) {
            $funcKey = Arr::get($attributes, 'funcKey');

            $operate = (is_array($funcKey)) ? 'whereIn' : 'where';

            return $builder->$operate('func_key', $funcKey);
        });
    }

    /**
     * 篩選 操作者類型
     *
     * @param Builder $builder
     */
    protected function addType(Builder $builder)
    {
        $builder->macro('type', function (Builder $builder, array $attributes) {
            $funcKey = Arr::get($attributes, 'type');

            $operate = (is_array($funcKey)) ? 'whereIn' : 'where';

            return $builder->$operate('type', $funcKey);
        });
    }

    /**
     * 篩選 顯示狀態
     *
     * @param Builder $builder
     */
    protected function addStatus(Builder $builder)
    {
        $builder->macro('status', function (Builder $builder, array $attributes) {
            $status = Arr::get($attributes, 'status');

            $operate = (is_array($status)) ? 'whereIn' : 'where';

            return $builder->$operate('status', $status);
        });
    }

    /**
     * 篩選 時間區間
     *
     * @param Builder $builder
     */
    protected function addTimeBetween(Builder $builder)
    {
        $builder->macro('timeBetween', function (Builder $builder, array $attributes) {
            $beginAt = Arr::get($attributes, 'timeBetween.beginAt');
            $endAt = Arr::get($attributes, 'timeBetween.endAt');

            return $builder->whereBetween('created_at', [$beginAt, $endAt]);
        });
    }

    /**
     * 排序 時間
     *
     * @param Builder $builder
     */
    protected function addTimeSort(Builder $builder)
    {
        $builder->macro('timeSort', function (Builder $builder, array $attributes) {
            $sort = Arr::get($attributes, 'timeSort');

            return $builder->orderBy('created_at', $sort);
        });
    }
}
