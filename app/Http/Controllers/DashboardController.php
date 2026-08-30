<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Presensi;

class DashboardController extends Controller
{
    private const HARI_INDONESIA = [
        0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];

    public function index()
    {
        $hariIni = self::HARI_INDONESIA[now()->dayOfWeek];

        $sesiHariIni = Jadwal::where('hari', $hariIni)->count();
        $guruAktif = Guru::where('status', 'aktif')->count();

        $gajiBulanIni = Presensi::where('status', 'hadir')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal_gaji');

        $totalPresensiBulanIni = Presensi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $hadirBulanIni = Presensi::where('status', 'hadir')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $persenKehadiran = $totalPresensiBulanIni > 0
            ? round($hadirBulanIni / $totalPresensiBulanIni * 100)
            : 0;

        $presensiTerbaru = Presensi::with(['guru', 'jadwal.kelas'])
            ->latest('tanggal')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'sesiHariIni',
            'guruAktif',
            'gajiBulanIni',
            'persenKehadiran',
            'presensiTerbaru'
        ));
    }
}
