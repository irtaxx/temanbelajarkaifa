<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\RateGaji;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $filterTahunAjar = $request->query('tahun_ajar', '');
        $filterSemester = $request->query('semester', '');

        $kelas = Kelas::query()
            ->when($filterTahunAjar !== '', fn ($q) => $q->where('tahun_ajar', $filterTahunAjar))
            ->when($filterSemester !== '', fn ($q) => $q->where('semester', $filterSemester))
            ->orderByDesc('tahun_ajar')
            ->orderBy('semester')
            ->orderBy('jenjang')
            ->orderBy('nama_kelas')
            ->paginate(15)
            ->withQueryString();

        $daftarTahunAjar = Kelas::query()
            ->select('tahun_ajar')
            ->distinct()
            ->orderByDesc('tahun_ajar')
            ->pluck('tahun_ajar');

        return view('kelas.index', compact('kelas', 'daftarTahunAjar', 'filterTahunAjar', 'filterSemester'));
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['jadwals' => fn ($q) => $q
            ->with('guru')
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai'),
        ]);

        return view('kelas.show', [
            'kelas' => $kelas,
            'gurus' => Guru::where('status', 'aktif')->orderBy('nama')->get(),
            'rate' => RateGaji::cariRate($kelas->jenjang, $kelas->jumlah_siswa),
        ]);
    }

    public function store(Request $request)
    {
        Kelas::create($this->validated($request));

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $kelas->update($this->validated($request));

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'in:SD,SMP,SMA'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'tahun_ajar' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'jumlah_siswa' => ['required', 'integer', 'min:0'],
        ], [
            'tahun_ajar.regex' => 'Format tahun ajar harus seperti 2025/2026.',
        ]);
    }
}
