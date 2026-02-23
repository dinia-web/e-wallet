@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- CARD TITLE -->
    <div class="page-title">
        <i class="fas fa-chalkboard-teacher"></i>
        <h1>Kelas</h1>
    </div>

    <!-- CARD DATA -->
    <div class="card1">

        <div class="header">
            <h2>List Kelas</h2>
            <button class="btn btn-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> Tambah Data
            </button>
        </div>

        <div class="controls">
            <div class="entries">
                <label>Show</label>
                <select onchange="changeLimit(this.value)">
                    <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <label>entries</label>
            </div>

            <form method="GET" class="search">
                <label>Search:</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelas as $i => $row)
                    <tr>
                        <td>{{ $kelas->firstItem() + $i }}</td>
                        <td>{{ $row->klas }}</td>
                        <td>

                            <!-- EDIT -->
                            <button class="btn-edit"
                                onclick="openEditModal('{{ $row->id_kelas }}','{{ $row->klas }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('kelas.destroy', $row->id_kelas) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" onclick="return confirm('Yakin hapus data?')">
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
                Showing {{ $kelas->firstItem() }}
                to {{ $kelas->lastItem() }}
                of {{ $kelas->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $kelas->links() }}
            </div>
        </div>

    </div>
</div>

<!-- =========================
    MODAL TAMBAH
========================= -->
<div id="modalTambah" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Tambah Kelas</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>

        <form method="POST" action="{{ route('kelas.store') }}">
            @csrf

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="klas" placeholder="Masukkan Kelas" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
            </div>
        </form>

    </div>
</div>

<!-- =========================
    MODAL EDIT
========================= -->
<div id="modalEdit" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Edit Kelas</h3>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="klas" id="edit_kelas" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Update</button>
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
            </div>
        </form>

    </div>
</div>

@include('layout.footer')