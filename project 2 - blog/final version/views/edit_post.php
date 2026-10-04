<?php
// $post comes from index.php
$errors = getFormErrors();
$old = getFormOld();
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h1 class="h3 mb-4"><i class="bi bi-pencil-square"></i> Edit Post</h1>

        <?php if (!empty($errors)) { ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error) { ?>
                        <li><?= e($error) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="index.php?page=edit_post&id=<?= (int) $post['id'] ?>" method="post" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                               value="<?= e($old['title'] ?? $post['title']) ?>" maxlength="200" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="10" required><?= e($old['content'] ?? $post['content']) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="form-label">Replace image (optional)</label>
                        <?php if ($post['image']) { ?>
                            <div class="mb-2">
                                <img src="assets/uploads/<?= e($post['image']) ?>" alt="Current image"
                                     class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-text">Leave empty to keep the current image.</div>
                            </div>
                        <?php } ?>
                        <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">JPG, PNG, GIF or WEBP. Maximum 2 MB.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
                        <a href="index.php?page=post&id=<?= (int) $post['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
