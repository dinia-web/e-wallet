@include('layout.header')
@include('layout.sidebar')

<div class="main-content" id="main-content">

    <!-- Header -->
    <div class="header">
        <div class="p">
            <h1>Dashboard</h1>
            <p>Administrator Panel</p>
        </div>
        <div class="info">
            {{ \Carbon\Carbon::now()->format('d M Y') }}
        </div>
    </div>

    <!-- Welcome -->
    <div class="welcome">
        <div>
            <h2>Selamat Datang, {{ auth()->user()->username }}</h2>
            <p>Di Website Administrasi Layanan Izin Terpadu</p>
        </div>
        <img src="{{ asset('images/ft.png') }}" alt="welcome" />
    </div>

    <!-- Cards -->
    <div class="cards">

        <!-- Dispensasi -->
        <div class="card small blue">
            <h3>Data Dispen</h3>
            <div class="info">
                <p>{{ $jumlah_dispen }}</p>
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ url('/dispen?all=true') }}">Selengkapnya</a>
        </div>

        <div class="card small lightblue">
            <h3>Dispen Hari Ini</h3>
            <div class="info">
                <p>{{ $dispen_hari_ini }}</p>
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ url('/dispen') }}">Selengkapnya</a>
        </div>

        <!-- Info Statis / Contoh -->
        <div class="card small green">
            <h3>Total Murid</h3>
            <div class="info">
                <p>{{ $jumlah_siswa }}</p>
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="siswa">Selengkapnya</a>
        </div>

        <div class="card small yellow">
            <h3>Total Guru</h3>
            <div class="info">
                <p>{{ $jumlah_guru }}</p>
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="users">Selengkapnya</a>
        </div>

        <div class="card small orange">
            <h3>Total Perizinan</h3>
            <div class="info">
                <p>{{ $jumlah_perizinan }}</p>
                <i class="fas fa-notes-medical"></i>
            </div>
            <a href="/perizinan?all=true">Selengkapnya</a>
        </div>

        <div class="card small purple">
            <h3>Izin Hari Ini</h3>
            <div class="info">
                <p>{{ $izin_hari_ini }}</p>
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="/perizinan">Selengkapnya</a>
        </div>

    </div>

<!-- Chart Statistik Bulanan -->
<div class="charts">
    <!-- Dispensasi per bulan -->
    <div class="chart-container">
        <h3>Dispensasi Bulanan</h3>
        <canvas id="dispenChart"></canvas>
    </div>

    <!-- Perizinan per bulan -->
    <div class="chart-container">
        <h3>Perizinan Bulanan</h3>
        <canvas id="izinChart"></canvas>
    </div>
</div>
<div id="chart-data"
     data-bulan='@json($bulan)'
     data-dispen='@json($dataDispen)'
     data-sakit='@json($dataSakit)'
     data-izin='@json($dataIzin)'
     data-terlambat='@json($dataTerlambat)'>
</div>

</div>

@include('layout.footer')