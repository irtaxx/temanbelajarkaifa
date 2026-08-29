<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('jenjang')->orderBy('nama_kelas')->paginate(15);

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'in:SD,SMP,SMA'],
            'jumlah_siswa' => ['required', 'integer', 'min:0'],
        ]);

        Kelas::create($data);

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'in:SD,SMP,SMA'],
            'jumlah_siswa' => ['required', 'integer', 'min:0'],
        ]);

        $kelas->update($data);

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('kelas.index')->with('status', 'Data kelas berhasil dihapus.');
    }
}
