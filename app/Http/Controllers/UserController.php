<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {   
        $limit = $request->limit ?? 5;

        $users = User::when($request->search, function ($query) use ($request) {
                $query->where('username', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('role', 'like', '%' . $request->search . '%');
                    
            })
            ->orderBy('id_user', 'asc')
            ->paginate($limit)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:admin,guru',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ], [
        'username.required' => 'Username wajib diisi',
        'username.unique' => 'Username sudah digunakan',
        'email.required' => 'Email wajib diisi',
        'email.unique' => 'Email sudah digunakan',
        'password.required' => 'Password wajib diisi',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'foto.image' => 'File harus berupa gambar',
        'foto.mimes' => 'Format foto harus jpg, jpeg, atau png',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    $fotoPath = null;

    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('users', 'public');
    }

    User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'foto' => $fotoPath
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User berhasil ditambahkan'
    ]);
}

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'role' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {

            // hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->foto = $request->file('foto')->store('users', 'public');
        }

        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // hapus foto jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}