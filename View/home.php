<?php require 'inc/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>À Propos de Moi</h1>
            <p>
                Ma passion pour l’informatique et les jeux vidéo m’a permis d’intégrer la Coding Factory. J’apprends à coder en plusieurs langages et à travailler avec la méthode Scrum. J’aime travailler en équipe, je suis curieux et organisé.
            </p>

            <hr>

            <h2>Contact Me</h2>
            <form action="<?= ROOT_URL ?>?p=contact&amp;a=submit" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php require 'inc/footer.php'; ?>
