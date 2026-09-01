<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->string('nilai');
            $table->timestamps();
        });

        // Nilai awal sesuai skema penggajian yang berlaku; bisa diubah dari halaman Rate Gaji.
        DB::table('pengaturans')->insert([
            [
                'kunci' => 'bonus_kelas_gabungan',
                'nilai' => '5000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci' => 'nominal_siswa_absen',
                'nilai' => '10000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
