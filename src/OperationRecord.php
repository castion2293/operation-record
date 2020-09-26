<?php

namespace Pharaoh\OperationRecord;

class OperationRecord
{
    public function __construct()
    {
    }

    /**
     * 建立一筆 操作記錄
     *
     * @param int $operatorId
     * @param int $subjectId
     * @param int $funcKey
     * @param int $status
     * @param string $type
     * @param string $targets
     * @param string $content
     * @return bool
     */
    public function create(int $operatorId, int $subjectId, int $funcKey, int $status, string $type, string $targets, string $content): bool
    {
    }

    /**
     * 搜尋操作紀錄
     *
     * @param array $params
     * @return array
     */
    public function find(array $params = []): array
    {
    }

    /**
     * 移除 $date 前的 操作記錄
     *
     * @param string $dataTime
     * @return bool
     */
    public function removeBefore(string $dataTime): bool
    {
    }

    /**
     * 移除 $date 後的 操作記錄
     *
     * @param string $dataTime
     * @return bool
     */
    public function removeAfter(string $dataTime): bool
    {
    }

    /**
     * 清空操作記錄
     *
     * @return bool
     */
    public function truncate(): bool
    {
    }
}
