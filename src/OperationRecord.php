<?php

namespace Pharaoh\OperationRecord;

use Illuminate\Support\Arr;
use Pharaoh\OperationRecord\Jobs\OperationRecordCreateJob;
use Pharaoh\OperationRecord\Services\OperationRecordService;

class OperationRecord
{
    protected $operationRecordService;

    public function __construct(OperationRecordService $operationRecordService)
    {
        $this->operationRecordService = $operationRecordService;
    }

    /**
     * 建立一筆 操作記錄
     *
     * @param int $operatorId
     * @param string $operatorType
     * @param int $subjectId
     * @param string $subjectType
     * @param string $funcKey
     * @param int $action
     * @param array $old
     * @param array $new
     * @param string $ip
     * @return bool
     * @throws Exceptions\OperationRecordException
     */
    public function create(
        int $operatorId,
        string $operatorType,
        int $subjectId,
        string $subjectType,
        string $funcKey,
        int $action,
        array $old = [],
        array $new = [],
        string $ip = '',
    ): bool {
        return $this->operationRecordService->create(
            [
                'operator_id' => $operatorId,
                'operator_type' => $operatorType,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
                'func_key' => $funcKey,
                'action' => $action,
                'old' => $old,
                'new' => $new,
                'ip' => $ip,
            ]
        );
    }

    /**
     * 建立一筆 操作記錄 使用 queue job 的方式
     *
     * @param int $operatorId
     * @param string $operatorType
     * @param int $subjectId
     * @param string $subjectType
     * @param int $funcKey
     * @param int $action
     * @param array $old
     * @param array $new
     * @param string $ip
     */
    public function dispatch(
        int $operatorId,
        string $operatorType,
        int $subjectId,
        string $subjectType,
        int $funcKey,
        int $action,
        array $old = [],
        array $new = [],
        string $ip = ''
    ) {
        dispatch(
            new OperationRecordCreateJob(
                [
                    'operator_id' => $operatorId,
                    'operator_type' => $operatorType,
                    'subject_id' => $subjectId,
                    'subject_type' => $subjectType,
                    'func_key' => $funcKey,
                    'action' => $action,
                    'old' => $old,
                    'new' => $new,
                    'ip' => $ip,
                ]
            )
        )->onQueue(config('operation_record.queue'));
    }

    /**
     * 搜尋操作紀錄
     *
     * @param array $params
     * @return array
     * @throws Exceptions\OperationRecordException
     */
    public function find(array $params = []): array
    {
        // 預設條件
        $attributes = [
            'timeBetween' => [
                'beginAt' => now()->startOfDay()->toDateTimeString(),
                'endAt' => now()->endOfDay()->toDateTimeString()
            ],
            'timeSort' => 'desc',
            'perPage' => 10,
            'page' => 1
        ];

        if (array_key_exists('operator', $params)) {
            $attributes['operator'] = Arr::get($params, 'operator');
        }

        if (array_key_exists('subject', $params)) {
            $attributes['subject'] = Arr::get($params, 'subject');
        }

        if (array_key_exists('func_key', $params)) {
            $attributes['funcKey'] = Arr::get($params, 'func_key');
        }

        if (array_key_exists('action', $params)) {
            $attributes['action'] = Arr::get($params, 'action');
        }

        if (array_key_exists('begin_at', $params) && array_key_exists('end_at', $params)) {
            $attributes['timeBetween']['beginAt'] = Arr::get($params, 'begin_at');
            $attributes['timeBetween']['endAt'] = Arr::get($params, 'end_at');
        }

        if (array_key_exists('per_page', $params)) {
            $attributes['perPage'] = Arr::get($params, 'per_page');
        }

        if (array_key_exists('page', $params)) {
            $attributes['page'] = Arr::get($params, 'page');
        }

        return $this->operationRecordService->find($attributes);
    }

    /**
     * 移除 $date 前的 操作記錄
     *
     * @param string $dataTime
     * @return bool
     * @throws Exceptions\OperationRecordException
     */
    public function removeBefore(string $dataTime): bool
    {
        return $this->operationRecordService->removeBefore($dataTime);
    }

    /**
     * 移除 $date 後的 操作記錄
     *
     * @param string $dataTime
     * @return bool
     * @throws Exceptions\OperationRecordException
     */
    public function removeAfter(string $dataTime): bool
    {
        return $this->operationRecordService->removeAfter($dataTime);
    }

    /**
     * 清空操作記錄
     *
     * @return bool
     * @throws Exceptions\OperationRecordException
     */
    public function truncate(): bool
    {
        return $this->operationRecordService->truncate();
    }
}
