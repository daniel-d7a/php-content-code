<?php
// =====================================================================
// Shared helper functions (procedural, no classes)
// Database access is done with plain mysqli_* calls (no wrappers).
// =====================================================================

// ---------------------------------------------------------------------
// OUTPUT / INPUT
// ---------------------------------------------------------------------

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim((string) $data)));
}

// ---------------------------------------------------------------------
// FLASH MESSAGES
// ---------------------------------------------------------------------

function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ---------------------------------------------------------------------
// FORM STATE (errors + old input survive the POST/Redirect/GET cycle)
// ---------------------------------------------------------------------

function setFormState($errors, $old = [])
{
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $old;
}

function getFormErrors()
{
    $errors = $_SESSION['form_errors'] ?? [];
    unset($_SESSION['form_errors']);
    return $errors;
}

function getFormOld()
{
    $old = $_SESSION['form_old'] ?? [];
    unset($_SESSION['form_old']);
    return $old;
}

// ---------------------------------------------------------------------
// AUTH (session based)
// ---------------------------------------------------------------------

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to access that page.');
        header('Location: index.php?page=login');
        exit;
    }
}

// Can the current user edit/delete this post? (author or admin)
function canEdit($post)
{
    if (!isLoggedIn() || !is_array($post)) {
        return false;
    }
    return (int) $_SESSION['user_id'] === (int) $post['user_id'] || isAdmin();
}

// ---------------------------------------------------------------------
// POSTS (plain mysqli reads; ids are cast to int before querying)
// ---------------------------------------------------------------------

function getAllPosts()
{
    global $conn;
    $sql = "SELECT p.*, u.name AS author_name
            FROM posts p
            INNER JOIN users u ON u.id = p.user_id
            ORDER BY p.created_at DESC, p.id DESC";
    $result = mysqli_query($conn, $sql);
    $posts = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }
    }
    return $posts;
}

function getPostById($id)
{
    global $conn;
    $id = (int) $id;
    $sql = "SELECT p.*, u.name AS author_name, u.role AS author_role
            FROM posts p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.id = $id
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row ?: null;
    }
    return null;
}

// ---------------------------------------------------------------------
// IMAGE UPLOADS
// ---------------------------------------------------------------------

// Validates and saves the file from $_FILES['image'].
// Returns the stored filename, or '' when nothing was uploaded.
// Pushes a readable message into $errors when something is wrong.
function handleImageUpload(&$errors)
{
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Image upload failed (error code ' . (int) $file['error'] . ').';
        return '';
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $errors[] = 'Image must be 2 MB or smaller.';
        return '';
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        $errors[] = 'Only JPG, JPEG, PNG, GIF or WEBP images are allowed.';
        return '';
    }

    $mime = mime_content_type($file['tmp_name']);
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowedMimes, true)) {
        $errors[] = 'The uploaded file is not a valid image.';
        return '';
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], UPLOADS_PATH . '/' . $filename)) {
        $errors[] = 'Could not save the uploaded image.';
        return '';
    }

    return $filename;
}

function deleteImageFile($filename)
{
    if ($filename === '' || $filename === null) {
        return;
    }
    // Never trust the stored value blindly - only a plain filename is allowed
    $filename = basename($filename);
    $path = UPLOADS_PATH . '/' . $filename;
    if (is_file($path)) {
        unlink($path);
    }
}
