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
        array $old = [],
        array $new = [],
        string $ip = '',
        string $comment = '',
    ): bool {
        return $this->operationRecordService->create(
            [
                'operator_id' => $operatorId,
                'operator_type' => $operatorType,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
                'func_key' => $funcKey,
                'old' => $old,
                'new' => $new,
                'ip' => $ip,
                'comment' => $comment
            ]
        );
    }

//    /**
//     * 建立一筆 操作記錄 使用 queue job 的方式
//     *
//     * @param int $operatorId
//     * @param int $subjectId
//     * @param int $funcKey
//     * @param int $status
//     * @param string $type
//     * @param string $targets
//     * @param string $content
//     * @param string $ip
//     * @return array
//     */
//    public function dispatch(
//        int $operatorId,
//        int $subjectId,
//        int $funcKey,
//        int $status,
//        string $type,
//        string $targets,
//        string $content,
//        string $ip = ''
//    ) {
//        dispatch(
//            new OperationRecordCreateJob(
//                [
//                    'operator_id' => $operatorId,
//                    'subject_id' => $subjectId,
//                    'func_key' => $funcKey,
//                    'status' => $status,
//                    'type' => $type,
//                    'targets' => $targets,
//                    'content' => $content,
//                    'ip' => $ip
//                ]
//            )
//        )->onQueue(config('operation_record.queue'));
//    }

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

        if (array_key_exists('operator_id', $params)) {
            $attributes['operatorId'] = Arr::get($params, 'operator_id');
        }

        if (array_key_exists('subject_id', $params)) {
            $attributes['subjectId'] = Arr::get($params, 'subject_id');
        }

        if (array_key_exists('func_key', $params)) {
            $attributes['funcKey'] = Arr::get($params, 'func_key');
        }

        if (array_key_exists('status', $params)) {
            $attributes['status'] = Arr::get($params, 'status');
        }

        if (array_key_exists('type', $params)) {
            $attributes['type'] = Arr::get($params, 'type');
        }

        if (array_key_exists('begin_at', $params) && array_key_exists('end_at', $params)) {
            $attributes['timeBetween']['beginAt'] = Arr::get($params, 'begin_at');
            $attributes['timeBetween']['endAt'] = Arr::get($params, 'end_at');
        }

        if (array_key_exists('type', $params)) {
            $attributes['timeSort'] = Arr::get($params, 'sort');
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
