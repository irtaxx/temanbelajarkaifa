<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['nama', 'tahun_ajar', 'tanggal_mulai', 'tanggal_selesai'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function getLabelAttribute(): string
    {
        return $this->nama.' '.$this->tahun_ajar;
    }

    /**
     * Semester yang sedang berjalan berdasarkan tanggal hari ini,
     * dipakai sebagai pilihan awal pada rekap penggajian.
     */
    public static function berjalan(): ?self
    {
        return static::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->first();
    }

    public static function terbaruDulu()
    {
        return static::orderByDesc('tanggal_mulai')->get();
    }
}
