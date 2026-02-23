<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Dispensasi</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dispen.css') }}">
</head>

<body>

@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b5bdb',
            customClass: {
                confirmButton: 'swal2-ok-button'
            }
        });
    </script>
@endif

<div class="header">
    <img src="{{ asset('images/o.png') }}" alt="Logo E-Dispensasi">
    <h1>E-Dispensasi</h1>
</div>

<div class="container">
    <h2>Form Dispensasi</h2>

    <form method="POST" action="{{ url('/auth/dispen') }}">
        @csrf

        <label>Nis</label>
        <input type="number" name="nis" placeholder="Masukkan Nis Anda" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan Nama Lengkap Anda" required>

        <label>Data Siswa Tambahan</label>
        <div id="namaTambahanWrapper"></div>

        <button type="button" onclick="tambahNama()">+ Tambah</button>


        <label>Kelas</label>
        <select name="kelas" required>
            <option value="">Pilih kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id_kelas }}">{{ $k->klas }}</option>
            @endforeach
        </select>

        <label>Jam keluar</label>
        <select name="jam_keluar" required>
            <option value="">Pilih jam keluar</option>
            @foreach($jampel as $j)
                <option value="{{ $j->id_jampel }}">{{ $j->jam }}</option>
            @endforeach
        </select>

        <label>Jam kembali</label>
        <select name="jam_kembali" required>
            <option value="">Pilih jam kembali</option>
            @foreach($jampel as $j)
                <option value="{{ $j->id_jampel }}">{{ $j->jam }}</option>
            @endforeach
        </select>

        <label>Guru Pengajar</label>
            <select name="id_guru" required>
                <option value="">Pilih Guru</option>
                @foreach($guru as $g)
                    <option value="{{ $g->id_user }}">
                        {{ $g->username }}
                    </option>
                @endforeach
            </select>

        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan Email Anda" required>

        <label>Keperluan</label>
        <input type="text" name="alasan" placeholder="Masukkan Keperluan Anda" required>

        <button type="submit">Kirim</button>
    </form>
</div>
<script>
  let maxNama = 10;

function tambahNama() {
    let wrapper = document.getElementById("namaTambahanWrapper");

    if (wrapper.children.length >= maxNama) {
        alert("Maksimal 10 data!");
        return;
    }

    let div = document.createElement("div");
    div.classList.add("nama-item");

    div.innerHTML = `
        <input type="number" name="nis_tambahan[]" placeholder="NIS">
        <input type="text" name="nama_tambahan[]" placeholder="Nama Lengkap">
        <button type="button" class="btn-hapus" onclick="hapusNama(this)">×</button>
    `;

    wrapper.appendChild(div);
}

function hapusNama(btn) {
    btn.parentElement.remove();
}
</script>
</body>
</html>