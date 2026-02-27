@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <div class="page-title">
        <i class="fas fa-user-graduate"></i>
        <h1>Siswa</h1>
    </div>

    <div class="card1">

        <div class="header">
            <h2>List Siswa</h2>
            <button class="btn btn-primary" onclick="openModalSiswa()">
                <i class="fas fa-plus"></i> Tambah Data
            </button>
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
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $i => $s)
                    <tr>
                        <td>{{ $siswa->firstItem() + $i }}</td>
                        <td>{{ $s->nis }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->kelas }}</td>
                        <td>
                            <!-- EDIT -->
                            <button class="btn-edit"
                                onclick='openEditModalSiswa(@json($s->nis), @json($s->nama), @json($s->kelas))'>
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <form action="{{ route('siswa.destroy', $s->nis) }}" 
                                  method="POST" 
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" 
                                    onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pagination">
            <span>
                Showing {{ $siswa->firstItem() }} to {{ $siswa->lastItem() }} 
                of {{ $siswa->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $siswa->links() }}
            </div>
        </div>

    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modalSiswa" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Tambah Siswa</h3>
            <span class="close" onclick="closeModalSiswa()">&times;</span>
        </div>

        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" required>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeModalSiswa()">Batal</button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL EDIT -->
<div id="modalEditSiswa" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Edit Siswa</h3>
            <span class="close" onclick="closeEditModalSiswa()">&times;</span>
        </div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" id="editNis" required>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" id="editNama" required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" id="editKelas" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Update</button>
                <button type="button" class="btn-cancel" onclick="closeEditModalSiswa()">Batal</button>
            </div>
        </form>

    </div>
</div>

@include('layout.footer')