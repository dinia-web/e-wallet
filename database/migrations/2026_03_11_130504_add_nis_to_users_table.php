<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'nis')) {

            Schema::table('users', function (Blueprint $table) {
                $table->integer('nis')->nullable()->after('id_user');

                $table->foreign('nis')
                      ->references('nis')
                      ->on('siswa')
                      ->onDelete('cascade');
            });

        }
    }

    public function down(): void
{
    if (Schema::hasColumn('users', 'nis')) {

        Schema::table('users', function (Blueprint $table) {

            // hapus foreign key jika ada
            try {
                $table->dropForeign('users_nis_foreign');
            } catch (\Exception $e) {
                // abaikan jika tidak ada
            }

            $table->dropColumn('nis');
        });

    }
}
};