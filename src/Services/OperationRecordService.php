<?php

namespace Pharaoh\OperationRecord\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Pharaoh\OperationRecord\Exceptions\OperationRecordException;
use Pharaoh\OperationRecord\Models\OperationRecord;
use Pharaoh\OperationRecord\Scopes\OperationRecordScope;

class OperationRecordService
{
    protected $operationRecord;

    public function __construct(OperationRecord $operationRecord)
    {
        $this->operationRecord = $operationRecord;
    }

    /**
     * 建立 操作紀錄資料
     *
     * @param array $params
     * @return bool
     * @throws OperationRecordException
     */
    public function create(array $params = []): bool
    {
        try {
            $this->operationRecord->create($params);

            return true;
        } catch (\Exception $exception) {
            throw new OperationRecordException($exception);
        }
    }

    /**
     * 搜尋操作紀錄
     * params 欄位:
     *   operatorId
     *   subjectId
     *   funcKey
     *   type
     *   status
     *   timeBetween
     *   timeSort
     *   page
     *   perPage
     *
     * @param array $attributes
     * @return array
     * @throws OperationRecordException
     */
    public function find(array $attributes = []): array
    {
        try {
            $operationRecordBuilder = $this->operationRecord->query();

            foreach ($attributes as $function => $attribute) {
                $functionName = Str::of($function)->studly()->prepend('add');
                if (method_exists(OperationRecordScope::class, $functionName)) {
                    $operationRecordBuilder = $operationRecordBuilder->$function($attributes);
                }
            }

            $perPage = Arr::get($attributes, 'perPage');
            $page = Arr::get($attributes, 'page');
            $records = $operationRecordBuilder->paginate($perPage, ['*'], 'page', $page);

            $data = [
                'total' => $records->total(),
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'items' => collect($records->items())->map(
                    function ($record) {
                        return [
                            'id' => data_get($record, 'id'),
                            'operator_id' => data_get($record, 'operator_id'),
                            'func_key' => data_get($record, 'func_key'),
                            'subject_id' => data_get($record, 'subject_id'),
                            'type' => data_get($record, 'type'),
                            'status' => data_get($record, 'status'),
                            'targets' => data_get($record, 'targets'),
                            'content' => data_get($record, 'content'),
                            'ip' => data_get($record, 'ip'),
                            'created_at' => $record->createdAtDateTime,
                            'updated_at' => $record->updatedAtDateTime,
                        ];
                    }
                )->toArray()
            ];

            return [
                'data' => $data
            ];
        } catch (\Exception $exception) {
            throw new OperationRecordException($exception);
        }
    }

    /**
     * 移除 $date 前的 操作記錄
     *
     * @param string $dateTime
     * @return bool
     * @throws OperationRecordException
     */
    public function removeBefore(string $dateTime): bool
    {
        try {
            $this->operationRecord->where('created_at', '<', $dateTime)->delete();

            return true;
        } catch (\Exception $exception) {
            throw new OperationRecordException($exception);
        }
    }

    /**
     * 移除 $date 後的 操作記錄
     *
     * @param string $dateTime
     * @return bool
     */
    public function removeAfter(string $dateTime): bool
    {
        try {
            $this->operationRecord->where('created_at', '>', $dateTime)->delete();

            return true;
        } catch (\Exception $exception) {
            throw new OperationRecordException($exception);
        }
    }

    /**
     * 清空操作記錄
     *
     * @return bool
     * @throws OperationRecordException
     */
    public function truncate(): bool
    {
        try {
            $this->operationRecord->truncate();

            return true;
        } catch (\Exception $exception) {
            throw new OperationRecordException($exception);
        }
    }
}
