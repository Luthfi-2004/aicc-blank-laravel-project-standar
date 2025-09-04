<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_jsh_gfns', function (Blueprint $table) {
            $table->id(); // auto increment, ini jadi primary key

            $table->string('batch_code')->nullable();

            $table->date('gfn_date')->nullable();                 // tanggal input GFN
            $table->enum('shift', ['D', 'S', 'N'])->nullable();     // shift: Day, Swing, Night// grouping sekali input
            $table->string('mesh')->nullable();               // contoh: 18,5 / 26 / PAN
            $table->decimal('gram', 8, 2)->default(0);
            $table->decimal('percentage', 8, 2)->default(0);
            $table->integer('index')->nullable();
            $table->decimal('percentage_index', 10, 2)->default(0);

            // total
            $table->decimal('total_gram', 10, 2)->default(0);
            $table->decimal('total_percentage_index', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_jsh_gfns');
    }
};
