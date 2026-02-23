<?php

namespace App\Http\Controllers;

use App\Models\Gurpik;
use Illuminate\Http\Request;

class GurpikController extends Controller
{   
    public function index(Request $request)
{
    $search = $request->input('search');

    $gurpik = Gurpik::when($search, function ($query, $search) {
                    $query->where('gurpi', 'like', "%{$search}%");
                })
                ->orderBy('id_guru', 'asc')
                ->paginate(5)
                ->withQueryString(); // biar query search tetap ada saat pindah halaman

    return view('gurpik.index', compact('gurpik'));
}

    public function create()
    {
        return view('gurpik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gurpi' => 'required|string'
        ]);

        Gurpik::create([
            'gurpi' => $request->gurpi
        ]);

        return redirect()->route('gurpik.index')->with('success', 'Guru Piket berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $gurpik = Gurpik::findOrFail($id);
        return view('gurpik.edit', compact('gurpik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gurpi' => 'required|string'
        ]);

        $gurpik = Gurpik::findOrFail($id);
        $gurpik->update(['gurpi' => $request->gurpi]);

        return redirect()->route('gurpik.index')->with('success', 'Guru Piket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $gurpik = Gurpik::findOrFail($id);
        $gurpik->delete();

        return redirect()->route('gurpik.index')->with('success', 'Guru Piket berhasil dihapus!');
    }
}
