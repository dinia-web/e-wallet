<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispen', function (Blueprint $table) {
            $table->enum('tipe', ['individu', 'kelompok'])->default('individu');
            $table->string('kelas_kelompok')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dispen', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'kelas_kelompok']);
        });
    }
};