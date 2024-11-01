<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <h2>Confirmation de soumission</h2>

    <!-- Display the form data -->
    <p><strong>Nom:</strong> <?= htmlspecialchars($_GET['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($_GET['email']) ?></p>
    <p><strong>Message:</strong></p>
    <p><?= nl2br(htmlspecialchars($_GET['message'])) ?></p>

    <!-- Back to the form -->
    <a href="<?= ROOT_URL ?>" class="btn btn-primary">Retour à l'accueil</a>
</div>

<?php require 'inc/footer.php'; ?>
