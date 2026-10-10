<?php

session_start();

// --------------------- POSTS ---------------------

function getBlogs()
{
  $db = $GLOBALS["db"];

  $sql = "SELECT title, content, blogs.id as id, users.id as user_id, published_at, image, users.name as name 
          FROM blogs 
          JOIN users on users.id = blogs.user_id 
          ORDER BY published_at DESC;";

  $result = mysqli_query($db, $sql);

  $blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);

  return $blogs;
}

function getSinglePost($id)
{
  $db = $GLOBALS["db"];

  $sql = "SELECT title, content, blogs.id as id, published_at, image, users.name as name 
          FROM blogs 
          JOIN users on users.id = blogs.user_id 
          WHERE blogs.id = {$id}
          LIMIT 1";

  $result = mysqli_query($db, $sql);

  $post = mysqli_fetch_assoc($result);

  return $post;
}


function uploadImage($image)
{
  $upload_dir = __DIR__ . "/../assets/uploads";

  $allowed_mimes = [
    "image/jpeg" => "jpg",
    "image/png" => "png",
    "image/webp" => "webp",
    "image/gif" => "gif"
  ];
  $mime = mime_content_type($image['tmp_name']);

  $ext = $allowed_mimes[$mime];

  $name = uniqid() . ".{$ext}";

  $fullPath = "$upload_dir/$name";

  move_uploaded_file($image['tmp_name'], $fullPath);

  return $name;
}


function deleteImage($imagePath)
{
  $upload_dir = __DIR__ . "/../assets/uploads";

  $fullPath = "$upload_dir/$imagePath";

  unlink($fullPath);
}

function savePost($title, $content, $user_id, $imagePath)
{

  $db = $GLOBALS["db"];

  if (!empty($imagePath)) {
    $sql = "INSERT INTO blogs (title, content, user_id, image)
          VALUES ('$title', '$content', $user_id, '$imagePath');";
  } else {
    $sql = "INSERT INTO blogs (title, content, user_id)
          VALUES ('$title', '$content', $user_id);";
  }

  $result = null;
  try {
    $result = mysqli_query($db, $sql);
  } catch (Exception $e) {
    var_dump($e);
    return false;
  }

  if (!$result) {
    return false;
  } else {
    return true;
  }
}

function updatePost($id, $title, $content, $imagePath)
{
  $db = $GLOBALS["db"];

  if(isset($imagePath)){
    $sql = "UPDATE blogs SET title = '{$title}', content = '{$content}', image = '$imagePath'  WHERE id = {$id}";
  }else {
    $sql = "UPDATE blogs SET title = '{$title}', content = '{$content}' WHERE id = {$id}";
  }

  $result = mysqli_query($db, $sql);

  if (!$result) {
    return false;
  } else {
    return true;
  }
}

function checkOwnPost($post_id, $user_id)
{
  $db = $GLOBALS["db"];

  $sql = "SELECT * FROM blogs WHERE id = $post_id AND user_id = $user_id;";

  $result = mysqli_query($db, $sql);

  $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

  if (empty($result)) {
    return false;
  } else {
    return true;
  }
}

function deletePost($id)
{
  $db = $GLOBALS["db"];
  $sql = "DELETE FROM blogs WHERE id = $id";
  $result = mysqli_query($db, $sql);
  if ($result) {
    return true;
  } else {
    return false;
  }
}


// --------------------- AUTH ---------------------

function registerUser($name, $email, $password)
{
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  $db = $GLOBALS["db"];

  $sql = "INSERT INTO users (name, email, password_hash)
          VALUES ('$name', '$email', '$passwordHash');";

  $result = null;
  try {

    $result = mysqli_query($db, $sql);
  } catch (Exception $e) {
    // var_dump($e);
    return false;
  }

  if (!$result) {
    return false;
  } else {
    return true;
  }
}


function isLoggedIn()
{
  if (isset($_SESSION["user"])) {
    return true;
  } else {
    return false;
  }
}

function checkUser($email, $password)
{
  $db = $GLOBALS["db"];

  $sql = "SELECT *
          FROM users
          WHERE email = '$email'
          LIMIT 1;";

  $result = mysqli_query($db, $sql);

  $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

  if (empty($users)) {
    return false;
  }

  $passwordValid = password_verify($password, $users[0]['password_hash']);

  if ($passwordValid) {
    return $users[0];
  } else {
    return false;
  }
}



function setMessage($type, $message)
{
  $_SESSION['message'] = [
    'type' => $type,
    'text' => $message,
  ];
}

function getMessage()
{
  $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
  unset($_SESSION['message']);
  return $message;
}