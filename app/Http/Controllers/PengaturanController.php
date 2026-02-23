<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pengaturan.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6',
            'konfirmasi_password' => 'required|same:password_baru',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui');
    }

   public function uploadFoto(Request $request)
{
    $request->validate([
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:1024'
    ]);

    $user = Auth::user();

    if ($request->hasFile('foto')) {

        // Hapus foto lama jika ada
        if ($user->foto && Storage::disk('public')->exists('foto/'.$user->foto)) {
            Storage::disk('public')->delete('foto/'.$user->foto);
        }

        // Simpan ke disk public (bukan default local)
        $file = $request->file('foto');
        $filename = time().'_'.$file->getClientOriginalName();

        $file->storeAs('foto', $filename, 'public');

        // Simpan nama file ke database
        $user->foto = $filename;
        $user->save();
    }

    return back()->with('success', 'Foto berhasil diupload');
}
}