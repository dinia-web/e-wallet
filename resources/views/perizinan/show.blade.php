@include('layout.header')
@include('layout.sidebar')

<div class="main-content dispen-detail-page">

<div class="page-title">
    <i class="fas fa-file-alt"></i>
    <h1>Detail Perizinan</h1>
</div>

<div class="detail-card">

<div class="section-header">
    <h3>📋 Informasi Perizinan</h3>

        <a href="{{ route('perizinan.index', request()->query()) }}" class="btn-back">
            ← Kembali
        </a>
</div>

<div class="info-grid">

    <div class="info-box">
        <label>NIS</label>
        <p>{{ $perizinan->siswa->nis }}</p>
    </div>

    <div class="info-box">
        <label>Nama Lengkap</label>
        <p>{{ $perizinan->siswa->nama }}</p>
    </div>

    <div class="info-box">
        <label>Kelas</label>
        <p>{{ $perizinan->siswa->kelas }}</p>
    </div>

    <div class="info-box">
        <label>Wali Kelas</label>
        <p>{{ $perizinan->guru->username }}</p>
    </div>

    <div class="info-box">
        <label>Status</label>
        <p>
            <span class="status-badge">
                {{ ucfirst($perizinan->jenis) }}
            </span>
        </p>
    </div>

    <div class="info-box">
        <label>Alasan</label>
        <p>{{ $perizinan->keterangan }}</p>
    </div>

    <div class="info-box">
        <label>Tanggal Pengajuan</label>
        <p>{{ \Carbon\Carbon::parse($perizinan->created_at)->format('d M Y H:i') }}</p>
    </div>

</div>

<div class="file-box">
    <label>Bukti File</label>

    @if($perizinan->file)

        <div class="file-preview">

            <img src="{{ asset('uploads/perizinan/'.$perizinan->file) }}">

            <br>

            <a href="{{ asset('uploads/perizinan/'.$perizinan->file) }}" 
               download
               class="btn-download">
               ⬇ Download File
            </a>

        </div>

    @else
        <p>Tidak ada file</p>
    @endif
</div>

</div>
@include('layout.footer')