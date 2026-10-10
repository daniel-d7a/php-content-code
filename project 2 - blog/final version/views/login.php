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
          <h1 class="h4 mb-4 text-center"><i class="bi bi-box-arrow-in-right"></i> Login</h1>

          <form action="index.php?page=loginAction" method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" value="" required autofocus>
            </div>
            <div class="mb-4">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>

          <p class="text-center mt-3 mb-0">
            No account yet? <a href="index.php?page=register">Register</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</main>