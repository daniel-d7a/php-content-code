<?php
// =====================================================================
// OPTIONAL migration runner (for demonstration purposes).
//
// Applies the numbered .sql files in /migrations in ascending order
// and records each one in the `migrations` table, so re-running it
// only applies what is still pending.
//
// Usage (CLI):  php migrate.php
// Usage (browser): http://localhost/blog/migrate.php
// =====================================================================

define('DB_NO_SELECT', true);
require_once __DIR__ . '/config/config.php';

// The database itself must exist before migrations can run
if (!mysqli_query($conn, 'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')) {
    die('Could not create database: ' . mysqli_error($conn));
}
if (!mysqli_select_db($conn, DB_NAME)) {
    die('Could not select database: ' . mysqli_error($conn));
}

// Tracker table
$createMigrations = "CREATE TABLE IF NOT EXISTS migrations (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(190) NOT NULL UNIQUE,
        applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
if (!mysqli_query($conn, $createMigrations)) {
    die('Could not create the migrations table: ' . mysqli_error($conn));
}

$files = glob(__DIR__ . '/migrations/*.sql');
if ($files) {
    sort($files, SORT_STRING);
}

$log = [];

foreach ($files as $file) {
    $name = basename($file);
    $safeName = mysqli_real_escape_string($conn, $name);

    // Already applied?
    $check = mysqli_query($conn, "SELECT id FROM migrations WHERE migration = '$safeName' LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        $log[] = ['name' => $name, 'status' => 'skipped', 'detail' => 'already applied'];
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        $log[] = ['name' => $name, 'status' => 'failed', 'detail' => 'could not read file'];
        continue;
    }

    if (!mysqli_multi_query($conn, $sql)) {
        $log[] = ['name' => $name, 'status' => 'failed', 'detail' => mysqli_error($conn)];
        continue;
    }

    // Drain all result sets
    do {
        mysqli_store_result($conn);
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    if (mysqli_errno($conn)) {
        $log[] = ['name' => $name, 'status' => 'failed', 'detail' => mysqli_error($conn)];
        continue;
    }

    // Record it
    mysqli_query($conn, "INSERT INTO migrations (migration, applied_at) VALUES ('$safeName', NOW())");

    $log[] = ['name' => $name, 'status' => 'applied', 'detail' => ''];
}

$pending = count(array_filter($log, function ($entry) {
    return $entry['status'] !== 'skipped';
}));

// CLI output
if (php_sapi_name() === 'cli') {
    echo "Migration runner - database: " . DB_NAME . PHP_EOL;
    echo str_repeat('-', 60) . PHP_EOL;
    if (empty($log)) {
        echo "No migration files found in /migrations" . PHP_EOL;
    }
    foreach ($log as $entry) {
        printf("%-8s %s %s%s", strtoupper($entry['status']), $entry['name'],
            $entry['detail'] !== '' ? '- ' . $entry['detail'] : '', PHP_EOL);
    }
    echo str_repeat('-', 60) . PHP_EOL;
    echo "Done. $pending migration(s) processed." . PHP_EOL;
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Migrations - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 720px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h4 mb-4"><i class="bi bi-arrow-repeat"></i> Migration Runner <small class="text-muted fs-6">(optional demo)</small></h1>

            <?php if (empty($log)) { ?>
                <div class="alert alert-info mb-0">No migration files found in <code>/migrations</code>.</div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>File</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($log as $entry) {
                            $badge = $entry['status'] === 'applied' ? 'success'
                                : ($entry['status'] === 'failed' ? 'danger' : 'secondary');
                        ?>
                            <tr>
                                <td><span class="badge bg-<?= $badge ?>"><?= e($entry['status']) ?></span></td>
                                <td><?= e($entry['name']) ?></td>
                                <td class="text-muted"><?= e($entry['detail']) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div class="d-flex gap-2 mt-4">
                <a href="migrate.php" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i> Run Again</a>
                <a href="index.php?page=home" class="btn btn-primary">Go to the Blog</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
