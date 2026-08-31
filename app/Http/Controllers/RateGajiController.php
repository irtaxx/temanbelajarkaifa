<?php

namespace App\Http\Controllers;

use App\Models\RateGaji;
use Illuminate\Http\Request;

class RateGajiController extends Controller
{
    public function index()
    {
        $rates = RateGaji::orderBy('jenjang')->orderBy('min_siswa')->get();

        return view('rate-gaji.index', compact('rates'));
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
