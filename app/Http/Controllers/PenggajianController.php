<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) ($request->query('bulan') ?: now()->month);
        $tahun = (int) ($request->query('tahun') ?: now()->year);

        $rekap = Guru::query()
            ->withCount(['presensis as total_sesi_hadir' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'hadir')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            }])
            ->withSum(['presensis as total_gaji' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'hadir')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            }], 'nominal_gaji')
            ->orderBy('nama')
            ->get();

        return view('penggajian.index', compact('rekap', 'bulan', 'tahun'));
    }

    public function detail(Request $request, Guru $guru)
    {
        $bulan = (int) ($request->query('bulan') ?: now()->month);
        $tahun = (int) ($request->query('tahun') ?: now()->year);

        $presensis = Presensi::with(['jadwal.kelas'])
            ->where('guru_id', $guru->id)
            ->where('status', 'hadir')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $total = $presensis->sum('nominal_gaji');

        return view('penggajian.detail', compact('guru', 'presensis', 'total', 'bulan', 'tahun'));
    }
}
