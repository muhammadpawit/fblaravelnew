<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique();
            $table->string('foto', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_activity')->nullable();
            $table->integer('hak_akses')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'foto', 'last_login', 'last_activity', 'hak_akses', 'deleted_at']);
        });
    }
};
