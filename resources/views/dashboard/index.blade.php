@include('layout.header')
@include('layout.sidebar')

<div class="main-content" id="main-content">

    <div class="header">
        <div class="p">
            <h1>Dashboard</h1>
            <p>Administrator Panel</p>
        </div>

        <div class="info">
            {{ \Carbon\Carbon::now()->format('d M Y') }}
        </div>

    </div>

    <div class="welcome">
        <div>
            <h2>Selamat Datang</h2>
            <p>Di Website Aplikasi Surat Izin Keluar Lingkungan Sekolah</p>
        </div>
        <img src="{{ asset('images/ft.png') }}">
    </div>

    <div class="cards">

        <div class="card">
            <h3>Dispen Hari Ini</h3>
            <div class="info">
                <p>{{ $jumlah_terbaru }}</p>
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ url('/dispen') }}">Selengkapnya</a>
        </div>

        <div class="card yellow">
            <h3>Data Terbaru</h3>
            <div class="info">
                <p>{{ $jumlah_dispen }}</p>
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ url('/dispen') }}">Selengkapnya</a>
        </div>

    </div>

</div>

@include('layout.footer')
