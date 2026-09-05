<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = ['kunci', 'nilai'];

    public const BONUS_KELAS_GABUNGAN = 'bonus_kelas_gabungan';
    public const NOMINAL_SISWA_ABSEN = 'nominal_siswa_absen';
    public const PERSEN_TABUNGAN = 'persen_tabungan';

    /** Cache per-request; nilai pengaturan dibaca berulang saat merender halaman presensi. */
    private static ?array $cache = null;

    public static function ambil(string $kunci, int $default = 0): int
    {
        static::$cache ??= static::pluck('nilai', 'kunci')->all();

        return isset(static::$cache[$kunci]) ? (int) static::$cache[$kunci] : $default;
    }

    public static function simpan(string $kunci, int|string $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => (string) $nilai]);
        static::$cache = null;
    }
}
