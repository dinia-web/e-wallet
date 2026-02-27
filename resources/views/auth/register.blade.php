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
    <h1>Si Walet</h1>
</div>

<!-- FORM -->
<div class="container">

<h2>FORM REGISTER</h2>
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

<label>Username</label>
<input type="text" name="username" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Konfirmasi Password</label>
<input type="password" name="password_confirmation" required>

<label>Role</label>
<select name="role" required>
    <option value="admin">Admin</option>
    <option value="guru">Guru</option>
</select>

<button type="submit">Register</button>

</form>
</div>

</body>
</html>