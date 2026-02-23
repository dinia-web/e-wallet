<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jampel', function (Blueprint $table) {
            $table->increments('id_jampel');
            $table->string('jam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jampel');
    }
};
