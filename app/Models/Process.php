<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = 'tb_process';
    protected $guarded = [];

    protected $casts = [
        'process_date' => 'datetime:Y-m-d H:i:s',
        'mix_start'    => 'datetime:Y-m-d H:i:s',
        'mix_finish'   => 'datetime:Y-m-d H:i:s',
        'return_time'  => 'datetime:Y-m-d H:i:s',
        'plant'        => 'integer',
        'mm_no'        => 'integer',
        'mix_no'       => 'integer',
    ];

    public function scopeOnDateAndMm($query, $date, int $mmNo)
    {
        return $query->whereDate('process_date', $date)->where('mm_no', $mmNo);
    }

    public static function existsOnDateAndMm($date, int $mmNo, ?int $ignoreId = null): bool
    {
        return static::query()
            ->onDateAndMm($date, $mmNo)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }
}
