# 操作記錄-收集器

## 安裝
你可以使用 composer 做安裝
```bash
composer require thoth-pharaoh/operation-record
```

匯出 Migration
```bash
php artisan vendor:publish --tag=database --force
```

## 使用方法

先引入門面
```
use Pharaoh\OperationRecord\Facades\OperationRecord;
```

- 建立一筆 操作記錄
```bash
OperationRecord::create($operatorId, $subjectId, $funcKey, $status, $type, $targets, $content, $ip);
```
| 參數 | 說明 | 類型 | 範例 |
| ------------|:----------------------- | :------| :------|
| $operatorId | 操作者 ID | int | 123 |
| $subjectId | 操作對象 ID | int | 456 |
| $funcKey | 功能 KEY  | int | 1001 |
| $status | 狀態 | int | 1 |
| $type | 操作類型 | string | admin |
| $targets | string | 操作對象 | Gabriella Rohan |
| $content | text | 操作內容 | Sed est ipsum earum est sapiente debitis. |
| $ip | string | 操作者 IP | 127.0.0.1 |

- 搜尋操作紀錄
```bash
$params = [
    'operator_id' => $operatorId,
    'subjectId' => $subjectId,
    .....
];

OperationRecord::find($params);
```
$params 內容可以自定義選則搭配，可選項目如下:

| 參數 | 欄位 | 說明 | 預設 |
| ------------|:----------------------- |:----------------------- |:-----------------------
| operator_id | 操作者 ID | 一筆使用 int (ex: 1) 多筆使用 array (ex: [1, 2, 3]) | |
| subject_id | 操作對象 ID | 一筆使用 int (ex: 4) 多筆使用 array (ex: [4, 5, 6]) | |
| func_key | 功能 KEY | 一筆使用 int (ex: 1001) 多筆使用 array (ex: [1001, 1002, 1003]) | |
| status | 狀態 | 一筆使用 int (ex: 1) 多筆使用 array (ex: [1, 2, 3]) | | 
| type | 操作類型 | 一筆使用 string (ex: 'admin') 多筆使用 array (ex: ['admin', 'agent']) | |
| begin_at | 開始時間 | datatime '2020-09-27 00:00:00' | 當日 00:00:00 |
| end_at | 結束時間 | datatime '2020-09-27 23:59:59' | 當日 23:59:59 |
| sort | 時間排序 | string 正序 'asc' 倒序 'desc'| 'desc' |
| page | 第幾頁 | int 1| 1 |
| per_page | 每頁幾筆 | int 10| 10 |

- 移除 $datetime 前的 操作記錄
```bash
$dataTime = '2020-07-30 00:00:00'

OperationRecord::removeBefore($dataTime);
```

- 移除 $datetime 後的 操作記錄
```bash
$dataTime = '2020-07-30 00:00:00'

OperationRecord::removeAfter($dataTime);
```

- 清空操作記錄
```bash
OperationRecord::truncate();
```




 