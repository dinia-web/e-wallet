<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispen', function (Blueprint $table) {

            if (Schema::hasColumn('dispen', 'nis')) {
                $table->dropColumn('nis');
            }

            if (Schema::hasColumn('dispen', 'nama')) {
                $table->dropColumn('nama');
            }

            if (Schema::hasColumn('dispen', 'kelas')) {
                $table->dropColumn('kelas');
            }

        });
    }

    public function down(): void
    {
        Schema::table('dispen', function (Blueprint $table) {

            if (!Schema::hasColumn('dispen', 'nis')) {
                $table->integer('nis')->nullable();
            }

            if (!Schema::hasColumn('dispen', 'nama')) {
                $table->string('nama')->nullable();
            }

            if (!Schema::hasColumn('dispen', 'kelas')) {
                $table->string('kelas')->nullable();
            }

        });
    }
};