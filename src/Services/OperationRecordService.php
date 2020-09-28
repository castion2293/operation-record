<?php

namespace Pharaoh\OperationRecord\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Pharaoh\OperationRecord\Models\OperationRecord;

class OperationRecordService
{
    const SUCCESS = '200';
    const FAILURE = '500';

    protected $operationRecord;

    public function __construct(OperationRecord $operationRecord)
    {
        $this->operationRecord = $operationRecord;
    }

    /**
     * 建立 操作紀錄資料
     *
     * @param array $params
     * @return array
     */
    public function create(array $params = []): array
    {
        try {
            $this->operationRecord->create($params);

            return [
                'code' => self::SUCCESS,
                'data' => true
            ];
        } catch (\Exception $exception) {
            return [
                'code' => self::FAILURE,
                'error' => $exception->getMessage(),
            ];
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
     */
    public function find(array $attributes = []): array
    {
        try {
            $operationRecordBuilder = $this->operationRecord->query();

            foreach ($attributes as $function => $attribute) {
                $functionName = Str::of($function)->studly()->prepend('scope');
                if (method_exists($this->operationRecord, $functionName)) {
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
                'code' => self::SUCCESS,
                'data' => $data
            ];
        } catch (\Exception $exception) {
            return [
                'code' => self::FAILURE,
                'error' => $exception->getMessage(),
            ];
        }
    }

    /**
     * 移除 $date 前的 操作記錄
     *
     * @param string $dateTime
     * @return array
     */
    public function removeBefore(string $dateTime): array
    {
        try {
            $this->operationRecord->where('created_at', '<', $dateTime)->delete();

            return [
                'code' => self::SUCCESS,
                'data' => true
            ];
        } catch (\Exception $exception) {
            return [
                'code' => self::FAILURE,
                'error' => $exception->getMessage(),
            ];
        }
    }

    /**
     * 移除 $date 後的 操作記錄
     *
     * @param string $dateTime
     * @return array
     */
    public function removeAfter(string $dateTime): array
    {
        try {
            $this->operationRecord->where('created_at', '>', $dateTime)->delete();

            return [
                'code' => self::SUCCESS,
                'data' => true
            ];
        } catch (\Exception $exception) {
            return [
                'code' => self::FAILURE,
                'error' => $exception->getMessage(),
            ];
        }
    }

    /**
     * 清空操作記錄
     *
     * @return array
     */
    public function truncate(): array
    {
        try {
            $this->operationRecord->truncate();

            return [
                'code' => self::SUCCESS,
                'data' => true
            ];
        } catch (\Exception $exception) {
            return [
                'code' => self::FAILURE,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
