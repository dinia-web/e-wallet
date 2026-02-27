<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Si Walet</title>
    <link rel="icon" href="{{ asset('images/y.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="login-container">
    <div class="img">
        <img src="{{ asset('images/y.png') }}" alt="Logo">
    </div>
    <hr>
    <h4>Form Login</h4>

    <form method="POST">
        @csrf

        <h5>Username</h5>
        <input type="text" name="username"
               value="{{ Cookie::get('username') }}"
               placeholder="Username" required>

        <h5>Email</h5>
        <input type="text" name="email" placeholder="Email" required>

        <h5>Password</h5>
        <div class="password">
            <input type="password" name="password" id="password" required>
            <i class="fa-solid fa-eye-slash" id="togglePassword"></i>
        </div>

        @if(session('error'))
            <p class="error-msg">{{ session('error') }}</p>
        @endif

        <div class="remember-forgot">
            <label class="remember">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>

            <button type="button" class="forgot-btn" onclick="openForgotPasswordModal()">
                <i class="fa-solid fa-key"></i>
                Lupa Password?
            </button>
        </div>

        <button type="submit" class="btn">Masuk</button>

    </form>
    <hr>
</div>

<div class="right-section">
    <div class="header">Si Walet</div>
    <div class="content">
        <h2>INFORMASI</h2>
        <hr>
        <p>
            Aplikasi surat izin keluar lingkungan sekolah berbasis web adalah
            sebuah sistem yang dibuat untuk memudahkan siswa dalam mengajukan
            permohonan izin keluar lingkungan sekolah kepada pihak sekolah.
        </p>
    </div>
</div>

<!-- MODAL LUPA PASSWORD -->
<div id="forgotPasswordModal" class="modal-overlay">
    <div class="modal-box">

        <button class="modal-close" onclick="closeForgotPasswordModal()">
            &times;
        </button>

        <h3 class="modal-title">Reset Password</h3>

        @if(session('modal_error'))
            <div class="alert-error">
                {{ session('modal_error') }}
            </div>
        @endif

        @if(session('modal_success'))
            <div class="alert-success">
                {{ session('modal_success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset.manual') }}">
            @csrf

            <input type="text" name="username"
                   placeholder="Username"
                   required>

            <input type="email" name="email"
                   placeholder="Email"
                   required>

            <input type="password" name="password"
                   placeholder="Password Baru"
                   required>

            <input type="password" name="password_confirmation"
                   placeholder="Konfirmasi Password"
                   required>

            <button type="submit" class="btn">
                Ubah Password
            </button>
        </form>
    </div>
</div>

<script>
    window.hasResetMessage = {{ session('modal_error') || session('modal_success') ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/modal.js') }}"></script>

</body>
</html>