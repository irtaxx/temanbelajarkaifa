<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        // Hanya jadwal yang harinya cocok dengan tanggal terpilih. Admin tetap bebas
        // memilih tanggal mana pun (lampau atau mendatang), tapi daftarnya mengikuti
        // jadwal hari itu supaya tidak tercampur sesi hari lain.
        //
        // whereDate dipakai karena kolom tanggal tersimpan dengan komponen jam (00:00:00),
        // sehingga perbandingan string biasa tidak cocok di SQLite.
        $sesuaiHari = Jadwal::with(['guru', 'kelas', 'presensis' => fn ($q) => $q->whereDate('tanggal', $tanggal)])
            ->where('hari', $hari)
            ->when($filterGuru !== '', fn ($q) => $q->where('guru_id', $filterGuru))
            ->orderBy('jam_mulai')
            ->get();

        // Kelas hanya berjalan di dalam rentang semesternya. Penyaringan dilakukan di
        // PHP (bukan query) supaya jadwal yang tersembunyi tetap bisa dihitung dan
        // alasannya bisa dijelaskan ke admin — daripada halaman kosong tanpa keterangan.
        $rentangSemester = Semester::all()
            ->keyBy(fn (Semester $s) => $s->nama.'|'.$s->tahun_ajar);

        $jadwals = $sesuaiHari->filter(function (Jadwal $j) use ($rentangSemester, $tanggalCarbon) {
            $semester = $rentangSemester->get($j->kelas->semester.'|'.$j->kelas->tahun_ajar);

            return $semester
                && $tanggalCarbon->betweenIncluded($semester->tanggal_mulai, $semester->tanggal_selesai);
        })->values();

        $jumlahDiluarSemester = $sesuaiHari->count() - $jadwals->count();

        return view('presensi.index', [
            'jadwals' => $jadwals,
            'jumlahDiluarSemester' => $jumlahDiluarSemester,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'gurus' => Guru::where('status', 'aktif')->orderBy('nama')->get(),
            'filterGuru' => $filterGuru,
            'bonusGabungan' => Pengaturan::ambil(Pengaturan::BONUS_KELAS_GABUNGAN),
            'nominalSiswaAbsen' => Pengaturan::ambil(Pengaturan::NOMINAL_SISWA_ABSEN),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwals,id'],
            'tanggal' => ['required', 'date'],
            'skenario' => ['required', Rule::in(array_keys(Presensi::SKENARIO))],
        ]);

        $jadwal = Jadwal::with('kelas')->findOrFail($data['jadwal_id']);
        $aturan = Presensi::SKENARIO[$data['skenario']];

        $isian = [
            'guru_id' => $jadwal->guru_id,
            'status' => $aturan['status'],
            'siswa_hadir' => $aturan['siswa_hadir'],
            'kelas_gabungan' => $aturan['kelas_gabungan'],
            'nominal_gaji' => Presensi::hitungNominal($data['skenario'], $jadwal->kelas),
        ];

        // updateOrCreate tidak dipakai karena pencocokan kolom tanggal harus lewat
        // whereDate (lihat Presensi::untukSesi), bukan perbandingan string biasa.
        $presensi = Presensi::untukSesi($data['jadwal_id'], $data['tanggal']);

        if ($presensi) {
            $presensi->update($isian);
        } else {
            Presensi::create($isian + [
                'jadwal_id' => $data['jadwal_id'],
                'tanggal' => $data['tanggal'],
            ]);
        }

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
