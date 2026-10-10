<?php

$message = getMessage()
?>

<main class="container py-4">

  <?php if (isset($message)): ?>
  <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
    <?= $message['text'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif ?>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h1 class="h4 mb-4 text-center"><i class="bi bi-person-plus"></i> Create an Account</h1>

          <form action="index.php?page=registerAction" method="post">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="" required autofocus>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" value="" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control" minlength="6" required>
              <div class="form-text">At least 6 characters.</div>
            </div>
            <div class="mb-4">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>

          <p class="text-center mt-3 mb-0">
            Already have an account? <a href="index.php?page=login">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</main>