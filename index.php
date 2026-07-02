<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Klinik Kampus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="login-page">

    <div class="login-card">
        <div class="logo-box">
            <i class="bi bi-hospital"></i>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">Klinik Kampus</h3>
            <p class="text-muted mb-0">Sistem Informasi Klinik Kampus Sederhana</p>
        </div>

        <form action="dashboard.php" method="get">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" placeholder="Masukkan username">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" placeholder="Masukkan password">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="ingat">
                    <label class="form-check-label" for="ingat">Ingat saya</label>
                </div>
                <a href="#" class="text-decoration-none">Lupa password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>
    </div>

</body>
</html>