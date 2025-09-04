<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalGfn extends Model
{
    protected $table = 'tb_total_gfn';

    protected $fillable = [
        'batch_code',
        'nilai_gfn',
        'mesh_total140',
        'mesh_total70',
        'meshpan',
        'judge_mesh_140',
        'judge_mesh_70',
        'judge_meshpan',
    ];
}
