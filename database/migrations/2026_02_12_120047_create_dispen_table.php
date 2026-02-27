<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('dispen', function (Blueprint $table) {

                $table->increments('id_dispen'); // PRIMARY KEY

                $table->integer('nis'); // INT(11)
                $table->string('nama'); // VARCHAR(255)
                $table->string('kelas');

                $table->string('email'); // VARCHAR(255)

                $table->unsignedInteger('id_guru');
                
                $table->unsignedInteger('gurpi')->nullable();

                $table->string('alasan'); // VARCHAR(255)

                $table->enum('status', [
                    'dalam proses',
                    'disetujui',
                    'ditolak',
                    'menunggu persetujuan admin',
                    'menunggu persetujuan guru'
                ])->default('dalam proses');

                $table->unsignedInteger('approved_by_admin')->nullable();
                $table->unsignedInteger('approved_by_guru')->nullable();

                $table->enum('admin_action', ['setuju', 'tolak'])->nullable();
                $table->enum('guru_action', ['setuju', 'tolak'])->nullable();

                $table->text('rejection_reason');

                $table->timestamps();

                // INDEXES
                $table->index('gurpi');
                $table->index('approved_by_admin');
                $table->index('approved_by_guru');
            
                $table->foreign('id_guru')
                    ->references('id_user')
                    ->on('users')
                    ->onDelete('cascade');

                // FK approved admin
                $table->foreign('approved_by_admin')
                    ->references('id_user')
                    ->on('users')
                    ->nullOnDelete();

                // FK approved guru
                $table->foreign('approved_by_guru')
                    ->references('id_user')
                    ->on('users')
                    ->nullOnDelete();

                // FK guru piket
                $table->foreign('gurpi')
                    ->references('id_guru')
                    ->on('gurpik')
                    ->nullOnDelete();
                });
        }

        public function down(): void
        {
            Schema::dropIfExists('dispen');
        }
    };