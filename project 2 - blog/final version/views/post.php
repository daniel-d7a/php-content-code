<?php // $post comes from index.php ?>

<article class="mx-auto" style="max-width: 800px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?page=home">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= e($post['title']) ?></li>
        </ol>
    </nav>

    <header class="mb-4">
        <h1 class="h2 mb-2"><?= e($post['title']) ?></h1>
        <p class="text-muted mb-0">
            <i class="bi bi-person"></i> <?= e($post['author_name']) ?>
            &middot; <i class="bi bi-calendar"></i> <?= e(date('F j, Y', strtotime($post['created_at']))) ?>
            <?php if ($post['updated_at'] !== $post['created_at']) { ?>
                &middot; <i class="bi bi-pencil-square"></i> updated <?= e(date('F j, Y', strtotime($post['updated_at']))) ?>
            <?php } ?>
        </p>
    </header>

    <?php if ($post['image']) { ?>
        <img src="assets/uploads/<?= e($post['image']) ?>" class="img-fluid rounded mb-4 w-100 post-image" alt="<?= e($post['title']) ?>">
    <?php } ?>

    <div class="post-content fs-5">
        <?= nl2br(e($post['content'])) ?>
    </div>

    <?php if (canEdit($post)) { ?>
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="index.php?page=edit_post&id=<?= (int) $post['id'] ?>" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Post
            </a>
            <form action="index.php?page=delete_post&id=<?= (int) $post['id'] ?>" method="post"
                  onsubmit="return confirm('Delete this post? This cannot be undone.');">
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Delete Post
                </button>
            </form>
        </div>
    <?php } ?>

    <a href="index.php?page=home" class="btn btn-link px-0 mt-4">
        <i class="bi bi-arrow-left"></i> Back to all posts
    </a>
</article>
