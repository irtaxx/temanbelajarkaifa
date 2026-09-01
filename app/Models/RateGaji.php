<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateGaji extends Model
{
    use HasFactory;

    protected $fillable = ['jenjang', 'min_siswa', 'max_siswa', 'rate_per_sesi'];

    /**
     * Cache per-request. Nominal dihitung berkali-kali saat merender halaman presensi,
     * jadi tabel rate cukup dibaca sekali saja.
     */
    private static ?Collection $cache = null;

    public static function cariRate(string $jenjang, int $jumlahSiswa): ?self
    {
        static::$cache ??= static::all();

        return static::$cache->first(
            fn (self $rate) => $rate->jenjang === $jenjang
                && $jumlahSiswa >= $rate->min_siswa
                && $jumlahSiswa <= $rate->max_siswa
        );
    }

    protected static function booted(): void
    {
        // Cache dibuang saat data rate berubah agar tidak basi dalam satu request.
        static::saved(fn () => static::$cache = null);
        static::deleted(fn () => static::$cache = null);
    }
}
