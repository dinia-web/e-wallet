@include('layout.header')
@include('layout.sidebar')

<div class="main-content">

<div class="header">
<div class="p">
<h1>Dashboard Wali Kelas</h1>
<p>Panel Wali Kelas</p>
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
<p>{{ $jumlah_terbaru }}</p>
<i class="fas fa-clock"></i>
</div>

<a href="{{ url('/dispen') }}">Selengkapnya</a>
</div>


<div class="card small orange">
<h3>Total Perizinan</h3>

<div class="info">
<p>{{ $jumlah_perizinan }}</p>
<i class="fas fa-notes-medical"></i>
</div>

<a href="{{ url('/perizinan?all=true') }}">Selengkapnya</a>
</div>


<div class="card small purple">
<h3>Izin Hari Ini</h3>

<div class="info">
<p>{{ $izin_hari_ini }}</p>
<i class="fas fa-calendar-check"></i>
</div>

<a href="{{ url('/perizinan') }}">Selengkapnya</a>
</div>

</div>

@include('layout.footer')