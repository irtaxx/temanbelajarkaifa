<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'jenjang', 'semester', 'tahun_ajar', 'jumlah_siswa'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Tahun ajar berjalan, dianggap berganti tiap bulan Juli.
     */
    public static function tahunAjarBerjalan(): string
    {
        $tahun = (int) now()->year;

        return now()->month >= 7
            ? $tahun.'/'.($tahun + 1)
            : ($tahun - 1).'/'.$tahun;
    }

    /**
     * Pilihan tahun ajar: dua tahun ke belakang sampai satu tahun ke depan.
     */
    public static function opsiTahunAjar(): array
    {
        $tahunAwal = (int) substr(static::tahunAjarBerjalan(), 0, 4);

        return collect(range($tahunAwal - 2, $tahunAwal + 1))
            ->map(fn ($t) => $t.'/'.($t + 1))
            ->all();
    }
}
