<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $periode = $this->periode($request);
        $persenTabungan = Pengaturan::ambil(Pengaturan::PERSEN_TABUNGAN, 10);

        $rekap = Guru::query()
            ->withCount(['presensis as total_sesi_hadir' => fn ($q) => $q
                ->where('status', 'hadir')
                ->whereBetween('tanggal', [$periode['mulai'], $periode['selesai']]),
            ])
            ->withSum(['presensis as total_gaji' => fn ($q) => $q
                ->where('status', 'hadir')
                ->whereBetween('tanggal', [$periode['mulai'], $periode['selesai']]),
            ], 'nominal_gaji')
            ->orderBy('nama')
            ->get()
            ->map(function (Guru $guru) use ($persenTabungan) {
                $total = (int) ($guru->total_gaji ?? 0);
                // Dibulatkan ke bawah agar tabungan tidak melebihi haknya karena pembulatan.
                $guru->tabungan = (int) floor($total * $persenTabungan / 100);
                $guru->gaji_diterima = $total - $guru->tabungan;

                return $guru;
            });

        return view('penggajian.index', [
            'rekap' => $rekap,
            'periode' => $periode,
            'semesters' => Semester::orderByDesc('tanggal_mulai')->get(),
            'persenTabungan' => $persenTabungan,
            'totalGaji' => $rekap->sum('total_gaji'),
            'totalTabungan' => $rekap->sum('tabungan'),
            'totalDiterima' => $rekap->sum('gaji_diterima'),
            'totalSesi' => $rekap->sum('total_sesi_hadir'),
        ]);
    }

    public function detail(Request $request, Guru $guru)
    {
        $periode = $this->periode($request);
        $persenTabungan = Pengaturan::ambil(Pengaturan::PERSEN_TABUNGAN, 10);

        $presensis = Presensi::with(['jadwal.kelas'])
            ->where('guru_id', $guru->id)
            ->where('status', 'hadir')
            ->whereBetween('tanggal', [$periode['mulai'], $periode['selesai']])
            ->orderBy('tanggal')
            ->get();

        $total = (int) $presensis->sum('nominal_gaji');
        $tabungan = (int) floor($total * $persenTabungan / 100);

        return view('penggajian.detail', [
            'guru' => $guru,
            'presensis' => $presensis,
            'total' => $total,
            'tabungan' => $tabungan,
            'diterima' => $total - $tabungan,
            'persenTabungan' => $persenTabungan,
            'periode' => $periode,
        ]);
    }

    /**
     * Menentukan rentang tanggal rekap dari parameter query.
     *
     * mode=bulan    : satu bulan (bulan, tahun)
     * mode=rentang  : beberapa bulan (dari_bulan/dari_tahun s.d. sampai_bulan/sampai_tahun)
     * mode=semester : mengikuti tanggal mulai & selesai semester terpilih
     */
    private function periode(Request $request): array
    {
        $mode = in_array($request->query('mode'), ['bulan', 'rentang', 'semester'], true)
            ? $request->query('mode')
            : 'bulan';

        if ($mode === 'semester') {
            $semester = Semester::find($request->query('semester_id'))
                ?? Semester::berjalan()
                ?? Semester::orderByDesc('tanggal_mulai')->first();

            if ($semester) {
                return [
                    'mode' => 'semester',
                    'mulai' => $semester->tanggal_mulai->copy()->startOfDay(),
                    'selesai' => $semester->tanggal_selesai->copy()->endOfDay(),
                    'label' => 'Semester '.$semester->label,
                    'semester_id' => $semester->id,
                ] + $this->paramBulanDefault();
            }

            // Belum ada data semester — jatuh kembali ke mode bulan.
            $mode = 'bulan';
        }

        if ($mode === 'rentang') {
            $dariBulan = $this->angka($request->query('dari_bulan'), 1, 12, (int) now()->month);
            $dariTahun = $this->angka($request->query('dari_tahun'), 2000, 2100, (int) now()->year);
            $sampaiBulan = $this->angka($request->query('sampai_bulan'), 1, 12, (int) now()->month);
            $sampaiTahun = $this->angka($request->query('sampai_tahun'), 2000, 2100, (int) now()->year);

            $mulai = Carbon::create($dariTahun, $dariBulan, 1)->startOfMonth();
            $selesai = Carbon::create($sampaiTahun, $sampaiBulan, 1)->endOfMonth();

            // Kalau terbalik, tukar supaya rentangnya tetap masuk akal.
            if ($selesai->lt($mulai)) {
                [$mulai, $selesai] = [$selesai->copy()->startOfMonth(), $mulai->copy()->endOfMonth()];
            }

            return [
                'mode' => 'rentang',
                'mulai' => $mulai,
                'selesai' => $selesai,
                'label' => $mulai->translatedFormat('F Y').' – '.$selesai->translatedFormat('F Y'),
                'dari_bulan' => $dariBulan,
                'dari_tahun' => $dariTahun,
                'sampai_bulan' => $sampaiBulan,
                'sampai_tahun' => $sampaiTahun,
                'semester_id' => null,
                'bulan' => (int) now()->month,
                'tahun' => (int) now()->year,
            ];
        }

        $bulan = $this->angka($request->query('bulan'), 1, 12, (int) now()->month);
        $tahun = $this->angka($request->query('tahun'), 2000, 2100, (int) now()->year);
        $mulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();

        return [
            'mode' => 'bulan',
            'mulai' => $mulai,
            'selesai' => $mulai->copy()->endOfMonth(),
            'label' => $mulai->translatedFormat('F Y'),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semester_id' => null,
        ] + $this->paramRentangDefault();
    }

    private function paramBulanDefault(): array
    {
        return [
            'bulan' => (int) now()->month,
            'tahun' => (int) now()->year,
        ] + $this->paramRentangDefault();
    }

    private function paramRentangDefault(): array
    {
        return [
            'dari_bulan' => (int) now()->month,
            'dari_tahun' => (int) now()->year,
            'sampai_bulan' => (int) now()->month,
            'sampai_tahun' => (int) now()->year,
        ];
    }

    private function angka($nilai, int $min, int $max, int $default): int
    {
        $angka = (int) $nilai;

        return ($angka >= $min && $angka <= $max) ? $angka : $default;
    }
}
