<?php require 'inc/header.php'; ?>

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

<div class="container mt-5">
    <h1 class="mb-4">Gérer les commentaires</h1>

    <!-- Display List of Comments -->
    <?php if (!$this->oComments): ?>

        <p class="alert alert-warning">Aucun commentaire trouvé.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($this->oComments as $oComment): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Commentaire:</strong> <?=nl2br(htmlspecialchars($oComment->comment))?>
                        <br>
                        <small>Posté le <?=htmlspecialchars($oComment->created_at)?> par utilisateur ID <?=htmlspecialchars($oComment->user_id)?> sur le post:
                        <a href="<?=ROOT_URL?>?p=blog&a=post&id=<?=htmlspecialchars($oComment->post_id)?>">
                            <?=htmlspecialchars($oComment->post_title)?>
                        </a></small>
                    </div>

                    <!-- Approve/Delete Buttons -->
                    <div>
                        <?php if ($oComment->status === 'pending'): ?>
                            <form action="<?=ROOT_URL?>?p=blog&a=approveComment&id=<?=$oComment->id?>" method="post" style="display:inline;">
                                <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                            </form>
                        <?php endif; ?>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-comment-id="<?=$oComment->id?>" data-comment-content="<?=htmlspecialchars($oComment->comment)?>">
                            Supprimer
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>

<!-- Bootstrap Modal for Confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalCommentContent">Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteCommentForm" action="" method="post">
                    <input type="hidden" name="delete" value="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (ensure this is included if not already) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Initialize Modal with Dynamic Data -->
<script>
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        // Extract info from data-* attributes
        var commentId = button.getAttribute('data-comment-id');
        var commentContent = button.getAttribute('data-comment-content');

        // Update the modal's content
        var modalBodyText = confirmDeleteModal.querySelector('#modalCommentContent');
        var deleteForm = confirmDeleteModal.querySelector('#deleteCommentForm');

        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer ce commentaire : "${commentContent}" ?`;
        deleteForm.setAttribute('action', `<?=ROOT_URL?>?p=blog&a=deleteComment&id=${commentId}`);
    });
</script>
