<?php
// =====================================================================
// Post controller: create / update / delete (with image uploads)
// Runs before any HTML output - each handler redirects + exits itself.
// =====================================================================

function handleAddPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    requireLogin();
    global $conn;

    $input = [
        'title'   => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
    ];
    $old = ['title' => $input['title'], 'content' => $input['content']];

    $errors = validatePost($input);
    $filename = handleImageUpload($errors);

    if (!empty($errors)) {
        // Don't keep files that were uploaded for a post we did not save
        if ($filename !== '') {
            deleteImageFile($filename);
        }
        setFormState($errors, $old);
        header('Location: index.php?page=add_post');
        exit;
    }

    $userId = (int) $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, sanitize($input['title']));
    $content = mysqli_real_escape_string($conn, $input['content']);
    $image = $filename !== '' ? "'" . mysqli_real_escape_string($conn, $filename) . "'" : 'NULL';

    $ok = mysqli_query($conn, "INSERT INTO posts (user_id, title, content, image, created_at, updated_at) VALUES ($userId, '$title', '$content', $image, NOW(), NOW())");

    if (!$ok) {
        if ($filename !== '') {
            deleteImageFile($filename);
        }
        setFormState(['Could not save the post. Please try again.'], $old);
        header('Location: index.php?page=add_post');
        exit;
    }

    setFlash('success', 'Post created successfully!');
    header('Location: index.php?page=home');
    exit;
}

function handleEditPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    global $conn;

    $post = getPostById($_GET['id'] ?? 0);
    if (!$post) {
        setFlash('error', 'Post not found.');
        header('Location: index.php?page=home');
        exit;
    }
    if (!canEdit($post)) {
        setFlash('error', 'You are not allowed to edit this post.');
        header('Location: index.php?page=post&id=' . (int) $post['id']);
        exit;
    }

    $input = [
        'title'   => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
    ];
    $old = ['title' => $input['title'], 'content' => $input['content']];

    $errors = validatePost($input);
    $filename = handleImageUpload($errors); // '' = no new image chosen

    if (!empty($errors)) {
        if ($filename !== '') {
            deleteImageFile($filename);
        }
        setFormState($errors, $old);
        header('Location: index.php?page=edit_post&id=' . (int) $post['id']);
        exit;
    }

    $title = mysqli_real_escape_string($conn, sanitize($input['title']));
    $content = mysqli_real_escape_string($conn, $input['content']);
    $newImage = $filename !== '' ? $filename : $post['image'];
    $image = $newImage !== null && $newImage !== '' ? "'" . mysqli_real_escape_string($conn, $newImage) . "'" : 'NULL';
    $id = (int) $post['id'];

    $ok = mysqli_query($conn, "UPDATE posts SET title = '$title', content = '$content', image = $image, updated_at = NOW() WHERE id = $id");

    if (!$ok) {
        if ($filename !== '') {
            deleteImageFile($filename);
        }
        setFormState(['Could not update the post. Please try again.'], $old);
        header('Location: index.php?page=edit_post&id=' . $id);
        exit;
    }

    // New image saved - remove the old one
    if ($filename !== '' && $post['image']) {
        deleteImageFile($post['image']);
    }

    setFlash('success', 'Post updated successfully!');
    header('Location: index.php?page=post&id=' . $id);
    exit;
}

function handleDeletePost()
{
    // Delete must be a POST (the form in the views sends it)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        setFlash('error', 'Invalid request.');
        header('Location: index.php?page=home');
        exit;
    }

    global $conn;

    $post = getPostById($_GET['id'] ?? 0);
    if (!$post) {
        setFlash('error', 'Post not found.');
        header('Location: index.php?page=home');
        exit;
    }
    if (!canEdit($post)) {
        setFlash('error', 'You are not allowed to delete this post.');
        header('Location: index.php?page=post&id=' . (int) $post['id']);
        exit;
    }

    $id = (int) $post['id'];
    $ok = mysqli_query($conn, "DELETE FROM posts WHERE id = $id");

    if (!$ok) {
        setFlash('error', 'Could not delete the post.');
        header('Location: index.php?page=post&id=' . $id);
        exit;
    }

    deleteImageFile($post['image']);

    setFlash('success', 'Post "' . $post['title'] . '" has been deleted.');
    header('Location: index.php?page=home');
    exit;
}
