<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_cmt', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cmt', 50);
            $table->string('cmt_name', 100);
            $table->enum('cmt_job_desk', ['JAHIT', 'SABLON', 'BORDIR']);
            $table->string('alamat', 255)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('an', 100)->nullable();
            $table->string('norek', 100)->nullable();
            $table->string('bank', 100)->nullable();
            $table->decimal('ongkos_transport_perdz', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_cmt');
    }
};
