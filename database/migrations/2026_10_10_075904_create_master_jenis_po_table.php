<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_jenis_po', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis_po', 100)->unique();
            $table->tinyInteger('status');
            $table->decimal('perkalian', 12, 4);
            $table->decimal('harga_packing', 15, 2)->default(0);
            $table->decimal('cucian_finishing', 15, 2)->default(0);
            $table->decimal('cucian_finishing_luar', 15, 2)->default(0);
            $table->decimal('buangbenang', 15, 2)->default(0);
            $table->decimal('lobangkancing', 15, 2)->default(0);
            $table->decimal('pasangkancing', 15, 2)->default(0);
            $table->decimal('tress', 15, 2)->default(0);
            $table->boolean('tampil')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_jenis_po');
    }
};
