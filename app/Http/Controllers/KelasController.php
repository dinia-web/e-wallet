<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
{
    $limit = $request->limit ?? 5; // ambil limit dari dropdown

    $kelas = Kelas::when($request->search, function ($query) use ($request) {
            $query->where('klas', 'like', '%' . $request->search . '%');
        })
        ->orderBy('id_kelas', 'asc')
        ->paginate($limit)
        ->withQueryString();

    return view('kelas.index', compact('kelas'));
}

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'klas' => 'required|string'
        ]);

        Kelas::create([
            'klas' => $request->klas
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'klas' => 'required|string'
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update(['klas' => $request->klas]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
