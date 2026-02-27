{{-- users.blade.php --}}
@include('layout.header')
@include('layout.sidebar')

<div class="main-content gurpik-page" id="main-content">

    <!-- HEADER -->
    <div class="page-title">
        <i class="fas fa-users"></i>
        <h1>Manajemen User</h1>
    </div>

    <!-- CARD UTAMA -->
    <div class="card1">

        <div class="header">
            <h2>List User</h2>
            <button class="btn btn-primary" onclick="openModalUser()">
                <i class="fas fa-plus"></i> Tambah User
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
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-role {{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>

                            <!-- EDIT -->
                            <button class="btn-edit"
                                onclick="openEditModalUser(
                                    '{{ $user->id_user }}',
                                    '{{ $user->username }}',
                                    '{{ $user->email }}',
                                    '{{ $user->role }}'
                                )">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            @if(auth()->id() != $user->id_user)
                            <form action="{{ route('users.destroy', $user->id_user) }}" 
                                  method="POST" 
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" 
                                    onclick="return confirm('Yakin hapus user ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pagination">
            <span>
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} 
                of {{ $users->total() }} entries
            </span>

            <div class="pagination-controls">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div id="modalUser" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah User</h3>
            <span class="close" onclick="closeModalUser()">&times;</span>
        </div>
        <div id="errorMessage" class="error-box">
                <span id="errorText"></span>
        </div>

<form id="formTambahUser">
    @csrf

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="guru">Guru</option>
        </select>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn-save">Simpan</button>
        <button type="button" class="btn-cancel" onclick="closeModalUser()">Batal</button>
    </div>
</form>

    </div>
</div>

<!-- MODAL EDIT USER -->
<div id="modalEditUser" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>Ubah User</h3>
            <span class="close" onclick="closeEditModalUser()">&times;</span>
        </div>

        <form id="formEditUser" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="editUsername" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="editEmail" required>
            </div>

            <div class="form-group">
                <label>Password (Opsional)</label>
                <input type="password" name="password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="editRole" required>
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save">Update</button>
                <button type="button" class="btn-cancel" onclick="closeEditModalUser()">Batal</button>
            </div>
        </form>

    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    const form = document.getElementById("formTambahUser");

    form.addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(form);

        fetch("{{ route('users.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            let errorDiv = document.getElementById("errorMessage");
            let errorText = document.getElementById("errorText");

            if(data.status === false){

                errorDiv.style.display = "block";
                errorText.innerHTML = "";

                Object.values(data.errors).forEach(function(error){
                    errorText.innerHTML += "• " + error[0] + "<br>";
                });

            } else {

                errorDiv.style.display = "none";
                form.reset();
                closeModalUser();
                location.reload();
            }

        })
        .catch(error => {
            console.log("Fetch Error:", error);
        });

    });

});
</script>

@include('layout.footer')