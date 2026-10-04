<?php
// =====================================================================
// Form validation - each function returns an array of error messages
// =====================================================================

function validateRegister($input)
{
    $errors = [];
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    $confirm = $input['confirm_password'] ?? '';

    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (mb_strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Name must be 100 characters or fewer.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($confirm === '') {
        $errors[] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    return $errors;
}

function validateLogin($input)
{
    $errors = [];
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    return $errors;
}

function validatePost($input)
{
    $errors = [];
    $title = trim($input['title'] ?? '');
    $content = trim($input['content'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (mb_strlen($title) > 200) {
        $errors[] = 'Title must be 200 characters or fewer.';
    }

    if ($content === '') {
        $errors[] = 'Content is required.';
    } elseif (mb_strlen($content) < 10) {
        $errors[] = 'Content must be at least 10 characters.';
    }

    return $errors;
}
