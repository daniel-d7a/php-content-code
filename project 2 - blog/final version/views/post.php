<?php

$id = $_GET["id"];

$post = getSinglePost($id)

?>


<main class="container py-4">
  <article class="mx-auto" style="max-width: 800px;">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Welcome to the Blog</li>
      </ol>
    </nav>

    <header class="mb-4">
      <h1 class="h2 mb-2"><?= $post["title"]  ?></h1>
      <p class="text-muted mb-0">
        <i class="bi bi-person"></i><?= $post["name"]  ?>
        &middot; <i class="bi bi-calendar"></i> <?= $post["published_at"]  ?>
      </p>
    </header>

    <img src="assets/uploads/<?= !empty($post["image"]) ? $post["image"] : "placeholder.png"  ?>"
      class="img-fluid rounded mb-4 w-100 post-image" alt="Welcome to the Blog">

    <div class="post-content fs-5">
      <p><?= $post["content"]  ?></p>
    </div>

    <!-- <div class="d-flex gap-2 mt-4 pt-3 border-top">
      <a href="edit_post.html" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Edit Post
      </a>
      <form action="index.html" method="post" onsubmit="return false;">
        <button type="submit" class="btn btn-outline-danger">
          <i class="bi bi-trash"></i> Delete Post
        </button>
      </form>
    </div> -->

    <a href="index.php?page=home" class="btn btn-link px-0 mt-4">
      <i class="bi bi-arrow-left"></i> Back to all posts
    </a>
  </article>
</main>