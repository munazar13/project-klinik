<?php
include "koneksi.php";
include "session.php";

if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman kunjungan hanya untuk admin.'); window.location='dashboard.php';</script>";
    exit;
}

function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function bersih($teks) {
    return trim($teks ?? '');
}

$edit = false;
$data_edit = [];

// Hapus kunjungan. Jika kunjungan memakai obat, stok obat dikembalikan.
if (isset($_GET['hapus'])) {
    $id_kunjungan = (int) $_GET['hapus'];
    $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kunjungan WHERE id_kunjungan=$id_kunjungan"));

    if ($lama) {
        mysqli_begin_transaction($conn);

        $sukses = true;
        $id_obat_lama = (int) $lama['id_obat'];
        $jumlah_lama = (int) $lama['jumlah_obat'];

        if ($id_obat_lama > 0 && $jumlah_lama > 0) {
            $sukses = mysqli_query($conn, "UPDATE obat SET stok = stok + $jumlah_lama WHERE id_obat=$id_obat_lama");
        }

        if ($sukses) {
            $sukses = mysqli_query($conn, "DELETE FROM kunjungan WHERE id_kunjungan=$id_kunjungan");
        }

        if ($sukses) {
            mysqli_commit($conn);
            header("Location: kunjungan.php?pesan=hapus");
            exit;
        }

        mysqli_rollback($conn);
    }

    echo "<script>alert('Data kunjungan gagal dihapus.'); window.location='kunjungan.php';</script>";
    exit;
}

// Simpan atau update kunjungan.
if (isset($_POST['simpan_kunjungan'])) {
    $id_kunjungan = (int) ($_POST['id_kunjungan'] ?? 0);
    $id_pasien = (int) ($_POST['id_pasien'] ?? 0);
    $id_obat = (int) ($_POST['id_obat'] ?? 0);
    $jumlah_obat = (int) ($_POST['jumlah_obat'] ?? 0);

    $tanggal = mysqli_real_escape_string($conn, bersih($_POST['tanggal_kunjungan']));
    $jam = mysqli_real_escape_string($conn, bersih($_POST['jam_kunjungan']));
    $keluhan = mysqli_real_escape_string($conn, bersih($_POST['keluhan']));
    $tindakan = mysqli_real_escape_string($conn, bersih($_POST['tindakan']));
    $status = mysqli_real_escape_string($conn, bersih($_POST['status_kunjungan']));
    $petugas = mysqli_real_escape_string($conn, bersih($_POST['petugas']));

    $status_benar = in_array($status, ['Menunggu', 'Diproses', 'Selesai']);

    if ($tanggal == '' || $jam == '' || $id_pasien <= 0 || !$status_benar) {
        echo "<script>alert('Tanggal, jam, pasien, dan status wajib diisi.');</script>";
    } elseif ($id_obat <= 0 && $jumlah_obat > 0) {
        echo "<script>alert('Jika tidak memilih obat, jumlah obat harus 0.');</script>";
    } elseif ($id_obat > 0 && $jumlah_obat <= 0) {
        echo "<script>alert('Jika memilih obat, jumlah obat harus lebih dari 0.');</script>";
    } else {
        $boleh_simpan = true;
        $pesan_error = '';

        // Cek stok dulu sebelum data disimpan.
        if ($id_obat > 0) {
            $obat_baru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok FROM obat WHERE id_obat=$id_obat"));
            $stok_tersedia = $obat_baru ? (int) $obat_baru['stok'] : 0;

            // Saat edit obat yang sama, stok lama dianggap dikembalikan dulu.
            if ($id_kunjungan > 0) {
                $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_obat, jumlah_obat FROM kunjungan WHERE id_kunjungan=$id_kunjungan"));
                if ($lama && (int) $lama['id_obat'] == $id_obat) {
                    $stok_tersedia = $stok_tersedia + (int) $lama['jumlah_obat'];
                }
            }

            if ($jumlah_obat > $stok_tersedia) {
                $boleh_simpan = false;
                $pesan_error = "Stok obat tidak cukup. Stok tersedia hanya $stok_tersedia.";
            }
        }

        if (!$boleh_simpan) {
            echo "<script>alert('$pesan_error');</script>";
        } else {
            mysqli_begin_transaction($conn);
            $sukses = true;

            // Kalau edit, kembalikan stok lama dulu supaya tidak dobel pengurangan.
            if ($id_kunjungan > 0) {
                $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kunjungan WHERE id_kunjungan=$id_kunjungan"));

                if ($lama) {
                    $id_obat_lama = (int) $lama['id_obat'];
                    $jumlah_lama = (int) $lama['jumlah_obat'];

                    if ($id_obat_lama > 0 && $jumlah_lama > 0) {
                        $sukses = mysqli_query($conn, "UPDATE obat SET stok = stok + $jumlah_lama WHERE id_obat=$id_obat_lama");
                    }
                } else {
                    $sukses = false;
                }
            }

            // Kurangi stok obat baru jika kunjungan memakai obat.
            if ($sukses && $id_obat > 0 && $jumlah_obat > 0) {
                $sukses = mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah_obat WHERE id_obat=$id_obat");
            }

            $id_obat_sql = ($id_obat > 0) ? $id_obat : "NULL";
            $jumlah_obat_sql = ($id_obat > 0) ? $jumlah_obat : 0;

            if ($sukses && $id_kunjungan > 0) {
                $sukses = mysqli_query($conn, "
                    UPDATE kunjungan SET
                        id_pasien=$id_pasien,
                        id_obat=$id_obat_sql,
                        tanggal_kunjungan='$tanggal',
                        jam_kunjungan='$jam',
                        keluhan='$keluhan',
                        tindakan='$tindakan',
                        jumlah_obat=$jumlah_obat_sql,
                        status_kunjungan='$status',
                        petugas='$petugas'
                    WHERE id_kunjungan=$id_kunjungan
                ");
            }

            if ($sukses && $id_kunjungan <= 0) {
                $sukses = mysqli_query($conn, "
                    INSERT INTO kunjungan
                    (id_pasien, id_obat, tanggal_kunjungan, jam_kunjungan, keluhan, tindakan, jumlah_obat, status_kunjungan, petugas)
                    VALUES
                    ($id_pasien, $id_obat_sql, '$tanggal', '$jam', '$keluhan', '$tindakan', $jumlah_obat_sql, '$status', '$petugas')
                ");
            }

            if ($sukses) {
                mysqli_commit($conn);
                header("Location: kunjungan.php?pesan=simpan");
                exit;
            }

            mysqli_rollback($conn);
            echo "<script>alert('Data kunjungan gagal disimpan.');</script>";
        }
    }
}

// Ambil data kunjungan untuk edit.
if (isset($_GET['edit'])) {
    $id_kunjungan = (int) $_GET['edit'];
    $ambil = mysqli_query($conn, "SELECT * FROM kunjungan WHERE id_kunjungan=$id_kunjungan");
    $data_edit = mysqli_fetch_assoc($ambil);

    if ($data_edit) {
        $edit = true;
    }
}

$pasien = mysqli_query($conn, "SELECT * FROM pasien ORDER BY nama_pasien ASC");
$obat = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat ASC");

$tgl_awal = mysqli_real_escape_string($conn, bersih($_GET['tgl_awal'] ?? ''));
$tgl_akhir = mysqli_real_escape_string($conn, bersih($_GET['tgl_akhir'] ?? ''));
$where_tanggal = '';

if ($tgl_awal != '' && $tgl_akhir != '') {
    $where_tanggal = "WHERE k.tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
} elseif ($tgl_awal != '') {
    $where_tanggal = "WHERE k.tanggal_kunjungan >= '$tgl_awal'";
} elseif ($tgl_akhir != '') {
    $where_tanggal = "WHERE k.tanggal_kunjungan <= '$tgl_akhir'";
}

$data = mysqli_query($conn, "
    SELECT k.*, p.nama_pasien
    FROM kunjungan k
    JOIN pasien p ON k.id_pasien = p.id_pasien
    $where_tanggal
    ORDER BY k.id_kunjungan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Kunjungan</title>
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
                </div>
            </li>
            <li class="dropdown-css">
                <a href="#" class="active">Transaksi <i class="bi bi-chevron-down ms-1"></i></a>
                <div class="dropdown-css-menu">
                    <a href="kunjungan.php" class="active">Input Kunjungan</a>
                    <a href="laporan.php">Rekap Kunjungan</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<main class="container">
    <section class="page-header">
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'simpan') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data kunjungan berhasil disimpan dan stok obat sudah disesuaikan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus') { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data kunjungan berhasil dihapus dan stok obat dikembalikan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <h1 class="fw-bold mb-2">Input Kunjungan Pasien</h1>
        <p class="mb-0">Catat kunjungan pasien. Jika obat diberikan, stok obat otomatis berkurang.</p>
    </section>

    <section class="row g-4">
        <div class="col-lg-4">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3"><?= $edit ? 'Edit Kunjungan' : 'Form Kunjungan'; ?></h5>

                <form action="" method="POST" class="validate-form">
                    <?php if ($edit) { ?>
                        <input type="hidden" name="id_kunjungan" value="<?= e($data_edit['id_kunjungan']); ?>">
                    <?php } ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control" value="<?= $edit ? e($data_edit['tanggal_kunjungan']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Kunjungan</label>
                        <input type="time" name="jam_kunjungan" class="form-control" value="<?= $edit ? e($data_edit['jam_kunjungan']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pasien</label>
                        <select name="id_pasien" class="form-select" required>
                            <option value="">Pilih pasien</option>
                            <?php while ($p = mysqli_fetch_assoc($pasien)) { ?>
                                <option value="<?= e($p['id_pasien']); ?>" <?= ($edit && $data_edit['id_pasien'] == $p['id_pasien']) ? 'selected' : ''; ?>>
                                    <?= e($p['nama_pasien']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keluhan</label>
                        <textarea name="keluhan" class="form-control" rows="2"><?= $edit ? e($data_edit['keluhan']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindakan</label>
                        <textarea name="tindakan" class="form-control" rows="2"><?= $edit ? e($data_edit['tindakan']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Obat</label>
                        <select name="id_obat" class="form-select">
                            <option value="">Tidak ada obat</option>
                            <?php while ($o = mysqli_fetch_assoc($obat)) { ?>
                                <option value="<?= e($o['id_obat']); ?>" <?= ($edit && (string) $data_edit['id_obat'] === (string) $o['id_obat']) ? 'selected' : ''; ?>>
                                    <?= e($o['nama_obat']); ?> - Stok: <?= e($o['stok']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Obat</label>
                        <input type="number" name="jumlah_obat" class="form-control" min="0" value="<?= $edit ? e($data_edit['jumlah_obat']) : '0'; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Kunjungan</label>
                        <select name="status_kunjungan" class="form-select" required>
                            <option value="Menunggu" <?= ($edit && $data_edit['status_kunjungan'] == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Diproses" <?= ($edit && $data_edit['status_kunjungan'] == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                            <option value="Selesai" <?= ($edit && $data_edit['status_kunjungan'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Petugas</label>
                        <input type="text" name="petugas" class="form-control" placeholder="Nama petugas" value="<?= $edit ? e($data_edit['petugas']) : ''; ?>">
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" name="simpan_kunjungan">
                            <?= $edit ? 'Update Kunjungan' : 'Simpan Kunjungan'; ?>
                        </button>
                        <?php if ($edit) { ?>
                            <a href="kunjungan.php" class="btn btn-outline-secondary">Batal Edit</a>
                        <?php } else { ?>
                            <button class="btn btn-outline-secondary" type="reset">Reset Form</button>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card-modern p-4">
                <h5 class="fw-bold mb-3">Data Kunjungan</h5>

                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= e($tgl_awal); ?>">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= e($tgl_akhir); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button class="btn btn-primary w-100" type="submit">Filter</button>
                    </div>
                    <?php if ($tgl_awal != '' || $tgl_akhir != '') { ?>
                        <div class="col-12">
                            <a href="kunjungan.php" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                        </div>
                    <?php } ?>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Keluhan</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($data) > 0) {
                            while ($d = mysqli_fetch_assoc($data)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= e(date('d-m-Y', strtotime($d['tanggal_kunjungan']))); ?></td>
                                <td><?= e($d['nama_pasien']); ?></td>
                                <td><?= e($d['keluhan']); ?></td>
                                <td><?= e($d['tindakan']); ?></td>
                                <td>
                                    <?php if ($d['status_kunjungan'] == 'Selesai') { ?>
                                        <span class="badge badge-soft-success">Selesai</span>
                                    <?php } elseif ($d['status_kunjungan'] == 'Diproses') { ?>
                                        <span class="badge badge-soft-warning">Diproses</span>
                                    <?php } else { ?>
                                        <span class="badge badge-soft-danger">Menunggu</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="kunjungan.php?edit=<?= e($d['id_kunjungan']); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <a href="kunjungan.php?hapus=<?= e($d['id_kunjungan']); ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-danger">Data kunjungan tidak ditemukan.</td>
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
