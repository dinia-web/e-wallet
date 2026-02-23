@include('layout.header')
@include('layout.sidebar')

<div class="main-content dispen-detail-page">

    <div class="page-title">
        <i class="fas fa-file-alt"></i>
        <h1>Detail Dispensasi</h1>
    </div>

<div class="detail-card">

    {{-- ✅ HEADER SISWA + BUTTON --}}
    <div class="section-header">
        <h3>👨‍🎓 Data Siswa Dispensasi</h3>

        <a href="{{ route('dispen.index') }}" class="btn-back">
            ← Kembali
        </a>
    </div>

    {{-- ✅ TABEL GABUNGAN SISWA --}}
    <div class="table-responsive">
        <table class="table-siswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>

                {{-- 🔥 SISWA UTAMA --}}
                <tr class="utama">
                    <td>1</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->nis }}</td>
                    <td><span class="badge-utama">Pengaju</span></td>
                </tr>

                {{-- 🔥 SISWA TAMBAHAN --}}
                @foreach($detail as $i => $d)
                <tr>
                    <td>{{ $i + 2 }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->nis }}</td>
                    <td><span class="badge-tambahan">Tambahan</span></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>


        {{-- ✅ 3. INFORMASI DISPEN --}}
<div class="section">
    <h3>📚 Informasi Dispensasi</h3>

    <div class="info-grid">
        <div><strong>Kelas:</strong> {{ $data->kelas }}</div>
        <div><strong>Guru Pengajar:</strong> {{ $data->guru }}</div>
        <div><strong>Email:</strong> {{ $data->email }}</div>
        <div><strong>Keperluan:</strong> {{ $data->alasan }}</div>

        {{-- ✅ STATUS --}}
        <div class="uy">
            <strong>Status:</strong><br>
            <span class="status-badge {{ strtolower(str_replace(' ', '-', $data->status)) }}">
                {{ ucfirst($data->status) }}
            </span>
        </div>

        {{-- ✅ AKSI --}}
        <div class="aksi-box">
            <strong>Aksi:</strong><br><br>
            <div class="aksi-btn-group">
            @if(auth()->user()->role == 'admin' && !$data->admin_action)
                <button class="btn-approve" onclick="openmodal('admin-setuju')">Setujui</button>
                <button class="btn-reject" onclick="openmodal('admin-tolak')">Tolak</button>
            @endif

            @if(auth()->user()->role == 'guru' && !$data->guru_action)
                <button class="btn-approve" onclick="openmodal('guru-setuju')">Setujui</button>
                <button class="btn-reject" onclick="openmodal('guru-tolak')">Tolak</button>
            @endif
        </div>
        </div>
    </div>
</div>

<div id="admin-setuju" class="modal">
    <div class="modal-content">
        <h3>Setujui Dispensasi</h3>

        <form method="POST" action="{{ route('dispen.actionAdmin', $data->id_dispen) }}">
            @csrf

            <select name="id_gurupik" required>
                <option value="">Pilih Guru Piket</option>
                @foreach($gurpik as $g)
                    <option value="{{ $g->id_guru }}">
                        {{ $g->gurpi }}
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="action" value="setuju">

            <div class="modal-buttons">
                <button type="submit" class="btn-approve">Setujui</button>
                <button type="button" class="btn-cancel" onclick="closemodal()">Batal</button>
            </div>
        </form>
    </div>
</div>
<div id="admin-tolak" class="modal">
    <div class="modal-content">
        <h3>Tolak Dispensasi</h3>

        <form method="POST" action="{{ route('dispen.actionAdmin', $data->id_dispen) }}">
            @csrf

            <select name="id_gurupik" required>
                <option value="">Pilih Guru Piket</option>
                @foreach($gurpik as $g)
                    <option value="{{ $g->id_guru }}">
                        {{ $g->gurpi }}
                    </option>
                @endforeach
            </select>

            <textarea name="alasan" placeholder="Masukkan alasan penolakan" required></textarea>

            <input type="hidden" name="action" value="tolak">

            <div class="modal-buttons">
                <button type="submit" class="btn-reject">Tolak</button>
                <button type="button" class="btn-cancel" onclick="closemodal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="guru-setuju" class="modal">
    <div class="modal-content">
        <h3>Setujui Dispensasi</h3>

        <form method="POST" action="{{ route('dispen.actionGuru', $data->id_dispen) }}">
            @csrf
            <input type="hidden" name="action" value="setuju">

            <div class="modal-buttons">
                <button type="submit" class="btn-approve">Setujui</button>
                <button type="button" class="btn-cancel" onclick="closemodal()">Batal</button>
            </div>
        </form>
    </div>
</div>
<div id="guru-tolak" class="modal">
    <div class="modal-content">
        <h3>Tolak Dispensasi</h3>

        <form method="POST" action="{{ route('dispen.actionGuru', $data->id_dispen) }}">
            @csrf

            <textarea name="alasan" placeholder="Masukkan alasan penolakan" required></textarea>

            <input type="hidden" name="action" value="tolak">

            <div class="modal-buttons">
                <button type="submit" class="btn-reject">Tolak</button>
                <button type="button" class="btn-cancel" onclick="closemodal()">Batal</button>
            </div>
        </form>
    </div>
</div>

@include('layout.footer')