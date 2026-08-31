<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tahun ajar berjalan untuk mengisi data kelas yang sudah ada.
        // Tahun ajar baru dianggap mulai bulan Juli.
        $tahun = (int) now()->year;
        $tahunAjarBerjalan = now()->month >= 7
            ? $tahun.'/'.($tahun + 1)
            : ($tahun - 1).'/'.$tahun;

        Schema::table('kelas', function (Blueprint $table) use ($tahunAjarBerjalan) {
            $table->enum('semester', ['Ganjil', 'Genap'])->default('Ganjil')->after('jenjang');
            $table->string('tahun_ajar', 9)->default($tahunAjarBerjalan)->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajar']);
        });
    }
};
