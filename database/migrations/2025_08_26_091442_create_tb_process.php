<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_process', function (Blueprint $t) {
            $t->bigIncrements('id');

            //HEADER
            $t->dateTime('process_date');                        // tanggal cek
            $t->enum('shift', ['D','S','N']);             // shift
            $t->unsignedTinyInteger('plant')->default(0);   // 0 = SJH, 1 = ACE LINE
            $t->unsignedSmallInteger('mm_no');              // 1 = MM1, 2 = MM2
            $t->unsignedInteger('mix_no');                  // Mix Ke
            $t->dateTime('mix_start')->nullable();
            $t->dateTime('mix_finish')->nullable();
            //MM SAMPLE
            $t->decimal('sample_p',      7, 2)->nullable();
            $t->decimal('sample_c',      7, 2)->nullable();
            $t->decimal('sample_gt',     7, 2)->nullable();
            $t->decimal('cb_mm',         7, 2)->nullable();
            $t->decimal('cb_lab',        7, 2)->nullable();
            $t->decimal('sample_m',      7, 2)->nullable();
            $t->decimal('bakunetsu',     7, 2)->nullable();
            $t->decimal('sample_ac',     7, 2)->nullable();
            $t->decimal('sample_tc',     7, 2)->nullable();
            $t->decimal('vsd_mm',        7, 2)->nullable();
            $t->decimal('sample_ig',     7, 2)->nullable();
            $t->decimal('cb_weight',     7, 2)->nullable();
            $t->decimal('tp50_weight',   7, 2)->nullable();
            $t->decimal('ssi',           7, 2)->nullable();
            //ADDITIVE ADDITIONAL
            $t->decimal('additive_m3',   7, 2)->nullable();
            $t->decimal('additive_vsd',  7, 2)->nullable();
            $t->decimal('additive_sc',   7, 2)->nullable();
            //BC SAMPLE (gabungan BC12/BC11/BC16)
            $t->decimal('bc12_cb',       7, 2)->nullable();
            $t->decimal('bc12_m',        7, 2)->nullable();
            $t->decimal('bc11_ac',       7, 2)->nullable();
            $t->decimal('bc11_vsd',      7, 2)->nullable();
            $t->decimal('bc16_cb',       7, 2)->nullable();
            $t->decimal('bc16_m',        7, 2)->nullable();
            //RETURN SAND 
            $t->dateTime('return_time')->nullable();          // waktu pengukuran RS
            $t->string('model_type', 20)->nullable();         // dropdown: WIP, ES01, ...
            $t->decimal('moisture_bc9',   7, 2)->nullable();
            $t->decimal('moisture_bc10',  7, 2)->nullable();
            $t->decimal('moisture_bc11',  7, 2)->nullable();
            $t->decimal('temp_bc9',       7, 2)->nullable();
            $t->decimal('temp_bc10',      7, 2)->nullable();
            $t->decimal('temp_bc11',      7, 2)->nullable();
            //AUDIT 
            $t->timestamps();
            //INDEX & UNIQUE 
            $t->unique(['process_date','shift','plant','mm_no','mix_no'], 'uniq_process');
            $t->index(['process_date','shift']);
            $t->index('plant');
            $t->index('mm_no');
            $t->index('model_type');
        });

        // (Opsional) CHECK constraint untuk plant 0/1 dan validasi waktu (MySQL 8+)
        try {
            DB::statement("ALTER TABLE tb_process
                ADD CONSTRAINT chk_plant_in_01 CHECK (plant IN (0,1))");
            DB::statement("ALTER TABLE tb_process
                ADD CONSTRAINT chk_mix_time CHECK (
                    (mix_start IS NULL OR mix_finish IS NULL) OR (mix_start < mix_finish)
                )");
        } catch (\Throwable $e) {
            // Abaikan jika versi MySQL tidak mendukung CHECK atau sudah ada
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_process');
    }
};
