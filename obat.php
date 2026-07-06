<?php
include "session.php";


if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['role'] != 'petugas') {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk petugas.');
            window.location='dashboard.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>

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
                    <a href="pasien.php">Data Pasien</a>
                    <a href="obat.php" class="active">Data Obat</a>
                </div>
            </li>
            <li class="dropdown-css">
                <a href="#">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <h1 class="fw-bold mb-2">Data Obat</h1>
        <p class="mb-0">Kelola data obat klinik kampus sebagai data pendukung pencatatan kunjungan.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Form Data Obat</h5>

                <!-- Form dengan class validasi -->
                <form class="validate-form">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Obat</label>
                        <input type="text" name="kode_obat" class="form-control" required placeholder="Contoh: OB001">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control" required placeholder="Masukkan nama obat">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <input type="text" name="kategori" class="form-control" required placeholder="Contoh: Analgesik, Antibiotik">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <select name="satuan" class="form-select" required>
                            <option value="" selected disabled>Pilih satuan</option>
                            <option value="Tablet">Tablet</option>
                            <option value="Kapsul">Kapsul</option>
                            <option value="Botol">Botol</option>
                            <option value="Sachet">Sachet</option>
                            <option value="Tube">Tube</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stok" class="form-control" min="0" required placeholder="Jumlah stok tersedia">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" required rows="3" placeholder="Keterangan tambahan obat"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Simpan Obat</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Obat</h5>
                    <!-- Kolom cari tanpa required -->
                    <input
                        id="searchObat"
                        type="text"
                        class="form-control w-md-50"
                        placeholder="Cari kode atau nama obat..."
                    >
                </div>

                <div class="table-responsive">
                    <!-- Tabel dengan ID khusus -->
                    <table id="tableObat" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>OB001</td>
                                <td>Paracetamol</td>
                                <td>Analgesik</td>
                                <td>Tablet</td>
                                <td><span class="badge bg-success">150</span></td>
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
                                <td>OB002</td>
                                <td>Antasida</td>
                                <td>Obat Lambung</td>
                                <td>Tablet</td>
                                <td><span class="badge bg-warning text-dark">80</span></td>
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
                                <td>OB003</td>
                                <td>Vitamin C</td>
                                <td>Vitamin</td>
                                <td>Tablet</td>
                                <td><span class="badge bg-success">120</span></td>
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

                <p class="text-muted mb-0 small">Tombol hapus akan memunculkan konfirmasi dan menghapus baris data secara tampilan.</p>
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