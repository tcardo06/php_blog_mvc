<?php require 'inc/header.php'; ?>
<div class="container mt-5">
  <!-- Display a message if no posts exist -->
      <?php if (isset($this->oPosts) && count($this->oPosts) === 0): ?>
          <p class="alert alert-warning">Aucun article de blog pour le moment.</p>
      <?php endif; ?>

      <?php if (isset($this->oPosts) && count($this->oPosts) === 0 && !empty($_SESSION['is_logged']) && $_SESSION['role'] === 'admin'): ?>
          <p>
              <button type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&amp;a=add'" class="btn btn-primary">Ajoutez votre premier post de blog !</button>
          </p>
      <?php endif; ?>

        <!-- Iterate over posts -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($this->oPosts as $oPost): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <!-- Blog Post Title -->
                            <h4 class="card-title">
                                <a href="<?=ROOT_URL?>?p=blog&amp;a=post&amp;id=<?=$oPost->id?>" class="text-dark" style="text-decoration: none; font-weight: bold;">
                                    <?=htmlspecialchars($oPost->title, ENT_QUOTES, 'UTF-8')?>
                                </a>
                            </h4>

                            <!-- Display the preview text -->
                            <p class="card-text flex-grow-1"><?=nl2br(htmlspecialchars(mb_strimwidth($oPost->preview, 0, 100, '...'), ENT_QUOTES, 'UTF-8')) ?></p>

                            <!-- Post Date (Check if the post was updated) -->
                            <p class="text-muted small">
                                <?php if (!empty($oPost->updatedDate) && $oPost->updatedDate !== '0000-00-00 00:00:00'): ?>
                                    Mis à jour le <?=htmlspecialchars($oPost->updatedDate, ENT_QUOTES, 'UTF-8')?>
                                    par <?=htmlspecialchars($oPost->author_name, ENT_QUOTES, 'UTF-8')?>
                                <?php else: ?>
                                    Publié le <?=htmlspecialchars($oPost->createdDate, ENT_QUOTES, 'UTF-8')?>
                                    par <?=htmlspecialchars($oPost->author_name, ENT_QUOTES, 'UTF-8')?>
                                <?php endif; ?>
                            </p>

                            <!-- 'See More' Button -->
                            <a href="<?=ROOT_URL?>?p=blog&amp;a=post&amp;id=<?=$oPost->id?>" class="btn btn-outline-primary mt-auto">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
</div>

<?php require 'inc/footer.php'; ?>
