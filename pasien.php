<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman pasien hanya untuk admin.'); window.location='dashboard.php';</script>";
    exit;
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function post_trim($key) {
    return trim($_POST[$key] ?? '');
}

$edit = false;
$data_edit = [];

if (isset($_POST['simpan'])) {
    $no_rm         = post_trim('no_rm');
    $nim_nip       = post_trim('nim_nip');
    $nama_pasien   = post_trim('nama_pasien');
    $jenis_kelamin = post_trim('jenis_kelamin');
    $tgl_lahir     = post_trim('tgl_lahir');
    $status_pasien = post_trim('status_pasien');
    $fakultas_unit = post_trim('fakultas_unit');
    $no_hp         = post_trim('no_hp');
    $alamat        = post_trim('alamat');

    if ($no_rm === '' || $nama_pasien === '' || $jenis_kelamin === '' || $status_pasien === '') {
        echo "<script>alert('Gagal! No. RM, Nama Pasien, Jenis Kelamin, dan Status Pasien wajib diisi.');</script>";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO pasien (no_rm, nim_nip, nama_pasien, jenis_kelamin, tanggal_lahir, status_pasien, fakultas_unit, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssssss", $no_rm, $nim_nip, $nama_pasien, $jenis_kelamin, $tgl_lahir, $status_pasien, $fakultas_unit, $no_hp, $alamat);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: pasien.php?pesan=tambah");
            exit;
        }

        echo "<script>alert('Gagal menyimpan data pasien. Pastikan No. RM belum digunakan.');</script>";
    }
}

if (isset($_POST['update'])) {
    $id            = (int) ($_POST['id'] ?? 0);
    $no_rm         = post_trim('no_rm');
    $nim_nip       = post_trim('nim_nip');
    $nama_pasien   = post_trim('nama_pasien');
    $jenis_kelamin = post_trim('jenis_kelamin');
    $tgl_lahir     = post_trim('tgl_lahir');
    $status_pasien = post_trim('status_pasien');
    $fakultas_unit = post_trim('fakultas_unit');
    $no_hp         = post_trim('no_hp');
    $alamat        = post_trim('alamat');

    if ($id <= 0 || $no_rm === '' || $nama_pasien === '' || $jenis_kelamin === '' || $status_pasien === '') {
        echo "<script>alert('Update gagal! Data wajib belum lengkap.');</script>";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE pasien SET no_rm=?, nim_nip=?, nama_pasien=?, jenis_kelamin=?, tanggal_lahir=?, status_pasien=?, fakultas_unit=?, no_hp=?, alamat=? WHERE id_pasien=?");
        mysqli_stmt_bind_param($stmt, "sssssssssi", $no_rm, $nim_nip, $nama_pasien, $jenis_kelamin, $tgl_lahir, $status_pasien, $fakultas_unit, $no_hp, $alamat, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: pasien.php?pesan=update");
            exit;
        }

        echo "<script>alert('Update data gagal. Pastikan No. RM tidak sama dengan pasien lain.');</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM pasien WHERE id_pasien=?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: pasien.php?pesan=hapus");
            exit;
        }
    }

    echo "<script>alert('Data pasien gagal dihapus!');</script>";
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM pasien WHERE id_pasien=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data_edit = mysqli_fetch_assoc($result) ?: [];
        $edit = !empty($data_edit);
    }
}

$cari = trim($_GET['cari'] ?? '');
if ($cari !== '') {
    $keyword = "%" . $cari . "%";
    $stmt = mysqli_prepare($conn, "SELECT * FROM pasien WHERE nama_pasien LIKE ? OR no_rm LIKE ? OR nim_nip LIKE ? ORDER BY id_pasien DESC");
    mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
} else {
    $query = mysqli_query($conn, "SELECT * FROM pasien ORDER BY id_pasien DESC");
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="dropdown-css">
                <a href="#" class="active">Data Master <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="pasien.php" class="active">Data Pasien</a>
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

    <section class="page-header">
        <h1 class="fw-bold mb-2">CRUD Data Pasien</h1>
        <p class="mb-0">Kelola data pasien klinik kampus seperti mahasiswa, dosen, pegawai, dan umum.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3"><?= $edit ? 'Edit Data Pasien' : 'Form Data Pasien'; ?></h5>

                <form action="" method="POST" class="validate-form">
                    <input type="hidden" name="id" value="<?= $edit ? e($data_edit['id_pasien']) : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Rekam Medis</label>
                        <input type="text" class="form-control" name="no_rm" placeholder="Contoh: RM001" required value="<?= $edit ? e($data_edit['no_rm']) : ''; ?>">
                        <div class="invalid-feedback">No. Rekam Medis wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM / NIP</label>
                        <input type="text" class="form-control" name="nim_nip" placeholder="Masukkan NIM atau NIP" value="<?= $edit ? e($data_edit['nim_nip']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pasien</label>
                        <input type="text" class="form-control" name="nama_pasien" placeholder="Masukkan nama pasien" required value="<?= $edit ? e($data_edit['nama_pasien']) : ''; ?>">
                        <div class="invalid-feedback">Nama pasien wajib diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" <?= ($edit && $data_edit['jenis_kelamin']=="Laki-laki") ? "selected" : ""; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($edit && $data_edit['jenis_kelamin']=="Perempuan") ? "selected" : ""; ?>>Perempuan</option>
                        </select>
                        <div class="invalid-feedback">Silakan pilih jenis kelamin.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tgl_lahir" value="<?= $edit ? e($data_edit['tanggal_lahir']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pasien</label>
                        <select class="form-select" name="status_pasien" required>
                            <option value="">Pilih Status Pasien</option>
                            <option value="Mahasiswa" <?= ($edit && $data_edit['status_pasien']=="Mahasiswa") ? "selected" : ""; ?>>Mahasiswa</option>
                            <option value="Dosen" <?= ($edit && $data_edit['status_pasien']=="Dosen") ? "selected" : ""; ?>>Dosen</option>
                            <option value="Pegawai" <?= ($edit && $data_edit['status_pasien']=="Pegawai") ? "selected" : ""; ?>>Pegawai</option>
                            <option value="Umum" <?= ($edit && $data_edit['status_pasien']=="Umum") ? "selected" : ""; ?>>Umum</option>
                        </select>
                        <div class="invalid-feedback">Silakan pilih status pasien.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas / Unit</label>
                        <input type="text" class="form-control" name="fakultas_unit" placeholder="Contoh: FTK" value="<?= $edit ? e($data_edit['fakultas_unit']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="Masukkan nomor HP" value="<?= $edit ? e($data_edit['no_hp']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" rows="3" name="alamat" placeholder="Masukkan alamat"><?= $edit ? e($data_edit['alamat']) : ''; ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <?php if($edit){ ?>
                            <button class="btn btn-warning" type="submit" name="update">Update Data</button>
                            <a href="pasien.php" class="btn btn-outline-secondary">Batal Edit</a>
                        <?php } else { ?>
                            <button class="btn btn-primary" type="submit" name="simpan">Simpan Data</button>
                            <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Pasien</h5>
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="cari" class="form-control" placeholder="Cari Nama, NIM/NIP atau No. RM" value="<?= e($cari); ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <?php if($cari !== ''){ ?>
                            <a href="pasien.php" class="btn btn-outline-secondary">Reset</a>
                        <?php } ?>
                    </form>
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
                        <?php
                        $no = 1;
                        if(mysqli_num_rows($query) > 0){
                            while($row = mysqli_fetch_assoc($query)){
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= e($row['no_rm']); ?></td>
                                <td><?= e($row['nama_pasien']); ?></td>
                                <td><span class="badge bg-primary"><?= e($row['status_pasien']); ?></span></td>
                                <td><?= e($row['fakultas_unit']); ?></td>
                                <td><?= e($row['no_hp']); ?></td>
                                <td>
                                    <a href="pasien.php?edit=<?= e($row['id_pasien']); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <a href="pasien.php?hapus=<?= e($row['id_pasien']); ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-danger">Data pasien tidak ditemukan.</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center">Sistem Informasi Klinik Kampus Sederhana</footer>

<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
