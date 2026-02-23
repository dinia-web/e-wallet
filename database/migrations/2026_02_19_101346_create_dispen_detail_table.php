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
       Schema::create('dispen_detail', function (Blueprint $table) {
    $table->id();

    $table->unsignedInteger('id_dispen');
    $table->integer('nis');
    $table->string('nama');

    $table->timestamps();

    // FK ke dispen
    $table->foreign('id_dispen')
        ->references('id_dispen')
        ->on('dispen')
        ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispen_detail');
    }
};
