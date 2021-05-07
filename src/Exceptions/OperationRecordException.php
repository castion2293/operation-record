<?php

namespace Pharaoh\OperationRecord\Exceptions;

use Exception;
use Illuminate\Database\QueryException;

class OperationRecordException extends Exception
{
    public function __construct(Exception $exception)
    {
        if ($exception instanceof QueryException) {
            parent::__construct($exception->getMessage(), 500, $exception);
            return;
        }

        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }
}
