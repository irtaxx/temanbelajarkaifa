<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // Default mengikuti perilaku lama: guru hadir berarti siswa juga masuk
            // dan kelas tidak digabung, sehingga nominal presensi lama tidak berubah.
            $table->boolean('siswa_hadir')->default(true)->after('status');
            $table->boolean('kelas_gabungan')->default(false)->after('siswa_hadir');
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['siswa_hadir', 'kelas_gabungan']);
        });
    }
};
