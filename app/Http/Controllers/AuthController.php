<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function splash()
    {
        return view('auth.splash');
    }

    public function pilihan()
    {
        return view('auth.pilihan');
    }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return back()->with('error', 'Username tidak ditemukan!');
    }

    // jika admin atau guru harus cek email
    if (in_array($request->role, ['admin','guru'])) {

        if (!$request->email) {
            return back()->with('error', 'Email wajib diisi!');
        }

        if ($user->email !== $request->email) {
            return back()->with('error', 'Email tidak sesuai!');
        }

    }

    // cek password
    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Password salah!');
    }

    // cek akun siswa punya NIS
    if ($user->role == 'siswa' && !$user->nis) {
        return back()->with('error', 'Akun siswa belum terhubung dengan data siswa!');
    }

    Auth::login($user, $request->remember);

$request->session()->regenerate();

// 🔥 redirect berdasarkan role
if ($user->role == 'siswa') {
    return redirect('/siswa/dashboard');
}

return redirect('/dashboard');
}

public function prosesLupaPassword(Request $request)
{
    $request->validate([
        'username' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return back()->with('modal_error', 'Username tidak ditemukan!');
    }

    if ($user->email !== $request->email) {
        return back()->with('modal_error', 'Email tidak sesuai!');
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('modal_success', 'Password berhasil diubah! Silakan login.');
}



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/pilihan');
    }
}
