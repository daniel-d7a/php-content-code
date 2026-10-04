<?php
// =====================================================================
// Configuration: database credentials + path constants
// =====================================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');

define('SITE_NAME', 'My Blog');
define('BASE_PATH', dirname(__DIR__));
define('UPLOADS_PATH', BASE_PATH . '/assets/uploads');
define('UPLOADS_URL', 'assets/uploads');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2 MB

// Make sure the uploads folder exists
if (!is_dir(UPLOADS_PATH)) {
    mkdir(UPLOADS_PATH, 0755, true);
}

// ---------------------------------------------------------------------
// Connection (plain procedural mysqli - no OOP calls)
// PHP 8.1+ throws exceptions by default; classic false-return behaviour
// is what this project's error checks expect.
// init_db.php and migrate.php define DB_NO_SELECT before requiring
// this file because they work before/without the database existing.
// ---------------------------------------------------------------------
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

if (!defined('DB_NO_SELECT')) {
    if (!mysqli_select_db($conn, DB_NAME)) {
        die(
            'Database "' . DB_NAME . '" does not exist yet. ' .
            '<a href="init_db.php">Click here to initialize it.</a>'
        );
    }
}
