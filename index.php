<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Klinik Kampus</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="login-page">

    <div class="login-card">

        <div class="logo-box">
            <i class="bi bi-hospital"></i>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">Klinik Kampus</h3>
            <p class="text-muted mb-0">
                Sistem Informasi Klinik Kampus Sederhana
            </p>
        </div>

        <form action="cek_login.php" method="POST">

            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="ingat">
                    <label class="form-check-label" for="ingat">
                        Ingat Saya
                    </label>
                </div>

                <a href="#" class="text-decoration-none">
                    Lupa Password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Login
            </button>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($error): ?>
    <script>
        Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '<?= htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#146c94',

        backdrop: 'rgba(0,0,0,0.45)',
        heightAuto: false,
        scrollbarPadding: false,

        showClass: {
            popup: ''
        },
        hideClass: {
            popup: ''
        }
    });
    </script>
    <?php endif; ?>

</body>
</html>