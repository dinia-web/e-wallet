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
    <h2>Form Perizinan</h2>

    <form method="POST" action="{{ route('perizinan.store') }}" enctype="multipart/form-data">
    @csrf

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

        <label>Kelas</label>
        <input type="text" id="kelasSiswa" placeholder="Kelas" readonly>

        <label>Status</label>
        <select name="jenis" class="custom-select" required>
            <option value="">Pilih Status</option>
            <option value="sakit">Sakit</option>
            <option value="izin">Izin</option>
            <option value="terlambat">Terlambat</option>
        </select>

        <label>Keterangan</label>
        <textarea name="keterangan" placeholder="Isi keterangan..." required></textarea>
        
        <label>Wali Kelas</label>
        <select name="id_guru" class="select-guru" required>
            <option value="">Pilih Guru</option>
            @foreach($guru as $g)
            <option value="{{ $g->id_user }}">{{ $g->username }}</option>
            @endforeach
        </select>

        <label>Upload Bukti</label>
        <input type="file" name="file">

<div class="klas">
        <button type="submit">Kirim</button>
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

</script>
</body>
</html>