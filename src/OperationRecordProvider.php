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
    }

    public function register()
    {
        parent::register();

        $loader = AliasLoader::getInstance();
        $loader->alias('operation_record', 'Pharaoh\OperationRecord\OperationRecord');
    }
}
