<?php


function validateRegister($name, $email, $password, $password_confirmation)
{

  /// sanitize
  $name = sanitizer($name);
  $email = sanitizer($email);
  $password = sanitizer($password);
  $password_confirmation = sanitizer($password_confirmation);

  $errors = [];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "email is invalid";
  }

  if (strlen($name) < 2) {
    $errors[] = "name must be more than 2 characters";
  }

  if ($password != $password_confirmation) {
    $errors[] = "passwords don't match";
  }

  return $errors;
}


function sanitizer($val)
{
  return htmlspecialchars(strip_tags(trim($val)));
}

function validatePost($title, $content, $image)
{
  $title = sanitizer($title);
  $content = sanitizer($content);

  $errors = [];

  if (strlen($title) < 1) {
    $errors[] = "title is required";
  }
  if (strlen($content) < 1) {
    $errors[] = "content is required";
  }

  /// image validation

  if (isset($image)) {

    /// 5 MB
    $max_file_size = 5 * 1024 * 1024;

    if (filesize($image['tmp_name']) > $max_file_size) {
      $errors[] = "file too big";
    }

    $allowed_mimes = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    $mime = mime_content_type($image['tmp_name']);

    if (!in_array($mime, $allowed_mimes)) {
      $errors[] = "file format not allowed";
    }
  }

  return $errors;
}