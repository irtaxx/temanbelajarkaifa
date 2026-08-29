<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateGaji extends Model
{
    use HasFactory;

    protected $fillable = ['jenjang', 'min_siswa', 'max_siswa', 'rate_per_sesi'];

    public static function cariRate(string $jenjang, int $jumlahSiswa): ?self
    {
        return static::where('jenjang', $jenjang)
            ->where('min_siswa', '<=', $jumlahSiswa)
            ->where('max_siswa', '>=', $jumlahSiswa)
            ->first();
    }
}
