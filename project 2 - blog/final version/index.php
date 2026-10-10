<?php

include_once "./config/db.php";
include_once "./core/functions.php";
include_once "./core/validation.php";

$route = isset($_GET['page']) ? $_GET['page'] : "home";

include_once "./inc/header.php";

if ($route == "home") {
  include "./views/home.php";
} else if ($route == "post") {
  include "./views/post.php";
} else if ($route == "login") {
  if (!isLoggedIn()) {
    include "./views/login.php";
  } else {
    header("Location: index.php?page=home");
  }
} else if ($route == "register") {
  include "./views/register.php";
} else if ($route == "registerAction") {
  include "./controllers/auth-controller.php";
} else if ($route == "loginAction") {
  include "./controllers/auth-controller.php";
} else if ($route == "createPostPage") {
  include "./views/add_post.php";
} else if ($route == "createPostAction") {
  include "./controllers/blog-controller.php";
} else if ($route == "deletePostAction") {
  $id = $_GET['id'];
  if (!isLoggedIn() || !checkOwnPost($id, $_SESSION['user']['id'])) {
    header("Location: index.php?page=home");
  } else {
    include "./controllers/blog-controller.php";
  }
} else if ($route == "editPostPage") {
  $id = $_GET['id'];
  if (!isLoggedIn() || !checkOwnPost($id, $_SESSION['user']['id'])) {
    header("Location: index.php?page=home");
  } else {
    include "./views/edit_post.php";
  }
} else if ($route == "updatePostAction") {
  $id = $_POST['id'];
  if (!isLoggedIn() || !checkOwnPost($id, $_SESSION['user']['id'])) {
    header("Location: index.php?page=home");
  } else {
    include "./controllers/blog-controller.php";
  }
} else {
  echo "404 not found";
}

include_once "./inc/footer.php";