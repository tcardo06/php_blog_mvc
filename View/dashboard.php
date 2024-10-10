<?php require 'inc/header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="d-grid gap-3">
        <!-- Placeholder buttons for admin actions -->
        <button class="btn btn-primary btn-lg" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=add'">Create Post</button>
        <button class="btn btn-secondary btn-lg" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=manage&action=edit'">Edit Post</button>
        <button class="btn btn-danger btn-lg" type="button" onclick="window.location='<?=ROOT_URL?>?p=blog&a=manage&action=delete'">Delete Post</button>
    </div>
</div>
<?php require 'inc/footer.php'; ?>
