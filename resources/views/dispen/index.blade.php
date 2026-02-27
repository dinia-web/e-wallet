{{-- dispen/index.blade.php --}}
@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- HEADER -->
    <div class="page-title">
        <i class="fas fa-file-alt"></i>
        <h1>Dispensasi</h1>
    </div>

    <div class="card1">

        <div class="header">
            <h2>List Dispensasi</h2>
            <a href="{{ url('/auth/dispen') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
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
                        <th>Kelas</th>
                        <th>Guru Pengajar</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispen as $i => $d)
                    <tr>
                        <td>
                            {{ method_exists($dispen, 'firstItem') 
                                ? $dispen->firstItem() + $i 
                                : $loop->iteration }}
                        </td>
                        <td>{{ $d->nis }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->kelas ?? '-' }}</td>
                        <td>{{ $d->guru->username ?? '-' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y') }}
                                {{ \Carbon\Carbon::parse($d->created_at)->format('H:i:s') }}
                            </td>
                       <td>
                            <span class="status-badge {{ strtolower(str_replace(' ', '-', $d->status)) }}">
                            {{ ucfirst($d->status) }}
                        </span>
                        </td>

                        <td>
                            <a href="{{ route('dispen.show', $d->id_dispen) }}" class="btn-detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <form action="{{ route('dispen.destroy', $d->id_dispen) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
       @if(method_exists($dispen, 'links'))
        <div class="pagination">
            <span>
                Showing {{ $dispen->firstItem() ?? 0 }}
                to {{ $dispen->lastItem() ?? 0 }}
                of {{ $dispen->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $dispen->appends(request()->query())->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

@include('layout.footer')
