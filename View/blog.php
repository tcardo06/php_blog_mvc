<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <?php if (empty($this->oPosts)): ?>
        <p class="alert alert-warning">There are no Blog Posts yet.</p>
        <?php if (!empty($_SESSION['is_logged']) && $_SESSION['role'] === 'admin'): ?>
        <p>
            <button type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&amp;a=add'" class="btn btn-primary">Add Your First Blog Post!</button>
        </p>
        <?php endif; ?>
    <?php else: ?>
        <div class="row">
            <?php foreach ($this->oPosts as $oPost): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="<?=ROOT_URL?>?p=blog&amp;a=post&amp;id=<?=$oPost->id?>" class="text-primary">
                                    <?=htmlspecialchars($oPost->title)?>
                                </a>
                            </h3>
                            <p class="card-text"><?=nl2br(htmlspecialchars(mb_strimwidth($oPost->body, 0, 100, '...')))?></p>
                            <p class="text-muted small">Posted on <?=$oPost->createdDate?></p>
                            <a href="<?=ROOT_URL?>?p=blog&amp;a=post&amp;id=<?=$oPost->id?>" class="btn btn-link">Want to see more?</a>

                            <!-- Only show buttons if the user is an admin -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="d-flex justify-content-between">
                                    <a href="<?=ROOT_URL?>?p=blog&amp;a=edit&amp;id=<?=$oPost->id?>" class="btn btn-warning">Edit</a>
                                    <form action="<?=ROOT_URL?>?p=blog&amp;a=delete&amp;id=<?=$oPost->id?>" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif; ?>

    <!-- "Add New Post" button, only for admins -->
    <?php if (!empty($_SESSION['is_logged']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="mt-3">
            <button type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&amp;a=add'" class="btn btn-primary">Add New Post</button>
        </div>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>
