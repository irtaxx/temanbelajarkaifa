<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id', 'guru_id', 'tanggal', 'status',
        'siswa_hadir', 'kelas_gabungan', 'keterangan', 'nominal_gaji',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'siswa_hadir' => 'boolean',
        'kelas_gabungan' => 'boolean',
    ];

    /**
     * Skenario presensi yang menentukan nominal per sesi.
     * Tiap skenario memetakan ke kombinasi status guru, kehadiran siswa, dan kelas gabungan.
     */
    public const SKENARIO = [
        'hadir' => ['label' => 'Hadir', 'status' => 'hadir', 'siswa_hadir' => true, 'kelas_gabungan' => false],
        'gabungan' => ['label' => 'Gabungan', 'status' => 'hadir', 'siswa_hadir' => true, 'kelas_gabungan' => true],
        'siswa_absen' => ['label' => 'Siswa absen', 'status' => 'hadir', 'siswa_hadir' => false, 'kelas_gabungan' => false],
        'izin' => ['label' => 'Izin', 'status' => 'izin', 'siswa_hadir' => false, 'kelas_gabungan' => false],
        'sakit' => ['label' => 'Sakit', 'status' => 'sakit', 'siswa_hadir' => false, 'kelas_gabungan' => false],
        'alpha' => ['label' => 'Alpha', 'status' => 'alpha', 'siswa_hadir' => false, 'kelas_gabungan' => false],
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Cari presensi satu sesi. Wajib memakai whereDate: kolom tanggal tersimpan
     * dengan komponen jam (00:00:00), sehingga pencocokan string biasa meleset
     * dan updateOrCreate akan mencoba INSERT lalu menabrak unique constraint.
     */
    public static function untukSesi(int $jadwalId, string $tanggal): ?self
    {
        return static::where('jadwal_id', $jadwalId)
            ->whereDate('tanggal', $tanggal)
            ->first();
    }

    /**
     * Skenario yang sedang tersimpan pada baris ini, untuk menandai tombol aktif.
     */
    public function getSkenarioAttribute(): string
    {
        if ($this->status !== 'hadir') {
            return $this->status;
        }

        if (! $this->siswa_hadir) {
            return 'siswa_absen';
        }

        return $this->kelas_gabungan ? 'gabungan' : 'hadir';
    }

    /**
     * Hitung nominal satu sesi berdasarkan skenario dan rate kelas.
     *
     * - Guru dan siswa masuk                  : rate penuh
     * - Guru dan siswa masuk, kelas gabungan  : rate penuh + bonus gabungan
     * - Guru masuk, siswa tidak masuk         : nominal tetap (tidak melihat rate)
     * - Guru tidak masuk                      : 0
     */
    public static function hitungNominal(string $skenario, Kelas $kelas): int
    {
        $aturan = static::SKENARIO[$skenario] ?? null;

        if ($aturan === null || $aturan['status'] !== 'hadir') {
            return 0;
        }

        if (! $aturan['siswa_hadir']) {
            return Pengaturan::ambil(Pengaturan::NOMINAL_SISWA_ABSEN);
        }

        $rate = RateGaji::cariRate($kelas->jenjang, $kelas->jumlah_siswa);
        $nominal = $rate?->rate_per_sesi ?? 0;

        if ($aturan['kelas_gabungan']) {
            $nominal += Pengaturan::ambil(Pengaturan::BONUS_KELAS_GABUNGAN);
        }

        return $nominal;
    }
}
