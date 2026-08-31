<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::orderBy('nama')->paginate(15);

        return view('gurus.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Guru::create($data);

        return redirect()->route('gurus.index')->with('status', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $guru->update($data);

        return redirect()->route('gurus.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return redirect()->route('gurus.index')->with('status', 'Data guru berhasil dihapus.');
    }
}
