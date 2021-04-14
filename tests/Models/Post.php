<?php

namespace Pharaoh\OperationRecord\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaoh\OperationRecord\Traits\HasOperationRecord;

class Post extends Model
{
    use HasOperationRecord;
}
