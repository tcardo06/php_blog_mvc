<?php require 'inc/header.php' ?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
    <p class="alert alert-danger">Post introuvable !</p>
<?php else: ?>

    <div class="container mt-5">
        <h2 class="mb-4">Modifier le post</h2>

        <form action="" method="post">
            <!-- Post Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Titre:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?=htmlspecialchars($this->oPost->title)?>" required="required" />
            </div>

            <!-- Post Preview -->
            <div class="mb-3">
                <label for="preview" class="form-label">Texte d'aperçu:</label>
                <textarea name="preview" id="preview" class="form-control" rows="3" required="required"><?=htmlspecialchars($this->oPost->preview)?></textarea>
            </div>

            <!-- Post Body -->
            <div class="mb-3">
                <label for="body" class="form-label">Contenu:</label>
                <textarea name="body" id="body" class="form-control" rows="5" required="required"><?=htmlspecialchars($this->oPost->body)?></textarea>
            </div>

            <!-- Tags Input with Select2 -->
            <div class="mb-4">
                <label for="tags" class="form-label">Tags:</label>
                <select name="tags[]" id="tags" class="form-control" multiple="multiple">
                    <?php foreach ($this->oTags as $oTag): ?>
                        <option value="<?= $oTag->id ?>" <?= in_array($oTag->id, $this->oPost->tags) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($oTag->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <input type="submit" name="edit_submit" value="Mettre à jour le post" class="btn btn-primary btn-lg" />
            </div>
        </form>
    </div>

<?php endif ?>

<?php require 'inc/footer.php' ?>

<!-- Include jQuery (Ensure this is loaded before Select2 JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        $('#tags').select2({
            placeholder: "Sélectionnez des tags",
            allowClear: true
        });
    });
</script>
