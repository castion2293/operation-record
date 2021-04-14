<?php

namespace Pharaoh\OperationRecord\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pharaoh\OperationRecord\Scopes\OperationRecordScope;

class OperationRecord extends Model
{
    protected $guarded = [];

    use HasFactory;

    protected $casts = [
        'old' => 'array',
        'new' => 'array',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new OperationRecordScope);
    }

    /**
     * 覆寫序列化方法 toArray()時使用
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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
