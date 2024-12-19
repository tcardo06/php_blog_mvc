<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
      /* Styles for social media icons */
      .social-icon {
          font-size: 24px;
          color: #333; /* Default color for icons */
      }
      .social-icon:hover {
          color: #007bff;
      }
      .social-icon-link {
          text-decoration: none;
      }
    </style>
  </head>
  <body>
    <footer class="mt-5">
        <p class="italic text-center">
            <a href="https://github.com/tcardo06/php_blog_mvc" target="_blank" title="GitHub" class="me-3 social-icon-link">
                <i class="bi bi-github social-icon"></i>
            </a>
            <a href="https://www.linkedin.com/in/thomas-cardoso/" target="_blank" title="LinkedIn" class="me-3 social-icon-link">
                <i class="bi bi-linkedin social-icon"></i>
            </a>
            &nbsp; | &nbsp;
            <?php if ($this->isLogged): ?>
                <?php if ($this->role === 'admin'): ?>
                    Connecté en tant qu'Admin - <a href="<?=ROOT_URL?>?p=admin&amp;a=logout">Déconnexion</a> &nbsp; | &nbsp;
                    <a href="<?=ROOT_URL?>?p=admin&amp;a=dashboard">Tableau de bord</a>
                <?php else: ?>
                    Connecté en tant qu'Utilisateur - <a href="<?=ROOT_URL?>?p=admin&amp;a=logout">Déconnexion</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?=ROOT_URL?>?p=user&amp;a=login">Connexion</a>
            <?php endif; ?>
        </p>
    </footer>
  </body>
</html>
