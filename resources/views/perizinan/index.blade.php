@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- HEADER -->
    <div class="page-title">
        <i class="fas fa-file-medical"></i>
        <h1>Perizinan</h1>
    </div>

    <div class="card1">

<div class="header">
    <h2>List Perizinan</h2>

    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'siswa')
    <a href="{{ route('perizinan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajukan Izin
    </a>
    @endif
</div>

             <!-- FILTER -->
        <div class="controls">

            <div class="entries">
                <label>Show</label>
                <select onchange="changeLimit(this.value)">
                    <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                </select>
                <label>entries</label>
            </div>
        <form method="GET" class="search">
            <input type="hidden" name="all" value="{{ request('all') }}">

            <label>Search:</label>
            <input type="text" name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari data...">

            <button type="submit">Cari</button>
        </form>

        </div>

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Wali Kelas</th>
                        <th>Status</th>
                        <th>Alasan</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($perizinan as $i => $p)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $p->siswa->nis ?? '-' }}</td>

                        <td>{{ $p->siswa->nama ?? '-' }}</td>

                 
                        <td>{{ $p->guru->username ?? '-' }}</td>
                        <td>
                            <span class="status-badge 
                            {{ $p->jenis == 'izin' ? 'izin' : '' }}
                            {{ $p->jenis == 'sakit' ? 'sakit' : '' }}">
                            {{ ucfirst($p->jenis) }}
                            </span>
                            </td>
                        <td>{{ $p->keterangan }}</td>
                    

                        <td>
                        @if($p->file)
                            <a href="{{ asset('uploads/perizinan/'.$p->file) }}" target="_blank" class="btn-detail">
                                <i class="fas fa-image"></i> Lihat
                            </a>
                        @else
                            -
                        @endif
                        </td>
                        <td>

                        {{-- ADMIN --}}
                        @if(auth()->user()->role == 'admin')
                           
                        <a href="{{ route('perizinan.show', $p) . '?' . http_build_query(request()->only('search','limit','all')) }}" 
                        class="btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        <form action="{{ route('perizinan.destroy',$p) }}" 
                            method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete"
                        onclick="return confirm('Hapus data ini?')">
                        <i class="fas fa-trash"></i> Hapus
                        </button>
                        </form>

                        {{-- WALI KELAS --}}
                        @elseif(auth()->user()->role == 'guru' && auth()->user()->is_walikelas)

                      <a href="{{ route('perizinan.show', $p) . '?' . http_build_query(request()->only('search','limit','all')) }}" 
                        class="btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        {{-- SISWA --}}
                        @elseif(auth()->user()->role == 'siswa')

                        @if($p->nis == auth()->user()->nis)

                        <a href="{{ route('perizinan.show',$p) }}" class="btn-detail">
                        <i class="fas fa-eye"></i> Detail
                        </a>

                        <form action="{{ route('perizinan.destroy',$p) }}" 
                            method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete"
                        onclick="return confirm('Batalkan izin ini?')">
                        <i class="fas fa-times"></i> Batalkan izin
                        </button>
                        </form>

                        @endif

                        @endif

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
         <!-- PAGINATION -->
       @if(method_exists($perizinan, 'links'))
    <div class="pagination">
        <span>
            Showing {{ $perizinan->firstItem() ?? 0 }}
            to {{ $perizinan->lastItem() ?? 0 }}
            of {{ $perizinan->total() }} entries
        </span>

        <div class="pagination-controls">
            {{ $perizinan->appends(request()->query())->links() }}
        </div>
    </div>
@endif

    </div>

</div>

@include('layout.footer')