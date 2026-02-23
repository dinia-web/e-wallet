<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurpik', function (Blueprint $table) {
            $table->increments('id_guru');
            $table->string('gurpi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurpik');
    }
};
