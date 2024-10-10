<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Tableau de bord administrateur</h1>

    <div class="row">
        <!-- Admin action buttons with enhanced Bootstrap styling -->
        <div class="col-lg-4 col-md-6 mb-3">
            <button class="btn btn-primary btn-lg w-100" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=add'">
                Créer un post
            </button>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <button class="btn btn-secondary btn-lg w-100" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=manage&action=edit'">
                Modifier un post
            </button>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <button class="btn btn-danger btn-lg w-100" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=manage&action=delete'">
                Supprimer un post
            </button>
        </div>
    </div>
</div>

<?php require 'inc/footer.php'; ?>
