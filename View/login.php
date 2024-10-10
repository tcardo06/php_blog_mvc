<?php require 'inc/header.php'; ?>
<?php require 'inc/msg.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <form action="" method="post" class="border p-4 bg-white shadow-sm rounded">
            <h2 class="text-center mb-4">Connexion</h2>

            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" required="required" />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" required="required" />
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Connexion</button>
            </div>
        </form>
    </div>
</div>

<?php require 'inc/footer.php'; ?>
