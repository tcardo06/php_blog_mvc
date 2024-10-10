<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="text-center mb-4">
                <img src="<?= ROOT_URL ?>assets/images/profile.png" alt="Your Profile Picture" class="img-fluid rounded-circle" width="200">
            </div>

            <h1 class="text-center">À Propos de Moi</h1>
            <p class="text-center">
                Ma passion pour l’informatique et les jeux vidéo m’a permis d’intégrer la Coding Factory. J’apprends à coder en plusieurs langages et à travailler avec la méthode Scrum. J’aime travailler en équipe, je suis curieux et organisé.
            </p>

            <!-- CV download link -->
            <div class="text-center mb-4">
                <a href="<?= ROOT_URL ?>assets/documents/THOMAS_CARDOSO_DEVELOPPEUR_WEB.pdf" target="_blank" class="btn btn-outline-secondary">Télécharger mon CV</a>
            </div>

            <hr>

            <h2 class="text-center">Contactez-moi</h2>
            <form action="<?= ROOT_URL ?>?p=contact&amp;a=submit" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>

                <!-- Send Message button centered -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'inc/footer.php'; ?>
