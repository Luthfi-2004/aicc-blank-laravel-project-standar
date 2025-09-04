<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JshGfn extends Model
{
    protected $table = 'tb_jsh_gfns'; // pakai nama tabel sesuai DB

    protected $fillable = [
        'batch_code',
        'gfn_date',
        'shift',
        'mesh',
        'gram',
        'percentage',
        'index',
        'percentage_index',
        'total_gram',
        'total_percentage_index',
    ];
}
