@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- CARD TITLE -->
    <div class="page-title">
        <i class="fas fa-cog"></i>
        <h1 id="pengaturan-title">Pengaturan - Informasi Akun</h1>
    </div>

    <!-- HEADER -->
    <div class="pengaturan-header">
        <div class="tabs">
            <button class="tab active" onclick="showTab('profil')">Profil</button>
            <button class="tab" onclick="showTab('password')">Ubah Password</button>
        </div>
    </div>


    <!-- ================= TAB PROFIL ================= -->
    <div id="profil" class="tab-content active">

        <div class="card-wrapper">

            <!-- FOTO -->
            <div class="card-box">
                <h3>Profil Picture</h3>

                <div class="profile-image">
                    <img id="previewFoto"
                        src="{{ $user->foto 
                            ? asset('storage/foto/'.$user->foto) 
                            : asset('images/default.png') }}"
                        width="120">
                </div>

                <form action="{{ route('pengaturan.uploadFoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="foto" id="inputFoto" accept="image/*" required>
                    <small>JPG atau PNG tidak lebih dari 1 MB</small>
                    <button type="submit" class="btn-primary">Unggah</button>
                </form>
            </div>

            <!-- INFO AKUN -->
            <div class="card-box">
                <h3>Informasi Akun</h3>

                <form action="{{ route('pengaturan.updateProfile') }}" method="POST">
                    @csrf

                    <label>Username</label>
                    <input type="text" name="username"
                        value="{{ old('username', $user->username) }}" required>

                    <label>Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email) }}" required>

                    <button type="submit" class="btn-primary">
                        Perbarui Profil
                    </button>
                </form>
            </div>

        </div>
    </div>


    <!-- ================= TAB PASSWORD ================= -->
    <div id="password" class="tab-content">

        <div class="card-box single">
            <h3>Ubah Password</h3>

            <form action="{{ route('pengaturan.updatePassword') }}" method="POST">
                @csrf

                <label>Password Lama</label>
                <input type="password" name="password_lama" required>

                <label>Password Baru</label>
                <input type="password" name="password_baru" required>

                <label>Konfirmasi Password</label>
                <input type="password" name="password_baru_confirmation" required>

                <button type="submit" class="btn-primary">
                    Update Password
                </button>
            </form>
        </div>

    </div>

</div>

@include('layout.footer')