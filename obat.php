<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'petugas') {
    echo "<script>alert('Akses ditolak! Halaman obat hanya untuk petugas.'); window.location='dashboard.php';</script>";
    exit;
}

function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function bersih($teks) {
    return trim($teks ?? '');
}

$mode = 'tambah';
$data_obat = [];

// Simpan obat baru. Kalau nama/kode obat sudah ada, stoknya ditambahkan.
if (isset($_POST['simpan_obat'])) {
    $kode_obat  = mysqli_real_escape_string($conn, bersih($_POST['kode_obat']));
    $nama_obat  = mysqli_real_escape_string($conn, bersih($_POST['nama_obat']));
    $kategori   = mysqli_real_escape_string($conn, bersih($_POST['kategori']));
    $satuan     = mysqli_real_escape_string($conn, bersih($_POST['satuan']));
    $stok       = (int) ($_POST['stok'] ?? 0);
    $keterangan = mysqli_real_escape_string($conn, bersih($_POST['keterangan']));

    if ($kode_obat == '' || $nama_obat == '' || $stok <= 0) {
        echo "<script>alert('Kode obat, nama obat, dan stok wajib diisi. Stok harus lebih dari 0.');</script>";
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM obat WHERE kode_obat='$kode_obat' OR LOWER(TRIM(nama_obat))=LOWER('$nama_obat') LIMIT 1");
        $obat_lama = mysqli_fetch_assoc($cek);

        if ($obat_lama) {
            $id_obat = (int) $obat_lama['id_obat'];
            mysqli_query($conn, "UPDATE obat SET stok = stok + $stok WHERE id_obat=$id_obat");
            header("Location: obat.php?pesan=stok");
            exit;
        } else {
            $simpan = mysqli_query($conn, "
                INSERT INTO obat (kode_obat, nama_obat, kategori, satuan, stok, keterangan)
                VALUES ('$kode_obat', '$nama_obat', '$kategori', '$satuan', $stok, '$keterangan')
            ");

            if ($simpan) {
                header("Location: obat.php?pesan=tambah");
                exit;
            }

            echo "<script>alert('Data obat gagal disimpan.');</script>";
        }
    }
}

// Tambah stok dari tombol khusus di tabel obat.
if (isset($_POST['tambah_stok'])) {
    $id_obat = (int) ($_POST['id_obat'] ?? 0);
    $stok_masuk = (int) ($_POST['stok_masuk'] ?? 0);

    if ($id_obat <= 0 || $stok_masuk <= 0) {
        echo "<script>alert('Stok masuk harus lebih dari 0.');</script>";
    } else {
        mysqli_query($conn, "UPDATE obat SET stok = stok + $stok_masuk WHERE id_obat=$id_obat");
        header("Location: obat.php?pesan=stok");
        exit;
    }
}

// Update detail obat.
if (isset($_POST['update_obat'])) {
    $id_obat    = (int) ($_POST['id_obat'] ?? 0);
    $kode_obat  = mysqli_real_escape_string($conn, bersih($_POST['kode_obat']));
    $nama_obat  = mysqli_real_escape_string($conn, bersih($_POST['nama_obat']));
    $kategori   = mysqli_real_escape_string($conn, bersih($_POST['kategori']));
    $satuan     = mysqli_real_escape_string($conn, bersih($_POST['satuan']));
    $stok       = (int) ($_POST['stok'] ?? 0);
    $keterangan = mysqli_real_escape_string($conn, bersih($_POST['keterangan']));

    if ($id_obat <= 0 || $kode_obat == '' || $nama_obat == '' || $stok < 0) {
        echo "<script>alert('Data obat belum lengkap.');</script>";
    } else {
        $update = mysqli_query($conn, "
            UPDATE obat SET
                kode_obat='$kode_obat',
                nama_obat='$nama_obat',
                kategori='$kategori',
                satuan='$satuan',
                stok=$stok,
                keterangan='$keterangan'
            WHERE id_obat=$id_obat
        ");

        if ($update) {
            header("Location: obat.php?pesan=update");
            exit;
        }

        echo "<script>alert('Data obat gagal diperbarui.');</script>";
    }
}

// Hapus obat.
if (isset($_GET['hapus'])) {
    $id_obat = (int) $_GET['hapus'];

    if ($id_obat > 0) {
        $hapus = mysqli_query($conn, "DELETE FROM obat WHERE id_obat=$id_obat");

        if ($hapus) {
            header("Location: obat.php?pesan=hapus");
            exit;
        }
    }

    echo "<script>alert('Obat gagal dihapus. Kemungkinan obat sudah dipakai di data kunjungan.'); window.location='obat.php';</script>";
    exit;
}

// Ambil data untuk edit obat.
if (isset($_GET['edit'])) {
    $id_obat = (int) $_GET['edit'];
    $ambil = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat=$id_obat");
    $data_obat = mysqli_fetch_assoc($ambil);

    if ($data_obat) {
        $mode = 'edit';
    }
}

// Ambil data untuk tambah stok.
if (isset($_GET['stok'])) {
    $id_obat = (int) $_GET['stok'];
    $ambil = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat=$id_obat");
    $data_obat = mysqli_fetch_assoc($ambil);

    if ($data_obat) {
        $mode = 'stok';
    }
}

$cari = mysqli_real_escape_string($conn, bersih($_GET['cari'] ?? ''));

if ($cari != '') {
    $query = mysqli_query($conn, "
        SELECT * FROM obat
        WHERE kode_obat LIKE '%$cari%' OR nama_obat LIKE '%$cari%'
        ORDER BY id_obat DESC
    ");
} else {
    $query = mysqli_query($conn, "SELECT * FROM obat ORDER BY id_obat DESC");
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
            <li><a href="obat.php" class="active">Data Obat</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <?php if (isset($_GET['pesan'])) { ?>
        <?php if ($_GET['pesan'] == 'tambah') { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>Berhasil!</strong> Data obat baru berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if ($_GET['pesan'] == 'stok') { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>Berhasil!</strong> Stok obat berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if ($_GET['pesan'] == 'update') { ?>
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <strong>Berhasil!</strong> Data obat berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if ($_GET['pesan'] == 'hapus') { ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>Berhasil!</strong> Data obat berhasil dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
    <?php } ?>

    <section class="page-header">
        <h1 class="fw-bold mb-2">Data Obat</h1>
        <p class="mb-0">Kelola obat baru dan penambahan stok obat yang sudah tersedia.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <?php if ($mode == 'stok') { ?>
                    <h5 class="fw-bold mb-3">Tambah Stok Obat</h5>

                    <div class="alert alert-info small">
                        <div><strong>Nama Obat:</strong> <?= e($data_obat['nama_obat']); ?></div>
                        <div><strong>Stok Saat Ini:</strong> <?= e($data_obat['stok']); ?></div>
                    </div>

                    <form action="" method="POST" class="validate-form">
                        <input type="hidden" name="id_obat" value="<?= e($data_obat['id_obat']); ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stok Masuk</label>
                            <input type="number" name="stok_masuk" class="form-control" min="1" placeholder="Contoh: 50" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-success" type="submit" name="tambah_stok">Tambah Stok</button>
                            <a href="obat.php" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                <?php } else { ?>
                    <h5 class="fw-bold mb-3"><?= ($mode == 'edit') ? 'Edit Data Obat' : 'Tambah Obat Baru'; ?></h5>

                    <?php if ($mode == 'tambah') { ?>
                        <p class="text-muted small mb-3">Jika nama atau kode obat sudah ada, sistem akan menambahkan stok ke obat lama.</p>
                    <?php } ?>

                    <form action="" method="POST" class="validate-form">
                        <input type="hidden" name="id_obat" value="<?= ($mode == 'edit') ? e($data_obat['id_obat']) : ''; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Obat</label>
                            <input type="text" name="kode_obat" class="form-control" placeholder="Contoh: OB001" required value="<?= ($mode == 'edit') ? e($data_obat['kode_obat']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Obat</label>
                            <input type="text" name="nama_obat" class="form-control" placeholder="Masukkan nama obat" required value="<?= ($mode == 'edit') ? e($data_obat['nama_obat']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Contoh: Analgesik" required value="<?= ($mode == 'edit') ? e($data_obat['kategori']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                <option value="">Pilih satuan</option>
                                <option value="Tablet" <?= ($mode == 'edit' && $data_obat['satuan'] == 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                                <option value="Kapsul" <?= ($mode == 'edit' && $data_obat['satuan'] == 'Kapsul') ? 'selected' : ''; ?>>Kapsul</option>
                                <option value="Botol" <?= ($mode == 'edit' && $data_obat['satuan'] == 'Botol') ? 'selected' : ''; ?>>Botol</option>
                                <option value="Sachet" <?= ($mode == 'edit' && $data_obat['satuan'] == 'Sachet') ? 'selected' : ''; ?>>Sachet</option>
                                <option value="Tube" <?= ($mode == 'edit' && $data_obat['satuan'] == 'Tube') ? 'selected' : ''; ?>>Tube</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?= ($mode == 'edit') ? 'Stok Total' : 'Stok Awal / Stok Masuk'; ?></label>
                            <input type="number" name="stok" class="form-control" min="<?= ($mode == 'edit') ? '0' : '1'; ?>" required value="<?= ($mode == 'edit') ? e($data_obat['stok']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan obat"><?= ($mode == 'edit') ? e($data_obat['keterangan']) : ''; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <?php if ($mode == 'edit') { ?>
                                <button class="btn btn-warning" name="update_obat" type="submit">Update Data</button>
                                <a href="obat.php" class="btn btn-outline-secondary">Batal Edit</a>
                            <?php } else { ?>
                                <button class="btn btn-primary" name="simpan_obat" type="submit">Simpan Obat</button>
                                <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                            <?php } ?>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <h5 class="fw-bold mb-0">Daftar Obat</h5>
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="cari" class="form-control" placeholder="Cari kode atau nama obat" value="<?= e($cari); ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                        <?php if ($cari != '') { ?>
                            <a href="obat.php" class="btn btn-outline-secondary">Reset</a>
                        <?php } ?>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
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
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= e($row['kode_obat']); ?></td>
                                <td><?= e($row['nama_obat']); ?></td>
                                <td><?= e($row['kategori']); ?></td>
                                <td><?= e($row['satuan']); ?></td>
                                <td><?= e($row['stok']); ?></td>
                                <td>
                                    <a href="obat.php?edit=<?= e($row['id_obat']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="obat.php?stok=<?= e($row['id_obat']); ?>" class="btn btn-success btn-sm" title="Tambah Stok"><i class="bi bi-plus-circle"></i></a>
                                    <a href="obat.php?hapus=<?= e($row['id_obat']); ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-danger">Data obat tidak ditemukan.</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="footer text-center mt-4">Sistem Informasi Klinik Kampus Sederhana &copy; 2026</footer>

<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
