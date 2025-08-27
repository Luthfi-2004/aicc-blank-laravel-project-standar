<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = 'tb_process';

    protected $fillable = [
        'process_date','shift','plant','mm_no','mix_no','mix_start','mix_finish',
        'sample_p','sample_c','sample_gt','cb_mm','cb_lab','sample_m','bakunetsu',
        'sample_ac','sample_tc','vsd_mm','sample_ig','cb_weight','tp50_weight','ssi',
        'additive_m3','additive_vsd','additive_sc',
        'bc12_cb','bc12_m','bc11_ac','bc11_vsd','bc16_cb','bc16_m',
        'return_time','model_type','moisture_bc9','moisture_bc10','moisture_bc11',
        'temp_bc9','temp_bc10','temp_bc11',
    ];

    protected $casts = [
        'process_date' => 'date',
        'mix_start'    => 'datetime',
        'mix_finish'   => 'datetime',
        'return_time'  => 'datetime',
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
