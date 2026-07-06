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
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li class="dropdown-css">
                <a href="#">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="pasien.php">Data Pasien</a>
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
            <li><a href="logout.php">Logout</a></li>
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

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Rekam Medis</label>
                        <input type="text" class="form-control" placeholder="Contoh: RM001">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM / NIP</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM atau NIP">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama pasien">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select class="form-select">
                            <option>Pilih jenis kelamin</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pasien</label>
                        <select class="form-select">
                            <option>Mahasiswa</option>
                            <option>Dosen</option>
                            <option>Pegawai</option>
                            <option>Umum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas / Unit</label>
                        <input type="text" class="form-control" placeholder="Contoh: FTK">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" class="form-control" placeholder="Masukkan nomor HP">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" rows="3" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button">Simpan Data</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Pasien</h5>
                    <input type="text" class="form-control w-md-50" placeholder="Cari nama, NIM, NIP, atau No. RM">
                </div>

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

</body>
</html>