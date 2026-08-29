<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = ['guru_id', 'kelas_id', 'mapel', 'hari', 'jam_mulai', 'jam_selesai'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
