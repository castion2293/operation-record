<?php

namespace Pharaoh\OperationRecord\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaoh\OperationRecord\Traits\HasOperationRecord;

class User extends Model
{
    use HasOperationRecord;
}
