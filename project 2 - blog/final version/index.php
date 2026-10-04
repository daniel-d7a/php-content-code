<?php
// =====================================================================
// Entry point / router
// Usage: index.php?page=home|post|login|register|add_post|edit_post|...
// =====================================================================

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/functions.php';
require_once __DIR__ . '/core/validation.php';
require_once __DIR__ . '/controllers/auth_controller.php';
require_once __DIR__ . '/controllers/post_controller.php';

// ---------------------------------------------------------------------
// Resolve the page (whitelist - anything unknown falls back to home)
// ---------------------------------------------------------------------
$page = $_GET['page'] ?? 'home';
$allowedPages = ['home', 'post', 'login', 'register', 'add_post', 'edit_post', 'logout', 'delete_post'];
if (!in_array($page, $allowedPages, true)) {
    $page = 'home';
}

// ---------------------------------------------------------------------
// Simple access rules (redirect BEFORE any HTML output)
// ---------------------------------------------------------------------
if (($page === 'login' || $page === 'register') && isLoggedIn()) {
    header('Location: index.php?page=home');
    exit;
}
if ($page === 'add_post' && !isLoggedIn()) {
    setFlash('error', 'Please login to create a post.');
    header('Location: index.php?page=login');
    exit;
}

// ---------------------------------------------------------------------
// Controllers: handle POST data / side effects first.
// They redirect + exit on success or failure, so no HTML has been
// output yet (headers are always safe to send).
// ---------------------------------------------------------------------
switch ($page) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'add_post':
        handleAddPost();
        break;
    case 'edit_post':
        handleEditPost();
        break;
    case 'delete_post':
        handleDeletePost();
        break;
}

// ---------------------------------------------------------------------
// Data for the views
// ---------------------------------------------------------------------
$post = null;
$posts = [];

switch ($page) {
    case 'home':
        $posts = getAllPosts();
        break;

    case 'post':
        $post = getPostById($_GET['id'] ?? 0);
        if (!$post) {
            setFlash('error', 'Post not found.');
            header('Location: index.php?page=home');
            exit;
        }
        break;

    case 'edit_post':
        $post = getPostById($_GET['id'] ?? 0);
        if (!$post) {
            setFlash('error', 'Post not found.');
            header('Location: index.php?page=home');
            exit;
        }
        if (!isLoggedIn()) {
            setFlash('error', 'Please login to edit this post.');
            header('Location: index.php?page=login');
            exit;
        }
        if (!canEdit($post)) {
            setFlash('error', 'You are not allowed to edit this post.');
            header('Location: index.php?page=post&id=' . (int) $post['id']);
            exit;
        }
        break;
}

// ---------------------------------------------------------------------
// Page titles
// ---------------------------------------------------------------------
$pageTitles = [
    'home'      => 'Home',
    'post'      => $post['title'] ?? 'Post',
    'login'     => 'Login',
    'register'  => 'Register',
    'add_post'  => 'New Post',
    'edit_post' => 'Edit Post',
];
$pageTitle = ($pageTitles[$page] ?? 'Home') . ' - ' . SITE_NAME;
$currentPage = $page;

// ---------------------------------------------------------------------
// Render: header (navbar + flash) -> view -> footer
// ---------------------------------------------------------------------
require __DIR__ . '/inc/header.php';
require __DIR__ . '/views/' . $page . '.php';
require __DIR__ . '/inc/footer.php';
