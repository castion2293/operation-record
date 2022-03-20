<?php

namespace Pharaoh\OperationRecord\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Pharaoh\OperationRecord\Services\OperationRecordService;

class OperationRecordCreateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $params = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param OperationRecordService $operationRecordService
     * @return void
     */
    public function handle(OperationRecordService $operationRecordService)
    {
        $operationRecordService->create($this->params);
    }
}
