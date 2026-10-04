<?php // $posts comes from index.php ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Latest Posts</h1>
        <p class="text-muted mb-0"><?= count($posts) ?> post<?= count($posts) === 1 ? '' : 's' ?> published</p>
    </div>
    <?php if (isLoggedIn()) { ?>
        <a href="index.php?page=add_post" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Post
        </a>
    <?php } ?>
</div>

<?php if (empty($posts)) { ?>
    <div class="alert alert-info">
        No posts yet.
        <?php if (isLoggedIn()) { ?>
            <a href="index.php?page=add_post">Write the first one</a>.
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="row g-4">
        <?php foreach ($posts as $post) { ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm post-card">
                    <a href="index.php?page=post&id=<?= (int) $post['id'] ?>">
                        <?php if ($post['image']) { ?>
                            <img src="assets/uploads/<?= e($post['image']) ?>" class="card-img-top post-thumb" alt="<?= e($post['title']) ?>">
                        <?php } else { ?>
                            <div class="card-img-top post-thumb post-placeholder d-flex align-items-center justify-content-center">
                                <i class="bi bi-journal-text fs-1 text-white-50"></i>
                            </div>
                        <?php } ?>
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="index.php?page=post&id=<?= (int) $post['id'] ?>" class="text-decoration-none text-dark">
                                <?= e($post['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted flex-grow-1">
                            <?= e(mb_substr(strip_tags($post['content']), 0, 130)) ?><?= mb_strlen(strip_tags($post['content'])) > 130 ? '…' : '' ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> <?= e($post['author_name']) ?>
                                &middot; <i class="bi bi-calendar"></i> <?= e(date('M j, Y', strtotime($post['created_at']))) ?>
                            </small>
                        </div>
                    </div>
                    <?php if (canEdit($post)) { ?>
                        <div class="card-footer bg-white d-flex gap-2">
                            <a href="index.php?page=edit_post&id=<?= (int) $post['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="index.php?page=delete_post&id=<?= (int) $post['id'] ?>" method="post" class="ms-auto"
                                  onsubmit="return confirm('Delete this post? This cannot be undone.');">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
