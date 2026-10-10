<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.html">
      <i class="bi bi-journal-richtext"></i> My Blog
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
      aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php?page=home">Home</a>
        </li>
        <li class="nav-item">
          <!-- <a class="nav-link" href="add_post.html"><i class="bi bi-plus-circle"></i> New Post</a> -->
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <!-- <span class="nav-link text-white-50">
            <i class="bi bi-person-circle"></i> Admin
          </span> -->
        </li>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=login"><i class="bi bi-box-arrow-right"></i>Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=register">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=login">Login</a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>