<?php
// =====================================================================
// Auth controller: register / login / logout
// Runs before any HTML output - each handler redirects + exits itself.
// =====================================================================

function handleRegister()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    global $conn;

    $input = [
        'name'             => $_POST['name'] ?? '',
        'email'            => trim($_POST['email'] ?? ''),
        'password'         => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
    ];
    $old = ['name' => $input['name'], 'email' => $input['email']];

    $errors = validateRegister($input);

    $email = mysqli_real_escape_string($conn, $input['email']);

    // Is the email already taken?
    if (empty($errors)) {
        $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
        if ($result && mysqli_num_rows($result) > 0) {
            $errors[] = 'Email already exists.';
        }
    }

    if (!empty($errors)) {
        setFormState($errors, $old);
        header('Location: index.php?page=register');
        exit;
    }

    $name = mysqli_real_escape_string($conn, sanitize($input['name']));
    $hash = mysqli_real_escape_string($conn, password_hash($input['password'], PASSWORD_DEFAULT));

    $ok = mysqli_query($conn, "INSERT INTO users (name, email, password, role, created_at) VALUES ('$name', '$email', '$hash', 'user', NOW())");

    if (!$ok) {
        setFormState(['Could not create the account. Please try again.'], $old);
        header('Location: index.php?page=register');
        exit;
    }

    setFlash('success', 'Account created successfully! Please login.');
    header('Location: index.php?page=login');
    exit;
}

function handleLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    global $conn;

    $input = [
        'email'    => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
    ];
    $old = ['email' => $input['email']];

    $errors = validateLogin($input);

    $user = null;
    if (empty($errors)) {
        $email = mysqli_real_escape_string($conn, $input['email']);
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
        if ($result) {
            $user = mysqli_fetch_assoc($result);
        }

        if (!$user || !password_verify($input['password'], $user['password'])) {
            $errors[] = 'Invalid email or password.';
        }
    }

    if (!empty($errors)) {
        setFormState($errors, $old);
        header('Location: index.php?page=login');
        exit;
    }

    // Fresh session id after privilege change
    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    setFlash('success', 'Welcome back, ' . $user['name'] . '!');
    header('Location: index.php?page=home');
    exit;
}

function handleLogout()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: index.php?page=login');
    exit;
}
