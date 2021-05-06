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
        'Operator',
        'Subject',
        'FuncKey',
        'Action',
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
    protected function addOperator(Builder $builder)
    {
        $builder->macro(
            'operator',
            function (Builder $builder, array $attributes) {
                $operatorId = Arr::get($attributes, 'operator.id');
                $operatorType = Arr::get($attributes, 'operator.type');

                $operate = (is_array($operatorId)) ? 'whereIn' : 'where';

                return $builder->$operate('operator_id', $operatorId)->where('operator_type', $operatorType);
            }
        );
    }

    /**
     * 篩選 對象ID
     *
     * @param Builder $builder
     */
    protected function addSubject(Builder $builder)
    {
        $builder->macro(
            'subject',
            function (Builder $builder, array $attributes) {
                $subjectId = Arr::get($attributes, 'subject.id');
                $subjectType = Arr::get($attributes, 'subject.type');

                $operate = (is_array($subjectId)) ? 'whereIn' : 'where';

                return $builder->$operate('subject_id', $subjectId)->where('subject_type', $subjectType);
            }
        );
    }

    /**
     * 篩選 功能key
     *
     * @param Builder $builder
     */
    protected function addFuncKey(Builder $builder)
    {
        $builder->macro(
            'funcKey',
            function (Builder $builder, array $attributes) {
                $funcKey = Arr::get($attributes, 'funcKey');

                $operate = (is_array($funcKey)) ? 'whereIn' : 'where';

                return $builder->$operate('func_key', $funcKey);
            }
        );
    }

    /**
     * 篩選 動作
     *
     * @param Builder $builder
     */
    protected function addAction(Builder $builder)
    {
        $builder->macro(
            'action',
            function (Builder $builder, array $attributes) {
                $action = Arr::get($attributes, 'action');

                return $builder->where('action', $action);
            }
        );
    }

    /**
     * 篩選 時間區間
     *
     * @param Builder $builder
     */
    protected function addTimeBetween(Builder $builder)
    {
        $builder->macro(
            'timeBetween',
            function (Builder $builder, array $attributes) {
                $beginAt = Arr::get($attributes, 'timeBetween.beginAt');
                $endAt = Arr::get($attributes, 'timeBetween.endAt');

                return $builder->whereBetween('created_at', [$beginAt, $endAt]);
            }
        );
    }

    /**
     * 排序 時間
     *
     * @param Builder $builder
     */
    protected function addTimeSort(Builder $builder)
    {
        $builder->macro(
            'timeSort',
            function (Builder $builder, array $attributes) {
                $sort = Arr::get($attributes, 'timeSort');

                return $builder->orderBy('id', $sort);
            }
        );
    }
}
