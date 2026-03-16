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
        Schema::create('perizinan', function (Blueprint $table) {
    $table->id('id_perizinan');

    $table->integer('nis'); 
    $table->enum('jenis', ['sakit','izin']);

    $table->text('keterangan');

    $table->unsignedInteger('id_guru');

    $table->string('file')->nullable();

    $table->timestamps();

    $table->foreign('nis')
          ->references('nis')
          ->on('siswa')
          ->onDelete('cascade');

    $table->foreign('id_guru')
          ->references('id_user')
          ->on('users')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinan');
    }
};
