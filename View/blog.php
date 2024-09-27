<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <?php if (empty($this->oPosts)): ?>
        <p class="alert alert-warning">There are no Blog Posts yet.</p>
        <p>
            <button type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&amp;a=add'" class="btn btn-primary">Add Your First Blog Post!</button>
        </p>
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
                            <?php require 'inc/control_buttons.php' ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>

<?php require 'inc/footer.php'; ?>
