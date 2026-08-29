<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_gajis', function (Blueprint $table) {
            $table->id();
            $table->enum('jenjang', ['SD', 'SMP', 'SMA']);
            $table->unsignedInteger('min_siswa');
            $table->unsignedInteger('max_siswa');
            $table->unsignedInteger('rate_per_sesi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_gajis');
    }
};
