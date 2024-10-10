<?php require 'inc/header.php' ?>
<?php require 'inc/msg.php' ?>

<form action="" method="post">
    <p><label for="title">Title:</label><br />
        <input type="text" name="title" id="title" value="<?=htmlspecialchars($this->oPost->title ?? '')?>" required="required" />
    </p>

    <p><label for="preview">Preview Text:</label><br />
        <textarea name="preview" id="preview" rows="3" cols="35" required="required"><?=htmlspecialchars($this->oPost->preview ?? '')?></textarea>
    </p>

    <p><label for="body">Body:</label><br />
        <textarea name="body" id="body" rows="5" cols="35" required="required"><?=htmlspecialchars($this->oPost->body ?? '')?></textarea>
    </p>

    <p><label for="tags">Tags:</label><br />
        <?php if (!empty($this->oTags) && is_array($this->oTags)): ?>
            <?php foreach ($this->oTags as $oTag): ?>
                <input type="checkbox" name="tags[]" value="<?= $oTag->id ?>" <?= in_array($oTag->id, $this->oPost->tags ?? []) ? 'checked' : '' ?>> <?= htmlspecialchars($oTag->name) ?><br />
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tags available. Add some tags to the database.</p>
        <?php endif; ?>
    </p>

    <p><input type="submit" name="add_submit" value="Submit" /></p>
</form>

<?php require 'inc/footer.php' ?>
