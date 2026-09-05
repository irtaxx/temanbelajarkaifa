<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->enum('nama', ['Ganjil', 'Genap']);
            $table->string('tahun_ajar', 9);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();

            $table->unique(['nama', 'tahun_ajar']);
        });

        // Isi awal untuk tahun ajar berjalan dengan rentang tanggal umum,
        // supaya opsi "per semester" langsung bisa dipakai. Tanggalnya bisa
        // disesuaikan admin lewat menu Semester.
        $tahun = (int) now()->year;
        $tahunAwal = now()->month >= 7 ? $tahun : $tahun - 1;
        $tahunAjar = $tahunAwal.'/'.($tahunAwal + 1);

        DB::table('semesters')->insert([
            [
                'nama' => 'Ganjil',
                'tahun_ajar' => $tahunAjar,
                'tanggal_mulai' => $tahunAwal.'-07-01',
                'tanggal_selesai' => $tahunAwal.'-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Genap',
                'tahun_ajar' => $tahunAjar,
                'tanggal_mulai' => ($tahunAwal + 1).'-01-01',
                'tanggal_selesai' => ($tahunAwal + 1).'-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
