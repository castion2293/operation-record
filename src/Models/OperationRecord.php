<?php

namespace Pharaoh\OperationRecord\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pharaoh\OperationRecord\Scopes\OperationRecordScope;

class OperationRecord extends Model
{
    protected $guarded = [];

    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new OperationRecordScope);
    }

    public function getCreatedAtDateTimeAttribute()
    {
        return  $this->created_at->toDateTimeString();
    }

    public function getUpdatedAtDateTimeAttribute()
    {
        return  $this->updated_at->toDateTimeString();
    }
}
