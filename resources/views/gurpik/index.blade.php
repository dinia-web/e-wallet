{{-- gurpik.blade.php --}}
@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- HEADER (FIX TIDAK PAKAI .card) -->
    <div class="page-title">
        <i class="fas fa-user-tie"></i>
        <h1>Guru Piket</h1>
    </div>

    <!-- CARD UTAMA -->
    <div class="card1">

        <div class="header">
            <h2>List Guru Piket</h2>
            <button class="btn btn-primary" onclick="openModalGurpik()">
            <i class="fas fa-plus"></i> Tambah Data</button>
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
                        <th>Nama Guru</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gurpik as $i => $g)
                    <tr>
                        <td>{{ $gurpik->firstItem() + $i }}</td>
                        <td>{{ $g->gurpi }}</td>
                        <td>
                             <!-- EDIT -->
                             <button class="btn-edit"
                                onclick="openEditModalGurpik('{{ $g->id_guru }}','{{ $g->gurpi }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <form action="{{ route('gurpik.destroy', $g->id_guru) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" onclick="return confirm('Yakin hapus?')">
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
                Showing {{ $gurpik->firstItem() }} to {{ $gurpik->lastItem() }} 
                of {{ $gurpik->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $gurpik->links() }}
            </div>
        </div>

    </div>
</div>

<!-- MODAL -->
<div id="modalGurpik" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Tambah Guru Piket</h3>
            <span class="close" onclick="closeModalGurpik()">&times;</span>
        </div>

        <form method="POST" action="{{ route('gurpik.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Guru</label>
                <input type="text" name="gurpi" placeholder="Masukkan Nama Guru" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Simpan Data</button>
                <button type="button" class="btn-cancel" onclick="closeModalGurpik()">Batal</button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL EDIT -->
<div id="modalEditGurpik" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Ubah Guru Piket</h3>
            <span class="close" onclick="closeEditModalGurpik()">&times;</span>
        </div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Guru</label>
                <input type="text" name="gurpi" id="editNamaGuru" required>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Update</button>
                <button type="button" class="btn-cancel" onclick="closeEditModalGurpik()">Batal</button>
            </div>
        </form>

    </div>
</div>
@include('layout.footer')