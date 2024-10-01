<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?=\TestProject\Engine\Config::SITE_NAME?></title>
    <meta name="author" content="Thomas" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=ROOT_URL?>static/style.css" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
          <a class="navbar-brand" href="<?=ROOT_URL?>">Home</a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" href="<?=ROOT_URL?>?p=blog">Blog</a>
                  </li>
              </ul>
              <ul class="navbar-nav ms-auto">
                  <?php if (!isset($_SESSION['is_logged'])): ?>
                      <li class="nav-item">
                          <a class="nav-link" href="<?=ROOT_URL?>?p=user&a=login">Login</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="<?=ROOT_URL?>?p=user&a=register">Register</a>
                      </li>
                  <?php else: ?>
                      <li class="nav-item d-flex align-items-center">
                          <span class="navbar-text me-3">
                              <strong>Logged in as: <?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?></strong>
                          </span>
                          <a class="nav-link" href="<?=ROOT_URL?>?p=admin&a=logout">Logout</a>
                      </li>
                  <?php endif; ?>
              </ul>
          </div>
      </div>
  </nav>
  <div class="container mt-4">
