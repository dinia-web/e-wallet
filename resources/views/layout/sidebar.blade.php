<div class="sidebar" id="sidebar">
    <nav>

        <a href="{{ Auth::user()->role == 'siswa' ? url('/siswa/dashboard') : url('/dashboard') }}">
            <h2>Menu</h2>
        </a>

        {{-- DASHBOARD --}}
        <a class="{{ request()->is('dashboard') || request()->is('siswa/dashboard') ? 'active' : '' }}" 
           href="{{ Auth::user()->role == 'siswa' ? url('/siswa/dashboard') : url('/dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <h2>Dashboard</h2>
        </a>

        {{-- ================== MENU SISWA ================== --}}
        @if(Auth::user()->role == 'siswa')

            <div class="menu-item {{ request()->is('siswa/dispen*') || request()->is('perizinan*') ? 'active' : '' }}"
                onclick="toggleSubmenuPengajuan()">

                <i class="fas fa-file-signature"></i>
                <h2>Pengajuan</h2>
                <i class="fas fa-chevron-down arrow"></i>
            </div>

            <div id="submenu-pengajuan" 
                class="submenu {{ request()->is('siswa/dispen*') || request()->is('perizinan*') ? 'show' : '' }}">

                <a class="{{ request()->is('siswa/dispen*') ? 'active' : '' }}" 
                href="{{ url('/siswa/dispen') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Dispensasi</span>
                </a>

                <a class="{{ request()->is('perizinan*') ? 'active' : '' }}" 
                href="{{ route('perizinan.index') }}">
                    <i class="fas fa-notes-medical"></i>
                    <span>Perizinan</span>
                </a>

            </div>
        @else

         {{-- DISPENSASI UNTUK ADMIN / GURU --}}
    @if(Auth::user()->role == 'admin' || (Auth::user()->role == 'guru' && Auth::user()->is_walikelas))
        {{-- Admin / Wali Kelas → submenu --}}
        <div class="menu-item {{ request()->is('dispen*') || request()->is('laporan') ? 'active' : '' }}" onclick="toggleSubmenuDispen()">
            <i class="fas fa-file-signature"></i>
            <h2>Dispensasi</h2>
            <i class="fas fa-chevron-down arrow"></i>
        </div>
        <div id="submenu-dispen" class="submenu {{ request()->is('dispen*') | request()->is('laporan') ? 'show' : '' }}">
            <a class="{{ request()->is('dispen*') ? 'active' : '' }}" href="{{ url('/dispen') }}">
                <i class="fas fa-list"></i>
                <span>Daftar Dispensasi</span>
            </a>
            
            <a class="{{ request()->is('laporan') ? 'active' : '' }}" href="{{ url('/laporan') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Laporan Dispensasi</span>
            </a>
        </div>

        {{-- Perizinan --}}
        <div class="menu-item {{ request()->is('perizinan*') ? 'active' : '' }}" onclick="toggleSubmenuPerizinan()">
            <i class="fas fa-notes-medical"></i>
            <h2>Perizinan</h2>
            <i class="fas fa-chevron-down arrow"></i>
        </div>
        <div id="submenu-perizinan" class="submenu {{ request()->is('perizinan*')  ? 'show' : '' }}">
            <a class="{{ request()->is('perizinan*') ? 'active' : '' }}" href="{{ route('perizinan.index') }}">
                <i class="fas fa-list"></i>
                <span>Daftar Perizinan</span>
            </a>
            <a class="{{ request()->is('perizinan/print*') ? 'active' : '' }}" href="{{ route('perizinan.printForm') }}">
                <i class="fas fa-print"></i>
                <span>Print / Export</span>
            </a>
        </div>
    @else
        {{-- Guru biasa → Dispensasi langsung link --}}
        <a class="{{ request()->is('dispen*') ? 'active' : '' }}" href="{{ url('/dispen') }}">
            <i class="fas fa-file-signature"></i>
            <h2>Dispensasi</h2>
        </a>

        {{-- Laporan untuk guru biasa --}}
        <a class="{{ request()->is('laporan') ? 'active' : '' }}" href="{{ url('/laporan') }}">
            <i class="fas fa-chart-line"></i>
            <h2>Laporan</h2>
        </a>
    @endif

        {{-- KHUSUS ADMIN --}}
        @if(Auth::user()->role == 'admin')

            <div class="menu-item 
                {{ request()->is('kelas*') || request()->is('jam-pelajaran*') || request()->is('gurpik*') || request()->is('users*') ? 'active' : '' }}"
                onclick="toggleSubmenu()">

                <i class="fas fa-cogs"></i>
                <h2>Manajemen</h2>
                <i class="fas fa-chevron-down arrow"></i>
            </div>

            <div id="submenu-manajemen" 
                class="submenu {{ request()->is('kelas*') || request()->is('siswa*') || request()->is('gurpik*') || request()->is('users*') ? 'show' : '' }}">

                <a class="{{ request()->is('users*') ? 'active' : '' }}" href="{{ url('/users') }}">
                    <i class="fas fa-users-cog"></i>
                    <span>User</span>
                </a>

                <a class="{{ request()->is('gurpik*') ? 'active' : '' }}" href="{{ route('gurpik.index') }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Guru Piket</span>
                </a>

                <a class="{{ request()->is('siswa*') ? 'active' : '' }}" href="{{ url('/siswa') }}">
                    <i class="fas fa-user-graduate"></i>
                    <span>Murid</span>
                </a>

            </div>

        @endif

        @endif

        {{-- PENGATURAN --}}
        <a class="{{ request()->is('pengaturan') || request()->is('siswa/pengaturan') ? 'active' : '' }}" 
           href="{{ url('/pengaturan') }}">
            <i class="fas fa-cog"></i>
            <h2>Pengaturan</h2>
        </a>

        {{-- LOGOUT --}}
        <a href="{{ url('/logout') }}">
            <i class="fas fa-sign-out-alt"></i>
            <h2>Log out</h2>
        </a>

    </nav>

    <div class="sidebar-footer">
        <p>Logged in as:</p>
        <p>{{ Auth::user()->role }}</p>
    </div>
</div>