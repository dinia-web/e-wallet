@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- TITLE -->
    <div class="page-title">
        <i class="fas fa-clock"></i>
        <h1>Laporan</h1>
    </div>

<div class="laporan-page">
    <div class="card1">
    <h2>Laporan Dispensasi</h2>

    <form method="GET" class="input-group">
        <input type="date" name="tanggal_awal" value="{{ $tanggal_awal }}">
        <input type="date" name="tanggal_akhir" value="{{ $tanggal_akhir }}">

        <div class="btn">
            <button type="submit" class="cari">Cari</button>
            <a href="{{ route('laporan.export', request()->all()) }}"
               class="download-button">
               Download Excel
            </a>
        </div>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jam Keluar</th>
                    <th>Jam Kembali</th>
                    <th>Tanggal</th>
                    <th>Guru Pengajar</th>
                    <th>Guru Piket</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            @forelse($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row['nis'] }}</td>
                    <td>
                        {{ $row['nama'] }}

                        {{-- 🔥 penanda siswa tambahan --}}
                        @if(str_contains($row['alasan'], '(Tambahan)'))
                            <span style="color:#3b82f6; font-size:12px;">+</span>
                        @endif
                    </td>
                    <td>{{ $row['kelas'] }}</td>
                    <td>{{ $row['jam_keluar'] }}</td>
                    <td>{{ $row['jam_kembali'] }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                        <br>
                        {{ \Carbon\Carbon::parse($row['tanggal'])->format('H:i:s') }}
                    </td>
                    <td>{{ $row['guru'] }}</td>
                    <td>{{ $row['gurpik'] }}</td>
                    <td>{{ $row['alasan'] }}</td>
                    <td>
                        <span style="
                            color:
                            {{ $row['status'] == 'disetujui' ? '#16a34a' : '#dc2626' }};
                            font-weight:bold;
                        ">
                            {{($row['status']) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">
                        Silakan pilih tanggal untuk melihat data.
                    </td>
                </tr>
            @endforelse

            </tbody>
            
        </table>
    </div>
</div>
</div>

@include('layout.footer')