<?php

namespace Pharaoh\OperationRecord;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class OperationRecordProvider extends ServiceProvider
{
    public function boot()
    {
        // 合併套件migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes(
            [
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ],
            'database'
        );

        // 合併套件設定檔
        $this->mergeConfigFrom(
            __DIR__ . '/../config/operation_record.php',
            'operation_record'
        );

        $this->publishes(
            [
                __DIR__ . '/../config' => config_path(),
            ],
            'config'
        );
    }

    public function register()
    {
        parent::register();

        $loader = AliasLoader::getInstance();
        $loader->alias('operation_record', 'Pharaoh\OperationRecord\OperationRecord');
    }
}
