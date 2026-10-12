<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_kode_po', function (Blueprint $table) {
            $table->id();
            $table->string('kode_po', 50)->unique();
            $table->string('nama_po', 100);
            $table->string('jenis_po', 100);
            $table->string('model_po', 100)->nullable();
            $table->date('tanggal_produksi');
            $table->string('kategori_po', 50)->default('DALAM');
            $table->string('kode_artikel', 50)->nullable();
            $table->string('jenis_ukuran', 50)->default('Biasa');
            $table->string('type', 50)->default('Biasa');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kode_po');
    }
};
