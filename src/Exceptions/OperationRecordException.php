<?php

namespace Pharaoh\OperationRecord\Exceptions;

use Exception;

class OperationRecordException extends Exception
{
    public function __construct(Exception $exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }
}
