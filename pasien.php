<!DOCTYPE html>
<html lang="id">

<head>
    <!-- ======================================================
         BAGIAN HEAD
         Berisi pengaturan halaman, judul, dan file CSS
         ======================================================= -->

    <!-- Menentukan karakter yang digunakan -->
    <meta charset="UTF-8">

    <!-- Membuat tampilan responsive di berbagai ukuran layar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman yang tampil pada tab browser -->
    <title>Data Pasien</title>

    <!-- Memanggil Framework Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Memanggil Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Memanggil CSS buatan sendiri -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<!--===============================================
     HEADER
     Berisi logo aplikasi dan menu navigasi
    =============================================== -->
<header class="topbar py-3">

    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo Sistem Klinik -->
        <a href="dashboard.php" class="brand-box">
            <span class="brand-icon">
                <i class="bi bi-hospital"></i>
            </span>

            <span>Klinik Kampus</span>
        </a>

        <!-- Menu Navigasi -->
        <ul class="nav-menu">

            <li class="dropdown-css">

                <a href="#">
                    Data Master
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <div class="dropdown-css-menu">
                    <a href="pasien.php" class="active">Data Pasien</a>
                    <a href="obat.php">Data Obat</a>
                </div>

            </li>

            <!-- Menu Dropdown Transaksi -->
            <li class="dropdown-css">

                <a href="#">
                    Transaksi
                    <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>

            </li>

            <!-- Menu Logout -->
            <li>
                <a href="#">Logout</a>
            </li>
            <li><a href="#">Logout</a></li>
        </ul>

    </div>

</header>

<!-- =======================================================
     HALAMAN UTAMA
     Berisi notifikasi, form CRUD, dan tabel data pasien
     ======================================================= -->
<main class="container">

    <!-- Menampilkan notifikasi setelah proses tambah, update, atau hapus -->
    <?php if(isset($_GET['pesan'])){ ?>

    <?php if($_GET['pesan']=="tambah"){ ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if($_GET['pesan']=="update"){ ?>
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if($_GET['pesan']=="hapus"){ ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Berhasil!</strong> Data pasien berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

<?php } ?>

<!-- Header Judul Halaman -->
<section class="page-header">
        <h1 class="fw-bold mb-2">CRUD Data Pasien</h1>
        <p class="mb-0">Kelola data pasien klinik kampus seperti mahasiswa, dosen, pegawai, dan umum.</p>
    </section>

    <!-- ===================================================
         BAGIAN FORM INPUT DATA PASIEN
         Form ini digunakan untuk menambah data pasien baru
         maupun mengubah data pasien yang sudah ada.
         =================================================== -->
    <section class="row g-4">

        <!-- ========================================
             KOLOM KIRI
             Berisi Form Input Data Pasien
             ======================================== -->
        <div class="col-lg-4">

            <!-- Card sebagai wadah form -->
            <div class="card-modern p-4">

                <!-- Judul Form -->
                <h5 class="fw-bold mb-3">Form Data Pasien</h5>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Rekam Medis</label>
                        <input type="text" class="form-control" placeholder="Contoh: RM001">
                    </div>

                    <!-- =====================
                         Input NIM / NIP
                         ===================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM / NIP</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM atau NIP">
                    </div>

                    <!-- =======================
                         Input Nama Pasien
                         ======================= -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama pasien">
                    </div>

                    <!-- ========================
                         Pilihan Jenis Kelamin
                         ======================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select class="form-select">
                            <option>Pilih jenis kelamin</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>

                        <!-- Pesan validasi -->
                        <div class="invalid-feedback">
                            Silakan pilih jenis kelamin.
                        </div>

                    </div>

                    <!-- ========================
                         Input Tanggal Lahir
                         ======================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" class="form-control">
                    </div>

                    <!-- ========================
                         Pilihan Status Pasien
                         ======================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pasien</label>
                        <select class="form-select">
                            <option>Mahasiswa</option>
                            <option>Dosen</option>
                            <option>Pegawai</option>
                            <option>Umum</option>
                        </select>

                        <!-- Pesan validasi -->
                        <div class="invalid-feedback">
                            Silakan pilih status pasien.
                        </div>

                    </div>

                    <!-- =========================
                         Input Fakultas / Unit
                         ========================= -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas / Unit</label>
                        <input type="text" class="form-control" placeholder="Contoh: FTK">
                    </div>

                    <!-- =========================
                         Input Nomor HP
                        ========================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" class="form-control" placeholder="Masukkan nomor HP">
                    </div>

                    <!-- =====================
                         Input Alamat
                         ===================== -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" rows="3" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <!--==============================================
                         Tombol Simpan / Update
                         Tombol berubah sesuai mode form.
                        ============================================== -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button">Simpan Data</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>

                </form>

            </div>

        </div>

        <!-- ======================================================
             KOLOM KANAN
             Menampilkan daftar seluruh data pasien yang tersimpan
             di dalam database.
             ====================================================== -->
        <div class="col-lg-8">

            <!-- Card untuk tabel data pasien -->
            <div class="card-modern p-4">

                <!-- ==================================================
                     Bagian Header Tabel
                     Berisi judul tabel dan fitur pencarian data.
                     ================================================== -->
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Pasien</h5>
                    <input type="text" class="form-control w-md-50" placeholder="Cari nama, NIM, NIP, atau No. RM">
                </div>

                <!-- ==================================================
                     TABEL DATA PASIEN
                     Menampilkan seluruh data pasien dari database.
                    =================================================== -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>

                            <tr>
                                <th>No</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Unit</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>RM001</td>
                                <td>Ahmad Fauzi</td>
                                <td><span class="badge bg-primary">Mahasiswa</span></td>
                                <td>FTK</td>
                                <td>081234567890</td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>RM002</td>
                                <td>Nur Aisyah</td>
                                <td><span class="badge bg-primary">Mahasiswa</span></td>
                                <td>FEBI</td>
                                <td>082345678901</td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>RM003</td>
                                <td>Dr. Rahmat Hidayat</td>
                                <td><span class="badge bg-success">Dosen</span></td>
                                <td>FST</td>
                                <td>083456789012</td>
                                <td>
                                    <button class="btn btn-sm btn-warning action-btn" type="button">Edit</button>
                                    <button class="btn btn-sm btn-danger action-btn" type="button">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-0 small">Tombol edit dan hapus hanya rancangan tampilan karena tidak menggunakan PHP dan JavaScript.</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center">
    Sistem Informasi Klinik Kampus Sederhana
</footer>

<!-- ======================================================
     JAVASCRIPT VALIDASI FORM
     Digunakan untuk mengaktifkan validasi Bootstrap
     sehingga form tidak dapat dikirim jika data wajib
     belum diisi.
     ====================================================== -->
<script>
(() => {
    'use strict';

    // Mengambil seluruh form yang menggunakan validasi Bootstrap
    const forms = document.querySelectorAll('.needs-validation');

    // Mengecek setiap form sebelum dikirim
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {

            // Jika ada input yang belum valid,
            // form tidak akan dikirim.
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            // Menampilkan tampilan validasi Bootstrap
            form.classList.add('was-validated');

        }, false);
    });

})();
</script>

<!-- Memanggil file JavaScript Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/app.js"></script>
</body>
</html>