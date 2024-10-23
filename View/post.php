<?php require 'inc/header.php'; ?>

<?php if (empty($this->oPost)): ?>
    <p class="alert alert-danger">Le post est introuvable !</p>
<?php else: ?>

    <!-- Display Success or Error Message -->
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); // Clear the message after displaying ?>
        </div>
    <?php elseif (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); // Clear the error after displaying ?>
        </div>
    <?php endif; ?>

    <article class="mt-5 p-4 bg-white rounded shadow-sm">
        <!-- Post Title -->
        <h2 class="mb-2"><?= htmlspecialchars($this->oPost->title) ?></h2>

        <!-- Post Date (Check if the post was updated) -->
        <?php if (!empty($this->oPost->updatedDate)): ?>
            <p class="text-muted mb-4">Mis à jour le <?= $this->oPost->updatedDate ?></p>
        <?php else: ?>
            <p class="text-muted mb-4">Publié le <?= $this->oPost->createdDate ?></p>
        <?php endif; ?>

        <!-- Display Tags -->
        <?php if (!empty($this->oTags)): ?>
            <div class="mb-4">
                <?php foreach ($this->oTags as $oTag): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($oTag->name) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Post Content -->
        <p class="lead"><?= nl2br(htmlspecialchars($this->oPost->body)) ?></p>
    </article>

    <!-- Display Approved Comments -->
    <section class="mt-5">
        <h2>Commentaires</h2>
        <?php if (empty($this->oComments)): ?>
            <p class="alert alert-warning">Pas encore de commentaires.</p>
        <?php else: ?>
            <?php foreach ($this->oComments as $oComment): ?>
                <div class="mb-4 p-3 border rounded bg-light">
                    <p><?= nl2br(htmlspecialchars($oComment->comment)) ?></p>
                    <p class="text-muted small">Posté le <?= htmlspecialchars($oComment->created_at) ?> par <?= htmlspecialchars($oComment->user_name) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- Comment Form for Logged-in Users -->
    <?php if (!empty($_SESSION['is_logged'])): ?>
        <section class="mt-5">
            <h3>Laisser un commentaire</h3>
            <form action="<?= ROOT_URL ?>?p=blog&amp;a=comment&amp;id=<?= $this->oPost->id ?>" method="post">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="5" placeholder="Écrivez votre commentaire ici..." required></textarea>
                </div>
                <button type="submit" name="submit_comment" class="btn btn-primary">Envoyer le commentaire</button>
            </form>
        </section>
    <?php else: ?>
        <p>Vous devez être connecté pour laisser un commentaire.</p>
    <?php endif; ?>

<?php endif; ?>

<?php require 'inc/footer.php'; ?>
