<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<header class="topbar py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="dashboard.php" class="brand-box">
            <span class="brand-icon"><i class="bi bi-hospital"></i></span>
            <span>Klinik Kampus</span>
        </a>

        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="dropdown-css">
                <a href="#">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="pasien.php" class="active">Data Pasien</a>
                    <a href="obat.php">Data Obat</a>
                </div>
            </li>
            <li class="dropdown-css">
                <a href="#">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <h1 class="fw-bold mb-2">CRUD Data Pasien</h1>
        <p class="mb-0">Kelola data pasien klinik kampus seperti mahasiswa, dosen, pegawai, dan umum.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Form Data Pasien</h5>

                <!-- Form dengan class untuk validasi -->
                <form class="validate-form">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Rekam Medis</label>
                        <input type="text" name="no_rm" class="form-control" required placeholder="Contoh: RM001">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM / NIP</label>
                        <input type="text" name="nim_nip" class="form-control" required placeholder="Masukkan NIM atau NIP">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama pasien">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select name="jk" class="form-select" required>
                            <option value="" selected disabled>Pilih jenis kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pasien</label>
                        <select name="status" class="form-select" required>
                            <option value="" selected disabled>Pilih status pasien</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Pegawai">Pegawai</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas / Unit</label>
                        <input type="text" name="unit" class="form-control" required placeholder="Contoh: FTK">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="hp" class="form-control" required placeholder="Masukkan nomor HP">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" required rows="3" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Simpan Data</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Pasien</h5>
                    <!-- Kolom cari TANPA required -->
                    <input
                        id="searchPasien"
                        type="text"
                        class="form-control w-md-50"
                        placeholder="Cari No. RM, Nama, Status..."
                    >
                </div>

                <div class="table-responsive">
                    <!-- Tabel dengan ID khusus -->
                    <table id="tablePasien" class="table table-hover">
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
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
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
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
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
                                    <button class="btn btn-sm btn-warning action-btn" type="button">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn btn-delete">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-0 small">Tombol edit dan hapus berfungsi sebagai tampilan simulasi (belum terhubung ke database).</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center mt-4">
    Sistem Informasi Klinik Kampus Sederhana &copy; 2026
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>