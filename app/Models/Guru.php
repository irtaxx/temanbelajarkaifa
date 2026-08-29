<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'no_hp', 'alamat', 'status'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
