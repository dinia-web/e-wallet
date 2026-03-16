<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="icon" href="{{ asset('images/y.png') }}">
<link rel="stylesheet" href="{{ asset('css/dispen.css') }}">
</head>

<body>

<!-- HEADER -->
<div class="header">
    <img src="{{ asset('images/y.png') }}" alt="Logo E-Wallet">
    <h1>Si WALET</h1>
</div>

<!-- FORM -->
<div class="container">

<h2>Form Register</h2>
<hr>

@if ($errors->any())
<div class="error-box">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="/register">
@csrf
<div id="nisField" style="display:none;">
<label>NIS</label>
<input type="number" name="nis">
</div>
<label>Username</label>
<input type="text" name="username" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Konfirmasi Password</label>
<input type="password" name="password_confirmation" required>

<label>Role</label>
<select name="role" id="roleSelect" class="custom-select" required>
    <option value="" disabled selected>Pilih Role</option>
    <option value="admin">Admin</option>
    <option value="guru">Guru</option>
    <option value="siswa">Murid</option>
</select>

<!-- Checkbox Wali Kelas untuk guru -->
<div class="form-group" id="walikelasBox" style="display:none;">
    <label>
        <input type="checkbox" name="is_walikelas" value="1">
        Jadikan Wali Kelas
    </label>
</div>

<div class="klas">
        <button type="submit">Register</button>
    </div></form>
</div>
<script>
const roleSelect = document.getElementById('roleSelect');
const nisField = document.getElementById('nisField');
const walikelasBox = document.getElementById('walikelasBox');

roleSelect.addEventListener('change', function(){
    if(this.value === 'siswa'){
        nisField.style.display = 'block';
        walikelasBox.style.display = 'none';
    } else if(this.value === 'guru'){
        walikelasBox.style.display = 'block';
        nisField.style.display = 'none';
    } else {
        nisField.style.display = 'none';
        walikelasBox.style.display = 'none';
    }
});
</script>
</body>
</html>