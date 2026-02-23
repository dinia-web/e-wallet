{{-- jampel.blade.php --}}
@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- TITLE -->
    <div class="page-title">
        <i class="fas fa-clock"></i>
        <h1>Jam Pelajaran</h1>
    </div>

    <!-- CARD -->
    <div class="card1">

        <!-- HEADER -->
        <div class="header">
            <h2>List Jam Pelajaran</h2>
            <button class="btn btn-primary" onclick="openModalJampel()">
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
                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <label>entries</label>
            </div>

            <form method="GET" class="search">
                <label>Search:</label>
                <input type="text" name="search" value="{{ request('search') }}">
                <button type="submit">Cari</button>
            </form>

        </div>

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jam Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jampel as $i => $row)
                    <tr>
                        <td>{{ $jampel->firstItem() + $i }}</td>
                        <td>{{ $row->jam }}</td>
                        <td>

                            <!-- EDIT -->
                            <button class="btn-edit"
                                onclick="openEditModalJampel('{{ $row->id_jampel }}','{{ $row->jam }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('jampel.destroy', $row->id_jampel) }}" method="POST" style="display:inline;">
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
                Showing {{ $jampel->firstItem() }} to {{ $jampel->lastItem() }}
                of {{ $jampel->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $jampel->links() }}
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
            <h3>Tambah Jam Pelajaran</h3>
            <span class="close" onclick="closeModalJampel()">&times;</span>
        </div>

        <form method="POST" action="{{ route('jampel.store') }}">
            @csrf

            <div class="form-group">
                <label>Jam Pelajaran</label>
                <input type="text" name="jam" placeholder="Masukkan Jam" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeModalJampel()">Batal</button>
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
            <h3>Edit Jam Pelajaran</h3>
            <span class="close" onclick="closeEditModalJampel()">&times;</span>
        </div>

        <form id="formEditJampel" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Jam Pelajaran</label>
                <input type="text" name="jam" id="edit_jam" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Update</button>
                <button type="button" class="btn-cancel" onclick="closeEditModalJampel()">Batal</button>
            </div>
        </form>

    </div>
</div>

@include('layout.footer')