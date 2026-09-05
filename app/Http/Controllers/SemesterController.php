<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        return view('semesters.index', [
            'semesters' => Semester::orderByDesc('tanggal_mulai')->get(),
            'opsiTahunAjar' => Kelas::opsiTahunAjar(),
        ]);
    }

    public function store(Request $request)
    {
        Semester::create($this->validated($request));

        return redirect()->route('semesters.index')->with('status', 'Semester berhasil ditambahkan.');
    }

    public function update(Request $request, Semester $semester)
    {
        $semester->update($this->validated($request, $semester));

        return redirect()->route('semesters.index')->with('status', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()->route('semesters.index')->with('status', 'Semester berhasil dihapus.');
    }

    private function validated(Request $request, ?Semester $semester = null): array
    {
        return $request->validate([
            'nama' => ['required', 'in:Ganjil,Genap'],
            'tahun_ajar' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ], [
            'tahun_ajar.regex' => 'Format tahun ajar harus seperti 2026/2027.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);
    }
}
