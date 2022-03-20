# 操作記錄-收集器

## 版本匹配

| Laravel          | package               |
| ----------------- |:----------------------- |
| 8.X       | 1.X   |
| 9.X       | 2.X   |

## 安裝
你可以使用 composer 做安裝
```bash
composer require thoth-pharaoh/operation-record
```

Migrate operation_records 資料表
```bash
php artisan migrate
```

匯出 Migration
```bash
php artisan vendor:publish --tag=operation-record-database --force
```

匯出 Config
```bash
php artisan vendor:publish --tag=operation-record-config --force
```

## 使用方法

### 使用 model 關聯:

先在要使用的 model 中 引入 HasOperationRecord trait
```bash
use Pharaoh\OperationRecord\Traits\HasOperationRecord;

class User extends Model
{
    use HasOperationRecord;
}
```

- 操作者 Model 修改 操作對象 Model 寫紀錄

```bash
$user = new \App\Models\User;
$user->operating($subject, $funcKey, $action, $old, $new, $ip);
```
| 參數 | 說明 | 類型 | 範例 |
| ------------|:----------------------- | :------| :------|
| $subject | 操作對象 model | Model |  |
| $funcKey | 功能 KEY  | int | 1001 |
| $action | 動做  | int | 1 |
| $old | 修改前內容  | array | ['title' => 'before'] |
| $new | 修改後內容  | array | ['title' => 'after'] |
| $ip | string | 操作者 IP | 127.0.0.1 |

> $action 動作參數 請參閱 config.operation_record.action 內容

- 操作對象 Model 被 操作者 Model 修改 寫紀錄

```bash
$post = new \App\Models\Post;
$post->operatedBy($operator, $funcKey, $action $old, $new, $ip);
```
| 參數 | 說明 | 類型 | 範例 |
| ------------|:----------------------- | :------| :------|
| $operator | 操作者 model | Model |  |
| $funcKey | 功能 KEY  | int | 1001 |
| $action | 動做  | int | 1 |
| $old | 修改前內容  | array | ['title' => 'before'] |
| $new | 修改後內容  | array | ['title' => 'after'] |
| $ip | string | 操作者 IP | 127.0.0.1 |

- 操作者 Model 獲取修改記錄

```bash
$user = new \App\Models\User;
$records = $user->getOperatorRecords()->get();
```

- 操作對象 Model 獲取被修改紀錄

```bash
$post = new \App\Models\Post;
$records = $post->getSubjectRecords()->get();
```

### 使用 Facade:

先引入門面
```
use Pharaoh\OperationRecord\Facades\OperationRecord;
```

- 建立一筆 操作記錄
```bash
OperationRecord::create($operatorId, $operatorType, $subjectId, $subjectType, $funcKey, $action ,$old, $new $ip);
```
| 參數 | 說明 | 類型 | 範例 |
| ------------|:----------------------- | :------| :------|
| $operatorId | 操作者 ID | int | 123 |
| $operatorType | 操作者 Model 類型 | string | User::class |
| $subjectId | 操作對象 ID | int | 456 |
| $subjectType | 操作對象 Model 類型 | string | Post::class |
| $funcKey | 功能 KEY  | int | 1001 |
| $action | 動做  | int | 1 |
| $old | 修改前內容  | array | ['title' => 'before'] |
| $new | 修改後內容  | array | ['title' => 'after'] |
| $ip | string | 操作者 IP | 127.0.0.1 |

- 建立一筆 操作記錄(使用 queue job 的方式)
```bash
OperationRecord::dispatch($operatorId, $operatorType, $subjectId, $subjectType, $funcKey, $action, $old, $new $ip);
```

queue 名稱 以 config/operation_record.php 裡設定的名稱為主

- 搜尋操作紀錄
```bash
$params = [
    'operator' => [
        'id' => 1,
        'type' => User::class,
    ],
    'subject' => [
        'id' => [2, 3],
        'type' => Post::class,
    ],
    .....
];

OperationRecord::find($params);
```
$params 內容可以自定義選則搭配，可選項目如下:

| 參數 | 欄位 | 說明 | 預設 |
| ------------|:----------------------- |:----------------------- |:-----------------------
| operator['id'] | 操作者 ID | 一筆使用 int (ex: 1) 多筆使用 array (ex: [1, 2, 3]) | |
| operator['type'] | 操作者 model 類型 | model類別名稱 (ex:User::class) | |
| subject['id'] | 操作對象 ID | 一筆使用 int (ex: 4) 多筆使用 array (ex: [4, 5, 6]) | |
| subject['type'] | 操作對象 model 類型 | model類別名稱 (ex:Post::class) | |
| action | 動做  | 參閱 config.operation_record.action內容 |  |
| func_key | 功能 KEY | 一筆使用 int (ex: 1001) 多筆使用 array (ex: [1001, 1002, 1003]) | |
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




