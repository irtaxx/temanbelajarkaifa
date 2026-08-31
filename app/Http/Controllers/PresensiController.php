<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\RateGaji;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    private const HARI_INDONESIA = [
        0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];

    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal') ?: now()->toDateString();
        $tanggalCarbon = Carbon::parse($tanggal);
        $hari = self::HARI_INDONESIA[$tanggalCarbon->dayOfWeek];
        $filterGuru = $request->query('guru_id', '');

        // Semua jadwal ditampilkan, tidak dibatasi hari terjadwalnya —
        // admin bebas mencatat sesi yang benar-benar berlangsung pada tanggal mana pun.
        // Jadwal yang memang terjadwal di hari tersebut ditaruh paling atas.
        // whereDate dipakai karena kolom tanggal tersimpan dengan komponen jam (00:00:00),
        // sehingga perbandingan string biasa tidak cocok di SQLite.
        $jadwals = Jadwal::with(['guru', 'kelas', 'presensis' => fn ($q) => $q->whereDate('tanggal', $tanggal)])
            ->when($filterGuru !== '', fn ($q) => $q->where('guru_id', $filterGuru))
            ->orderByRaw('CASE WHEN hari = ? THEN 0 ELSE 1 END', [$hari])
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai')
            ->get();

        $gurus = Guru::where('status', 'aktif')->orderBy('nama')->get();

        return view('presensi.index', compact('jadwals', 'tanggal', 'hari', 'gurus', 'filterGuru'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwals,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
        ]);

        $jadwal = Jadwal::with('kelas')->findOrFail($data['jadwal_id']);

        $nominal = null;
        if ($data['status'] === 'hadir') {
            $rate = RateGaji::cariRate($jadwal->kelas->jenjang, $jadwal->kelas->jumlah_siswa);
            $nominal = $rate?->rate_per_sesi;
        }

        Presensi::updateOrCreate(
            ['jadwal_id' => $data['jadwal_id'], 'tanggal' => $data['tanggal']],
            ['guru_id' => $jadwal->guru_id, 'status' => $data['status'], 'nominal_gaji' => $nominal]
        );

        return redirect()->route('presensi.index', array_filter([
            'tanggal' => $data['tanggal'],
            'guru_id' => $request->input('guru_id'),
        ]))->with('status', 'Presensi tersimpan.');
    }

    public function destroy(Request $request, Presensi $presensi)
    {
        $tanggal = $presensi->tanggal->toDateString();
        $presensi->delete();

        return redirect()->route('presensi.index', array_filter([
            'tanggal' => $tanggal,
            'guru_id' => $request->input('guru_id'),
        ]))->with('status', 'Presensi dibatalkan.');
    }
}
