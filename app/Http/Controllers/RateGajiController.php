<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\RateGaji;
use Illuminate\Http\Request;

class RateGajiController extends Controller
{
    public function index()
    {
        $rates = RateGaji::orderBy('jenjang')->orderBy('min_siswa')->get();

        return view('rate-gaji.index', [
            'rates' => $rates,
            'bonusGabungan' => Pengaturan::ambil(Pengaturan::BONUS_KELAS_GABUNGAN),
            'nominalSiswaAbsen' => Pengaturan::ambil(Pengaturan::NOMINAL_SISWA_ABSEN),
        ]);
    }

    public function simpanPengaturan(Request $request)
    {
        $data = $request->validate([
            'bonus_kelas_gabungan' => ['required', 'integer', 'min:0'],
            'nominal_siswa_absen' => ['required', 'integer', 'min:0'],
        ]);

        Pengaturan::simpan(Pengaturan::BONUS_KELAS_GABUNGAN, $data['bonus_kelas_gabungan']);
        Pengaturan::simpan(Pengaturan::NOMINAL_SISWA_ABSEN, $data['nominal_siswa_absen']);

        return redirect()->route('rate-gaji.index')
            ->with('status', 'Pengaturan nominal berhasil disimpan. Presensi yang sudah tercatat tidak ikut berubah.');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        RateGaji::create($data);

        return redirect()->route('rate-gaji.index')->with('status', 'Rate gaji berhasil ditambahkan.');
    }

    public function update(Request $request, RateGaji $rateGaji)
    {
        $data = $this->validated($request);

        $rateGaji->update($data);

        return redirect()->route('rate-gaji.index')->with('status', 'Rate gaji berhasil diperbarui.');
    }

    public function destroy(RateGaji $rateGaji)
    {
        $rateGaji->delete();

        return redirect()->route('rate-gaji.index')->with('status', 'Rate gaji berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'jenjang' => ['required', 'in:SD,SMP,SMA'],
            'min_siswa' => ['required', 'integer', 'min:0'],
            'max_siswa' => ['required', 'integer', 'gte:min_siswa'],
            'rate_per_sesi' => ['required', 'integer', 'min:0'],
        ]);
    }
}
