<?php

return [
    // 寫紀錄的佇列名稱
    'queue' => 'default',

    // 寫紀錄的動做行為代碼
    'action' => [
        'create' => 1,
        'update' => 2,
        'delete' => 3,
    ]
];
