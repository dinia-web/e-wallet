<?php

namespace App\Http\Controllers;

use App\Models\Jampel;
use Illuminate\Http\Request;

class JampelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jampel = jampel::when($search, function ($query, $search) {
                        $query->where('jam', 'like', "%{$search}%");
                    })
                    ->orderBy('id_jampel', 'asc')
                    ->paginate(5)
                    ->withQueryString(); // biar query search tetap ada saat pindah halaman

        return view('jampel.index', compact('jampel'));
    }

    // Form tambah jam
    public function create()
    {
        return view('jampel.create');
    }

    // Simpan jam baru
    public function store(Request $request)
    {
        $request->validate([
            'jam' => 'required|string'
        ]);

        Jampel::create([
            'jam' => $request->jam
        ]);

        return redirect()->route('jampel.index')->with('success', 'Jam pelajaran berhasil ditambahkan!');
    }

    // Form edit jam
    public function edit($id)
    {
        $jampel = Jampel::findOrFail($id);
        return view('jampel.edit', compact('jampel'));
    }

    // Update jam
    public function update(Request $request, $id)
    {
        $request->validate([
            'jam' => 'required|string'
        ]);

        $jampel = Jampel::findOrFail($id);
        $jampel->update([
            'jam' => $request->jam
        ]);

        return redirect()->route('jampel.index')->with('success', 'Jam pelajaran berhasil diperbarui!');
    }

    // Hapus jam
    public function destroy($id)
    {
        $jampel = Jampel::findOrFail($id);
        $jampel->delete();

        return redirect()->route('jampel.index')->with('success', 'Jam pelajaran berhasil dihapus!');
    }
}