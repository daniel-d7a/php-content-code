<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_GET['page'] == "registerAction") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = validateRegister($name, $email, $password, $confirm_password);

    if (!empty($errors)) {
      setMessage("danger", $errors[0]);
      header("Location: index.php?page=register");
      exit;
    }

    $result = registerUser($name, $email, $password);

    if ($result) {
      setMessage("success", "User registered successfully");
      header("Location: index.php?page=login");
      exit;
    } else {
      setMessage("danger", "Failed to register user");
      header("Location: index.php?page=register");
      exit;
    }

    // redirect to login with success message

  } else if ($_GET['page'] == "loginAction") {

    // email password
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = checkUser($email, $password);

    if (!$user) {
      setMessage("danger", "Failed to login user");
      header("Location: index.php?page=login");
      exit;
    } else {
      $_SESSION['user'] = $user;
      setMessage("success", "User Logged in successfully");
      header("Location: index.php?page=home");
      exit;
    }
  } else if ($_GET['page'] == "logoutAction") {
    // logout
  }
}