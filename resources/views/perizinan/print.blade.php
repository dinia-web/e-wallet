<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Laporan Perizinan</title>

<link rel="stylesheet" href="{{ asset('css/print.css') }}">

</head>

<body>

<!-- KOP SEKOLAH -->

<div class="kop">

<div class="logo">
<img src="{{ asset('images/tu.png') }}">
</div>

<div class="sekolah">
<h2>SMK NEGERI 1 KEBUMEN</h2>
<p>Jln. Cemara No.37 Karangsari Kebumen Jawa Tengah</p>
<p>Telp : 0287-381132</p>
</div>

</div>

<div class="judul">
LAPORAN PERIZINAN MURID
</div>

<div class="tanggal-cetak">
Tanggal Cetak : {{ date('d-m-Y') }}
</div>

<!-- INFO FILTER -->

<p style="margin-top:10px;">
<b>Kelas :</b> {{ $request->kelas }} |
<b>Semester :</b> {{ $request->semester }} |
<b>Tahun :</b> {{ $request->tahun }}
</p>

<table>

<thead>
<tr>
<th>No</th>
<th>NIS</th>
<th>Nama Lengkap</th>
<th>Wali Kelas</th>
<th>Tanggal</th>
<th>Status</th>
<th>Keterangan</th>
</tr>
</thead>

<tbody>

@foreach($perizinan as $p)

<tr>
<td class="text-center">{{ $loop->iteration }}</td>

<td>{{ $p->nis }}</td>

<td>{{ $p->siswa->nama ?? '-' }}</td>

<td>{{ $p->guru->username ?? '-' }}</td>

<td class="text-center">
{{ $p->created_at->format('d-m-Y') }}
</td>

<td class="text-center">
{{ ucfirst($p->jenis) }}
</td>

<td>
{{ $p->keterangan }}
</td>

</tr>

@endforeach

</tbody>

</table>

<!-- TANDA TANGAN -->

<div class="ttd">

<div class="ttd-box">

<p>Kebumen, {{ date('d-m-Y') }}</p>
<p>Mengetahui,</p>
<p>Kepala Sekolah</p>

<div class="ttd-space"></div>

<p><b>UMI ROKHAYATUN, S.Pd., M.Pd</b></p>
<p>NIP. 19710509 199003 2 006</p>

</div>

</div>

<script>
window.print();
</script>

</body>
</html>