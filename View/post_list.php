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
    <h1 class="mb-4">
        <?= $this->action === 'delete' ? 'Supprimer des posts' : 'Modifier des posts'; ?>
    </h1>

    <!-- Search Bar -->
    <form action="<?=ROOT_URL?>?p=blog&a=manage&action=<?=htmlspecialchars($this->action)?>" method="get" class="mb-4">
        <input type="hidden" name="p" value="blog">
        <input type="hidden" name="a" value="manage">
        <input type="hidden" name="action" value="<?=htmlspecialchars($this->action)?>">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Rechercher un post par nom..." value="<?=htmlspecialchars($_GET['q'] ?? '')?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

    <!-- Display List of Post Titles -->
    <?php if (empty($this->oPosts)): ?>
        <p class="alert alert-warning">Aucun post trouvé.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($this->oPosts as $oPost): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?=htmlspecialchars($oPost->title)?>

                    <?php if ($this->action === 'edit'): ?>
                        <a href="<?=ROOT_URL?>?p=blog&a=edit&id=<?=$oPost->id?>" class="btn btn-warning">Modifier</a>
                    <?php elseif ($this->action === 'delete'): ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-post-id="<?=$oPost->id?>" data-post-title="<?=htmlspecialchars($oPost->title)?>">
                            Supprimer
                        </button>
                    <?php endif; ?>
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
                <p id="modalPostTitle">Êtes-vous sûr de vouloir supprimer ce post ?</p>
            </div>
            <div class="modal-footer">
                <form id="deletePostForm" action="" method="post">
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
        var postId = button.getAttribute('data-post-id');
        var postTitle = button.getAttribute('data-post-title');

        // Update the modal's content
        var modalBodyText = confirmDeleteModal.querySelector('#modalPostTitle');
        var deleteForm = confirmDeleteModal.querySelector('#deletePostForm');

        modalBodyText.textContent = `Êtes-vous sûr de vouloir supprimer le post: "${postTitle}" ?`;
        deleteForm.setAttribute('action', `<?=ROOT_URL?>?p=blog&a=delete&id=${postId}`);
    });
</script>
