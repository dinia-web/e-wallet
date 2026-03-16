@include('layout.header')
@include('layout.sidebar')

<div class="main-content">

<div class="header">
    <div class="p">
        <h1>Dashboard Murid</h1>
        <p>Halaman Pengajuan Izin</p>
    </div>

    <div class="info">
        {{ \Carbon\Carbon::now()->format('d M Y') }}
    </div>
</div>

<div class="welcome">
    <div>
        <h2>Selamat Datang, {{ auth()->user()->username }}</h2>
        <p>Di Website Administrasi Layanan Izin Terpadu</p>
    </div>
    <img src="{{ asset('images/ft.png') }}">
</div>

<div class="cards">

    <div class="card ">
        <h3>Dispensasi Saya</h3>
        <div class="info">
            <p>{{ $jumlah_dispen }}</p>
            <i class="fas fa-envelope"></i>
        </div>
        <a href="{{ url('/dispen') }}">Lihat Dispensasi</a>
    </div>

    <div class="card yellow">
        <h3>Perizinan</h3>
        <div class="info">
            <p>{{ $jumlah_izin }}</p>
            <i class="fas fa-notes-medical"></i>
        </div>
        <a href="{{ route('perizinan.create') }}">Ajukan Izin</a>
    </div>

</div>
</div>

@include('layout.footer')