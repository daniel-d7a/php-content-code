<?php // $currentPage is set by index.php ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=home">
            <i class="bi bi-journal-richtext"></i> <?= SITE_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'home' || ($currentPage ?? '') === 'post' ? 'active' : '' ?>"
                       href="index.php?page=home">Home</a>
                </li>
                <?php if (isLoggedIn()) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage ?? '') === 'add_post' ? 'active' : '' ?>"
                           href="index.php?page=add_post"><i class="bi bi-plus-circle"></i> New Post</a>
                    </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()) { ?>
                    <li class="nav-item">
                        <span class="nav-link text-white-50">
                            <i class="bi bi-person-circle"></i> <?= e($_SESSION['user_name']) ?>
                            <?php if (isAdmin()) { ?>
                                <span class="badge bg-warning text-dark">admin</span>
                            <?php } ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage ?? '') === 'login' ? 'active' : '' ?>"
                           href="index.php?page=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage ?? '') === 'register' ? 'active' : '' ?>"
                           href="index.php?page=register">Register</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
