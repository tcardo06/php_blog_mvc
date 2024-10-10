<?php require 'inc/header.php' ?>

<!-- Display Success or Error Message -->
<?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php elseif (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (empty($this->oPost)): ?>
    <p class="alert alert-danger">Post not found!</p>
<?php else: ?>

    <form action="" method="post">
        <!-- Post Title -->
        <p><label for="title">Title:</label><br />
            <input type="text" name="title" id="title" value="<?=htmlspecialchars($this->oPost->title)?>" required="required" />
        </p>

        <!-- Post Preview -->
        <p><label for="preview">Preview Text:</label><br />
            <textarea name="preview" id="preview" rows="3" required="required"><?=htmlspecialchars($this->oPost->preview)?></textarea>
        </p>

        <!-- Post Body -->
        <p><label for="body">Body:</label><br />
            <textarea name="body" id="body" rows="5" required="required"><?=htmlspecialchars($this->oPost->body)?></textarea>
        </p>

        <!-- Tags -->
        <p><label for="tags">Tags:</label><br />
            <?php foreach ($this->oTags as $oTag): ?>
                <input type="checkbox" name="tags[]" value="<?= $oTag->id ?>" <?= in_array($oTag->id, $this->oPost->tags) ? 'checked' : '' ?>> <?= htmlspecialchars($oTag->name) ?><br />
            <?php endforeach; ?>
        </p>

        <p><input type="submit" name="edit_submit" value="Update Post" /></p>
    </form>

<?php endif ?>

<?php require 'inc/footer.php' ?>
