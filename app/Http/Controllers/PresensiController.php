<?php

namespace App\Http\Controllers;

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

        $jadwals = Jadwal::with(['guru', 'kelas', 'presensis' => function ($q) use ($tanggal) {
            $q->where('tanggal', $tanggal);
        }])
            ->where('hari', $hari)
            ->orderBy('jam_mulai')
            ->get();

        return view('presensi.index', compact('jadwals', 'tanggal', 'hari'));
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

        return redirect()->route('presensi.index', ['tanggal' => $data['tanggal']])
            ->with('status', 'Presensi tersimpan.');
    }
}
