@include('layout.header')
@include('layout.sidebar')

<div class="main-content">

<div class="header">
<div class="p">
<h1>Dashboard Guru</h1>
<p>Panel Guru</p>
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

</div>

</div>

@include('layout.footer')