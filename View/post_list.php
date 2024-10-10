<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">
        <?= $this->action === 'delete' ? 'Delete Posts' : 'Edit Posts'; ?>
    </h1>

    <!-- Search Bar -->
    <form action="<?=ROOT_URL?>?p=blog&a=manage&action=<?=htmlspecialchars($this->action)?>" method="get" class="mb-4">
        <input type="hidden" name="p" value="blog">
        <input type="hidden" name="a" value="manage">
        <input type="hidden" name="action" value="<?=htmlspecialchars($this->action)?>">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Search post by name..." value="<?=htmlspecialchars($_GET['q'] ?? '')?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Display List of Post Titles -->
    <?php if (empty($this->oPosts)): ?>
        <p class="alert alert-warning">No posts found.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($this->oPosts as $oPost): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?=htmlspecialchars($oPost->title)?>

                    <?php if ($this->action === 'edit'): ?>
                        <a href="<?=ROOT_URL?>?p=blog&a=edit&id=<?=$oPost->id?>" class="btn btn-warning">Edit</a>
                    <?php elseif ($this->action === 'delete'): ?>
                      <form action="<?=ROOT_URL?>?p=blog&a=delete&id=<?=$oPost->id?>" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                          <input type="hidden" name="delete" value="1">
                          <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>
