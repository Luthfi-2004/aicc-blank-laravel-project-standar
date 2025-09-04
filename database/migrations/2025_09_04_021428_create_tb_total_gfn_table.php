<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_total_gfn', function (Blueprint $table) {
            $table->id();

            // Batch yang mengelompokkan 10 baris detail di tb_jsh_gfns
            $table->string('batch_code')->unique();          // Σ GRAM

            // Hasil utama
            // nilai_gfn = total_percentage_index / 100
            $table->decimal('nilai_gfn', 10, 2)->default(0);

            // Rekap % sesuai requirement
            $table->decimal('mesh_total140', 8, 2)->default(0);   // % baris mesh 140
            $table->decimal('mesh_total70', 8, 2)->default(0);    // Σ% mesh 50 + 70 + 100
            $table->decimal('meshpan', 8, 2)->default(0);         // Σ% mesh 280 + PAN

            // Judge per-parameter + keseluruhan
            $table->string('judge_mesh_140', 8)->default('NG');     // OK/NG
            $table->string('judge_mesh_70', 8)->default('NG');      // OK/NG (untuk Σ 50+70+100)
            $table->string('judge_meshpan', 8)->default('NG');      // OK/NG (280+PAN)

            $table->timestamps();

            $table->index('batch_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_total_gfn');
    }
};
