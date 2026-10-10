<?php

$blogs = getBlogs();

$message = getMessage()
?>

<main class="container py-4">

  <?php if (isset($message)): ?>
  <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
    <?= $message['text'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif ?>

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h3 mb-1">Latest Posts</h1>
      <p class="text-muted mb-0"><?= count($blogs)  ?> posts published</p>
    </div>
    <a href="index.php?page=createPostPage" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> New Post
    </a>
  </div>

  <div class="row g-4">

    <?php foreach ($blogs as $b): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm post-card">
        <a href="index.php?page=post&id=<?= $b["id"]  ?>">
          <img src="assets/uploads/<?= !empty($b["image"]) ? $b["image"] : "placeholder.png"  ?>"
            class="card-img-top post-thumb" alt="Welcome to the Blog">
        </a>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">
            <a href="index.php?page=post&id=<?= $b["id"]  ?>"
              class="text-decoration-none text-dark"><?= $b["title"]  ?></a>
          </h5>
          <p class="card-text text-muted flex-grow-1"><?= substr($b["content"], 0, 100)  ?></p>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">
              <i class="bi bi-person"></i> <?= $b["name"]  ?>
              &middot; <i class="bi bi-calendar"></i><?= $b["published_at"]  ?>
            </small>
          </div>
        </div>

        <?php if (isLoggedIn() && $_SESSION['user']['id'] == $b['user_id']): ?>
        <div class="card-footer bg-white d-flex gap-2">
          <a href="index.php?page=editPostPage&id=<?= $b["id"]  ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <form action="index.php?page=deletePostAction&id=<?= $b["id"]  ?>" method="post" class="ms-auto">
            <button type="submit" class="btn btn-sm btn-outline-danger">
              <i class="bi bi-trash"></i> Delete
            </button>
          </form>
        </div>
        <?php endif ?>
      </div>
    </div>
    <?php endforeach ?>

  </div>
</main>