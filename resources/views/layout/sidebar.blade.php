<div class="sidebar" id="sidebar">
    <nav>

         <a href="{{ url('/dashboard') }}">
            <h2>Menu</h2>
        </a>
        {{-- DASHBOARD --}}
        <a class="{{ request()->is('dashboard') ? 'active' : '' }}" 
           href="{{ url('/dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <h2>Dashboard</h2>
        </a>

        {{-- DISPENSASI --}}
        <a class="{{ request()->is('dispen*') ? 'active' : '' }}" 
           href="{{ url('/dispen') }}">
            <i class="fas fa-file-signature"></i>
            <h2>Dispensasi</h2>
        </a>

       {{-- KHUSUS ADMIN --}}
        @if(Auth::user()->role == 'admin')

            <!-- MENU UTAMA -->
            <div class="menu-item 
                {{ request()->is('kelas*') || request()->is('jam-pelajaran*') || request()->is('gurpik*') || request()->is('users*') ? 'active' : '' }}"
                onclick="toggleSubmenu()">

                <i class="fas fa-cogs"></i>
                <h2>Manajemen</h2>
                <i class="fas fa-chevron-down arrow"></i>
            </div>

            <!-- SUBMENU -->
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
                    <span>Siswa</span>
                </a>

            </div>

        @endif

        {{-- LAPORAN --}}
        <a class="{{ request()->is('laporan') ? 'active' : '' }}" 
           href="{{ url('/laporan') }}"> 
            <i class="fas fa-chart-line"></i>
            <h2>Laporan</h2>
        </a>

        {{-- PENGATURAN --}}
        <a class="{{ request()->is('pengaturan') ? 'active' : '' }}" 
           href="{{ url('/pengaturan') }}">
            <i class="fas fa-cog"></i>
            <h2>Pengaturan</h2>
        </a>

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