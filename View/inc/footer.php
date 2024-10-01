<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
      /* Add styles for the social media icons */
      .social-icon {
          font-size: 24px; /* Increase icon size */
          color: #333; /* Default color for icons */
      }
      .social-icon:hover {
          color: #000; /* Darker color on hover */
      }
      .social-icon-link {
          text-decoration: none; /* Remove underline */
      }
    </style>
  </head>
  <body>
      <footer>
          <p class="italic">
              <!-- GitHub and LinkedIn icons with Bootstrap Icons -->
              <a href="https://github.com/tcardo06/php_blog_mvc" target="_blank" title="GitHub" class="me-3 social-icon-link">
                  <i class="bi bi-github social-icon"></i>
              </a>
              <a href="https://www.linkedin.com/in/thomas-cardoso/" target="_blank" title="LinkedIn" class="me-3 social-icon-link">
                  <i class="bi bi-linkedin social-icon"></i>
              </a>
              &nbsp; | &nbsp;
              <?php if (!empty($_SESSION['is_logged'])): ?>
                  <?php if ($_SESSION['role'] === 'admin'): ?>
                      <!-- Only display for admin users -->
                      You are connected as Admin - <a href="<?=ROOT_URL?>?p=admin&amp;a=logout">Logout</a> &nbsp; | &nbsp;
                      <a href="<?=ROOT_URL?>?p=admin&amp;a=dashboard">Dashboard</a>
                  <?php else: ?>
                      <!-- For non-admin users -->
                      You are connected as User - <a href="<?=ROOT_URL?>?p=admin&amp;a=logout">Logout</a>
                  <?php endif; ?>
              <?php else: ?>
                  <a href="<?=ROOT_URL?>?p=user&amp;a=login">Login</a>
              <?php endif; ?>
          </p>
      </footer>
    </div>
  </body>
</html>
