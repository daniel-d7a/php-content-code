<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if ($_GET["page"] == 'createPostAction') {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = null;

    if ($_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
      $image = $_FILES['image'];
    }

    $errors = validatePost($title, $content, $image);

    if (!empty($errors)) {
      setMessage("danger", $errors[0]);
      header("Location: index.php?page=createPostPage");
      exit;
    }

    $imagePath = null;
    if (isset($image)) {
      $imagePath = uploadImage($image);
    }

    $result = savePost($title, $content, $_SESSION['user']['id'], $imagePath);

    if ($result) {
      setMessage("success", "post created successfully");
      header("Location: index.php?page=home");
      exit;
    } else {
      setMessage("danger", "Error creating post");
      header("Location: index.php?page=createPostPage");
      exit;
    }
  } else if ($_GET["page"] == 'deletePostAction') {

    $id = $_GET['id'];

    $post = getSinglePost($id);

    // if the post has an image, delete the image
    if (isset($post['image'])) {
      deleteImage($post['image']);
    }

    // delete
    $result = deletePost($id);

    // redirect
    if ($result) {
      setMessage("success", "post deleted successfully");
      header("Location: index.php?page=home");
      exit;
    } else {
      setMessage("danger", "Error deleting post");
      header("Location: index.php?page=home");
      exit;
    }
  } else if ($_GET["page"] == 'updatePostAction') {

    /// extract new post data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = null;

    if ($_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
      $image = $_FILES['image'];
    }

    /// validate
    $errors = validatePost($title, $content, $image);

    if (!empty($errors)) {
      setMessage("danger", $errors[0]);
      header("Location: index.php?page=editPostPage&id={$id}");
      exit;
    }

    $post = getSinglePost($id);
    $oldImage = $post['image'];

    $imagePath = null;
    
    if (isset($image)) {
      if (isset($oldImage)) {
        deleteImage($oldImage);
      }
      $imagePath = uploadImage($image);
    }

    /// update post in database
    $result = updatePost($id, $title, $content, $imagePath);

    /// redirect user
    if ($result) {
      setMessage("success", "post updated successfully");
      header("Location: index.php?page=home");
      exit;
    } else {
      setMessage("danger", "Error updating post");
      header("Location: index.php?page=editPostPage&id={$id}");
      exit;
    }
  }
}