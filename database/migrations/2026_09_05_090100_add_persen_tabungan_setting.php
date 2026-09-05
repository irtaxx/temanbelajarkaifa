<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('pengaturans')->where('kunci', 'persen_tabungan')->exists()) {
            DB::table('pengaturans')->insert([
                'kunci' => 'persen_tabungan',
                'nilai' => '10',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('pengaturans')->where('kunci', 'persen_tabungan')->delete();
    }
};
