<?php

namespace App\Support;

use App\Models\Process;
use Illuminate\Database\Eloquent\Builder;

class GreensandQuery
{
    public static function build(array $filters = []): Builder
    {
        $q = Process::query();

        if (!empty($filters['mm'])) {
            $q->where('mm_no', (int)$filters['mm']);
        }
        if (!empty($filters['start'])) {
            $q->whereDate('process_date', '>=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $q->whereDate('process_date', '<=', $filters['end']);
        }
        if (!empty($filters['shift'])) {
            $q->where('shift', $filters['shift']);
        }
        if (!empty($filters['q'])) {
            $kw = '%'.trim($filters['q']).'%';
            $q->where(function (Builder $w) use ($kw) {
                $w->where('mix_no', 'like', $kw)
                  ->orWhere('model_type', 'like', $kw);
            });
        }

        return $q->orderByDesc('process_date')->orderByDesc('mix_no');
    }
}
