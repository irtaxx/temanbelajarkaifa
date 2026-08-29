<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['guru', 'kelas'])
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai')
            ->paginate(20);

        return view('jadwals.index', compact('jadwals'));
    }

    public function create()
    {
        $gurus = Guru::where('status', 'aktif')->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('jadwals.create', compact('gurus', 'kelas'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Jadwal::create($data);

        return redirect()->route('jadwals.index')->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $gurus = Guru::where('status', 'aktif')->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('jadwals.edit', compact('jadwal', 'gurus', 'kelas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $data = $this->validated($request);

        $jadwal->update($data);

        return redirect()->route('jadwals.index')->with('status', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwals.index')->with('status', 'Jadwal berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'guru_id' => ['required', 'exists:gurus,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mapel' => ['nullable', 'string', 'max:255'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);
    }
}
