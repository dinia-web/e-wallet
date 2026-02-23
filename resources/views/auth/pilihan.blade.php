<!DOCTYPE html>
<html>
<head>
    <title>E-Dispensasi</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/pilihan.css') }}">
</head>
<body>
<div class="container">
    <div class="logo-wrapper">
        <img src="{{ asset('images/l.png') }}">
    </div>
    <p>Silahkan pilih halaman akses sesuai dengan status masing-masing.</p>
    <hr>
    <div class="akses-login">Akses</div>
    <div class="login-buttons">
        <a href="{{ url('/login?role=admin') }}" class="admin-btn">Admin</a>
        <a href="{{ url('/login?role=guru') }}" class="guru-btn">Guru</a>
        <a href="{{ url('/auth/dispen') }}" class="siswa-btn">Siswa</a>
    </div>
</div>
</body>
</html>