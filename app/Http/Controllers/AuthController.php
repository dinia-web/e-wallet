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
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 1️⃣ Cek username dulu
    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return back()->with('error', 'Username tidak ditemukan!');
    }

    // 2️⃣ Cek email cocok atau tidak
    if ($user->email !== $request->email) {
        return back()->with('error', 'Email tidak sesuai dengan username!');
    }

    // 3️⃣ Cek password
    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Password salah!');
    }

    // 4️⃣ Login jika semua benar
    Auth::login($user, $request->remember);

    $request->session()->regenerate();

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
