<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
   public function index(Request $request)
{
    $limit = $request->get('limit', 5); // default 5
    $search = $request->input('search');

    $siswa = Siswa::when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('kelas', 'like', "%{$search}%")
                          ->orWhere('nis', 'like', "%{$search}%");
                    });
                })
                ->orderBy('nis', 'asc')
                ->paginate($limit)   // ✅ pakai $limit
                ->withQueryString(); // supaya limit & search tidak hilang

    return view('siswa.index', compact('siswa'));
}

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|unique:siswa,nis',
            'nama' => 'required|string',
            'kelas' => 'required|string'
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit($nis)
    {
        $siswa = Siswa::findOrFail($nis);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $nis)
    {
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|string'
        ]);

        $siswa = Siswa::findOrFail($nis);
        $siswa->update($request->all());

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil diperbarui!');
    }

    public function destroy($nis)
    {
        $siswa = Siswa::findOrFail($nis);
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }
}