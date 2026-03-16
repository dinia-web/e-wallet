<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si WALET</title>
    <link rel="icon" href="{{ asset('images/y.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dispen.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>

<div class="header">
    <img src="{{ asset('images/y.png') }}" alt="Logo E-Wallet">
    <h1>Si WALET</h1>
</div>

<div class="container">
    <h2>Form Dispensasi</h2>

    <form method="POST" action="{{ url('/auth/dispen') }}">
    @csrf

    <label>Tipe Dispensasi</label>
    <select name="tipe" id="tipeDispen" class="custom-select" required>
        <option value="individu">Individu</option>
        <option value="kelompok">Kelompok (1 Kelas)</option>
    </select>

    <div id="kelasKelompokWrapper" style="display:none;">
        <label>Kelas</label>
        <select name="kelas_kelompok" id="kelasKelompok" class="custom-select">
            <option value="">Pilih Kelas</option>
            @foreach($kelas as $k)
                <option value="{{ $k->kelas }}">
                    {{ $k->kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div id="previewSiswa" style="display:none;"></div>

    <div id="individuWrapper">

    <!-- NIS + Nama utama -->
    <div class="form-row">
        <div class="form-group">
            <label>NIS</label>
            <input type="number" name="nis" id="nisInput" placeholder="NIS">
        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" id="namaSiswa" placeholder="Nama Lengkap" readonly>
        </div>
    </div>

    <!-- TAMBAHAN SISWA MUNCUL DI SINI -->
    <div id="namaTambahanWrapper"></div>

    <!-- Kelas tetap di bawah -->
    <div >
        <label>Kelas</label>
        <input type="text" id="kelasSiswa" placeholder="Kelas" readonly>
    </div>

</div>

     <!-- Email -->
    <label>Email</label>
    <input type="email" name="email" placeholder="Email" required>
    
    <label>No Hp.</label>
    <input type="text" name="no_hp" placeholder="No Hp." required>

    <!-- Guru -->
    <label>Guru Pengajar</label>
    <select name="id_guru" class="select-guru" required>
        <option value="">Pilih Guru</option>
        @foreach($guru as $g)
            <option value="{{ $g->id_user }}">
                {{ $g->username }}
            </option>
        @endforeach
    </select>

    <!-- Keperluan -->
    <label>Keperluan</label>
    <input type="text" name="alasan" placeholder="Keperluan" required>
    <div class="klas">
        <button type="submit">Kirim</button>
        <button id="tmbah" type="button" onclick="tambahNama()">Tambah</button>
    </div>

</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function () {

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3b5bdb'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#3b5bdb'
        });
    @endif

});
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
        <input type="number" class="nis-tambahan" placeholder="NIS">
        <input type="text" class="nama-tambahan" name="nama_tambahan[]" placeholder="Nama Lengkap" readonly>
        <input type="hidden" name="nis_tambahan[]">
        <button type="button" class="btn-hapus" onclick="hapusNama(this)">×</button>
    `;

    wrapper.appendChild(div);
}

function hapusNama(btn) {
    btn.parentElement.remove();
}
document.addEventListener("input", function (e) {

    if (e.target.classList.contains("nis-tambahan")) {

        let nis = e.target.value.trim();
        let parent = e.target.parentElement;

        let namaField = parent.querySelector(".nama-tambahan");
        let hiddenNis = parent.querySelector("input[type='hidden']");

        if (nis.length === 0) {
            namaField.value = "";
            hiddenNis.value = "";
            return;
        }

        fetch("/get-siswa/" + nis)
            .then(res => res.json())
            .then(data => {

                if (data.status) {
                    namaField.value = data.nama;
                    hiddenNis.value = nis;
                } else {
                    namaField.value = "";
                    hiddenNis.value = "";
                }

            })
            .catch(err => console.error(err));
    }

});
// ============================
// AUTO FETCH SISWA (FORM DISPEN)
// ============================
document.addEventListener("DOMContentLoaded", function () {

    const nisInput = document.getElementById("nisInput");
    const namaField = document.getElementById("namaSiswa");
    const kelasField = document.getElementById("kelasSiswa");

    if (!nisInput) return;

    let timeout = null;

    nisInput.addEventListener("keyup", function () {

        clearTimeout(timeout);

        let nis = this.value.trim();

        if (nis.length === 0) {
            if (namaField) namaField.value = "";
            if (kelasField) kelasField.value = "";
            return;
        }

        // delay 400ms supaya tidak spam request
        timeout = setTimeout(() => {

            fetch("/get-siswa/" + nis)
                .then(response => response.json())
                .then(data => {

                    if (data.status) {

                        if (namaField) namaField.value = data.nama;
                        if (kelasField) kelasField.value = data.kelas;

                    } else {

                        if (namaField) namaField.value = "";
                        if (kelasField) kelasField.value = "";

                    }

                })
                .catch(error => {
                    console.error("Fetch error:", error);
                });

        }, 400);
    });
});
$(document).ready(function() {
    $('.select-guru').select2({
        placeholder: "Pilih Guru",
        allowClear: true,
        width: '100%'
    });


});

document.addEventListener("DOMContentLoaded", function() {

    const tipeSelect = document.getElementById("tipeDispen");
    const individuWrapper = document.getElementById("individuWrapper");
    const kelasWrapper = document.getElementById("kelasKelompokWrapper");
    const tombolTambah = document.getElementById("tmbah");
    const preview = document.getElementById("previewSiswa");
    const kelasSelect = document.getElementById("kelasKelompok");

    // ===== Sembunyikan preview saat awal =====
    if (preview) {
        preview.style.display = "none";
    }

    // ===== TOGGLE MODE =====
    tipeSelect.addEventListener("change", function() {

        if (this.value === "kelompok") {

            individuWrapper.style.display = "none";
            kelasWrapper.style.display = "block";
            tombolTambah.style.display = "none";

        } else {

            individuWrapper.style.display = "block";
            kelasWrapper.style.display = "none";
            tombolTambah.style.display = "inline-block";

        }

        // Reset preview
        preview.innerHTML = "";
        preview.style.display = "none";
        kelasSelect.value = "";

    });

    // ===== PREVIEW SISWA PER KELAS =====
    kelasSelect.addEventListener("change", function() {

        let kelas = this.value;

        if (!kelas) {
            preview.innerHTML = "";
            preview.style.display = "none";
            return;
        }

        fetch("/get-siswa-kelas/" + kelas)
            .then(res => res.json())
            .then(data => {

                if (!data.length) {
                    preview.style.display = "none";
                    return;
                }

                let html = `
                    <div class="preview-title">
                        Daftar Siswa Kelas ${kelas}
                    </div>
                `;

                data.forEach(s => {
                    html += `
                        <div class="preview-row">
                            <input type="text" value="${s.nis}" readonly>
                            <input type="text" value="${s.nama}" readonly>
                        </div>
                    `;
                });

                preview.innerHTML = html;
                preview.style.display = "block";

            })
            .catch(err => {
                console.error(err);
                preview.style.display = "none";
            });
    });

});
</script>
</body>
</html>