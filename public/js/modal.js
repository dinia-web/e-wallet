const togglePassword = document.getElementById("togglePassword");

if (togglePassword) {
    togglePassword.addEventListener("click", function() {
        let passwordField = document.getElementById("password");
        let icon = this;

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace("fa-eye-slash","fa-eye");
        } else {
            passwordField.type = "password";
            icon.classList.replace("fa-eye","fa-eye-slash");
        }
    });
}
function openForgotPasswordModal() {
    const modal = document.getElementById("forgotPasswordModal");
    if (modal) modal.style.display = "flex";
}

function closeForgotPasswordModal() {
    const modal = document.getElementById("forgotPasswordModal");
    if (modal) modal.style.display = "none";
}

window.addEventListener("click", function(event) {
    const modal = document.getElementById("forgotPasswordModal");
    if (event.target === modal) {
        closeForgotPasswordModal();
    }
});

// Auto buka jika ada error/success
document.addEventListener("DOMContentLoaded", function() {
    if (window.hasResetMessage) {
        openForgotPasswordModal();
    }
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    if (sidebar) {
        sidebar.classList.toggle('active');
    }

    if (mainContent) {
        mainContent.classList.toggle('shifted');
    }

    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth <= 768) {
        document.body.classList.toggle('sidebar-open');
        if (overlay) overlay.classList.toggle('active');
    }
}


const profilePic = document.getElementById("navProfilePic");
const logoutMenu = document.getElementById("logoutMenu");

if (profilePic && logoutMenu) {
    profilePic.addEventListener("click", function (event) {
        event.stopPropagation();
        logoutMenu.style.display =
            logoutMenu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function () {
        logoutMenu.style.display = "none";
    });
}

function toggleSubmenu() {
    const submenu = document.getElementById("submenu-manajemen");
    const menu = document.querySelector(".menu-item");

    submenu.classList.toggle("show");
    menu.classList.toggle("active");
}


// ============================
// MODAL GURPIK
// ============================
function openModalGurpik() {
    const modal = document.getElementById("modalGurpik");
    if (modal) modal.style.display = "flex";
}

function closeModalGurpik() {
    const modal = document.getElementById("modalGurpik");
    if (modal) modal.style.display = "none";
}

function openEditModalGurpik(id, nama) {
    const modal = document.getElementById("modalEditGurpik");
    const input = document.getElementById("editNamaGuru");
    const form = document.getElementById("formEdit");

    if (modal && input && form) {
        modal.style.display = "flex";
        input.value = nama;
        form.action = "/gurpik/" + id;
    }
}

function closeEditModalGurpik() {
    const modal = document.getElementById("modalEditGurpik");
    if (modal) modal.style.display = "none";
}


// ============================
// MODAL JAMPEL
// ============================
function openModalJampel() {
    const modal = document.getElementById("modalTambah");
    if (modal) modal.style.display = "flex";
}

function closeModalJampel() {
    const modal = document.getElementById("modalTambah");
    if (modal) modal.style.display = "none";
}

function openEditModalJampel(id, nama) {
    const modal = document.getElementById("modalEdit");
    const input = document.getElementById("edit_jam");
    const form = document.getElementById("formEditJampel");

    if (modal && input && form) {
        modal.style.display = "flex";
        input.value = nama;
        form.action = "/jampel/" + id;
    }
}

function closeEditModalJampel() {
    const modal = document.getElementById("modalEdit");
    if (modal) modal.style.display = "none";
}

//kelas//
function openModal() {
    const modal = document.getElementById("modalTambah");
    if (modal) modal.style.display = "flex";
}

function closeModal() {
    const modal = document.getElementById("modalTambah");
    if (modal) modal.style.display = "none";
}

function openEditModal(id, nama) {
    const modal = document.getElementById("modalEdit");
    const input = document.getElementById("edit_kelas");
    const form = document.getElementById("formEdit");

    if (modal && input && form) {
        modal.style.display = "flex";
        input.value = nama;
        form.action = "/kelas/" + id;
    }
}

function closeEditModal() {
    const modal = document.getElementById("modalEdit");
    if (modal) modal.style.display = "none";
}

function changeLimit(limit) {
    let url = new URL(window.location.href);
    url.searchParams.set('limit', limit);
    window.location.href = url.toString();
}

// ===============================
// USER MANAGEMENT
// ===============================

// Buka Modal Tambah User
function openModalUser() {
    const modal = document.getElementById("modalUser");
    if (modal) modal.style.display = "flex";
}

// Tutup Modal Tambah User
function closeModalUser() {
    const modal = document.getElementById("modalUser");
    if (modal) modal.style.display = "none";
}

// Buka Modal Edit User
function openEditModalUser(id, username, email, role){

    document.getElementById("editUsername").value = username;
    document.getElementById("editEmail").value = email;
    document.getElementById("editRole").value = role;

    let form = document.getElementById("formEditUser");

    form.action = "/users/" + id;  // 🔥 ini penting

    document.getElementById("modalEditUser").style.display = "flex";
}


// Tutup Modal Edit User
function closeEditModalUser() {
    const modal = document.getElementById("modalEditUser");
    if (modal) modal.style.display = "none";
}

// Change Limit Pagination
function changeLimit(limit) {
    const url = new URL(window.location.href);
    url.searchParams.set('limit', limit);
    window.location.href = url.toString();
}

function openmodal(id){
    document.getElementById(id).style.display = 'flex';
}

function closemodal(){
    document.querySelectorAll('.modal').forEach(modal => {
        modal.style.display = 'none';
    });
}

function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');

    const title = document.getElementById('pengaturan-title');

    if (tabId === 'profil') {
        title.innerText = 'Pengaturan - Informasi Akun';
    } else {
        title.innerText = 'Pengaturan - Setup Aplikasi';
    }
}
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("inputFoto");
    const preview = document.getElementById("previewFoto");

    if (input && preview) {
        input.addEventListener("change", function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {

    if (typeof Swal !== "undefined" && window.appConfig) {

        // SUCCESS
        if (window.appConfig.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: window.appConfig.success,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // ERROR (custom)
        if (window.appConfig.error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: window.appConfig.error
            });
        }

        // VALIDATION ERRORS
        if (window.appConfig.errors && window.appConfig.errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: window.appConfig.errors.join("<br>")
            });
        }
    }

});
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".modal form").forEach(form => {

        form.addEventListener("submit", function () {

            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Memproses...",
                    text: "Mohon tunggu",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            form.querySelectorAll("button[type='submit']").forEach(btn => {
                btn.disabled = true;
            });

        });

    });

});
// ============================
// MODAL SISWA
// ============================

// Buka Modal Tambah
function openModalSiswa() {
    const modal = document.getElementById("modalSiswa");
    if (modal) modal.style.display = "flex";
}

// Tutup Modal Tambah
function closeModalSiswa() {
    const modal = document.getElementById("modalSiswa");
    if (modal) modal.style.display = "none";
}

// Buka Modal Edit
function openEditModalSiswa(nis, nama, kelas) {
    const modal = document.getElementById("modalEditSiswa");
    const inputNis = document.getElementById("editNis");
    const inputNama = document.getElementById("editNama");
    const inputKelas = document.getElementById("editKelas");
    const form = document.getElementById("formEdit");

    if (modal && inputNis && inputNama && inputKelas && form) {
        modal.style.display = "flex";
        inputNis.value = nis;
        inputNama.value = nama;
        inputKelas.value = kelas;

        form.action = "/siswa/" + nis;
    }
}

// Tutup Modal Edit
function closeEditModalSiswa() {
    const modal = document.getElementById("modalEditSiswa");
    if (modal) modal.style.display = "none";
}
