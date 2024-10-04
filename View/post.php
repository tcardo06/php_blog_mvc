<?php require 'inc/header.php' ?>

<?php if (empty($this->oPost)): ?>
    <p class="error">The post can't be found!</p>
<?php else: ?>

    <article>
        <time datetime="<?=$this->oPost->createdDate?>" pubdate="pubdate"></time>

        <h1><?=htmlspecialchars($this->oPost->title)?></h1>
        <p><?=nl2br(htmlspecialchars($this->oPost->body))?></p>
        <p class="left small italic">Posted on <?=$this->oPost->createdDate?></p>

        <?php
            $oPost = $this->oPost;
            require 'inc/control_buttons.php';
        ?>
    </article>

    <!-- Display Approved Comments -->
    <section class="mt-5">
        <h2>Comments</h2>
        <?php if (empty($this->oComments)): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <?php foreach ($this->oComments as $oComment): ?>
                <div class="mb-4 p-3 border">
                    <p><?=nl2br(htmlspecialchars($oComment->comment))?></p>
                    <p class="text-muted small">Posted on <?=htmlspecialchars($oComment->created_at)?> by User ID <?=htmlspecialchars($oComment->user_id)?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- Comment Form for Logged-in Users -->
    <?php if (!empty($_SESSION['is_logged'])): ?>
        <section class="mt-5">
            <h3>Leave a Comment</h3>
            <form action="<?=ROOT_URL?>?p=blog&a=comment&id=<?=$this->oPost->id?>" method="post">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="5" placeholder="Write your comment here..." required></textarea>
                </div>
                <button type="submit" name="submit_comment" class="btn btn-primary">Submit Comment</button>
            </form>
        </section>
    <?php else: ?>
        <p>You must be logged in to leave a comment.</p>
    <?php endif; ?>

<?php endif ?>

<?php require 'inc/footer.php' ?>
