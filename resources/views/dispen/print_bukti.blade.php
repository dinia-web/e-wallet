<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bukti Dispensasi</title>
<link rel="stylesheet" href="{{ asset('css/print.css') }}">

</head>

<body>

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
Bukti Dispensasi Murid
</div>

<p>
Status Dispensasi :
<span class="status">{{ strtoupper($data->status) }}</span>
</p>

@if($data->status == 'ditolak')

<div class="alasan-penolakan">

@if($data->alasan_guru)
<p>
<b>Ditolak oleh Guru Pengajar :</b><br>
{{ $data->alasan_guru }}
</p>
@endif

@if($data->alasan_guru_piket)
<p>
<b>Ditolak oleh Guru Piket :</b><br>
{{ $data->alasan_guru_piket }}
</p>
@endif

</div>

@endif

<table>

<tr>
<th>No</th>
<th>NIS</th>
<th>Nama</th>
<th>Kelas</th>
<th>Keterangan</th>
</tr>

@foreach($detail as $d)
<tr>
<td class="text-center">{{ $loop->iteration }}</td>
<td>{{ $d->nis }}</td>
<td>{{ $d->nama }}</td>
<td>{{ $d->siswa->kelas ?? '-' }}</td>
<td>{{ $data->alasan }}</td>
</tr>
@endforeach

</table>

<div style="text-align:right;">
<p>Kebumen, {{ date('d-m-Y') }}</p>
<p>Mengetahui,</p>
</div>

<div class="ttd-area">

<div class="ttd-box">
<p>Guru Pengajar</p>

<div class="ttd-space"></div>

<b>{{ $data->guru->username ?? '-' }}</b>
</div>

<div class="ttd-box">
<p>Guru Piket</p>

<div class="ttd-space"></div>

<b>{{ optional($data->guruPiket)->gurpi ?? '-' }}</b>
</div>

</div>

</div>

<script>
window.print()
</script>

</body>
</html>