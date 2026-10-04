<?php
// =====================================================================
// One-shot initializer: executes database.sql (schema + sample data).
// Safe to re-run - it drops and recreates the tables.
// Usage: http://localhost/blog/init_db.php
// =====================================================================

define('DB_NO_SELECT', true);
require_once __DIR__ . '/config/config.php';

$sqlFile = __DIR__ . '/database.sql';
$sql = file_get_contents($sqlFile);

$success = false;
$error = '';

if ($sql === false) {
    $error = 'Could not read database.sql';
} elseif (mysqli_multi_query($conn, $sql)) {
    // Drain every result set so the whole script runs to completion
    do {
        mysqli_store_result($conn);
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    if (mysqli_errno($conn)) {
        $error = mysqli_error($conn);
    } else {
        $success = true;
    }
} else {
    $error = mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Initialize Database - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 640px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 text-center">
            <h1 class="h4 mb-4"><i class="bi bi-database-gear"></i> Database Initialization</h1>

            <?php if ($success) { ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    Database <strong><?= DB_NAME ?></strong> is ready: tables created and sample data inserted.
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <a href="index.php?page=home" class="btn btn-primary">Go to the Blog</a>
                    <a href="index.php?page=login" class="btn btn-outline-primary">Login</a>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Sample admin: admin@admin.com / admin123
                </p>
            <?php } else { ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> Initialization failed.
                    <div class="mt-2"><code><?= e($error) ?></code></div>
                </div>
                <a href="init_db.php" class="btn btn-outline-primary">Try Again</a>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
