<?php require 'inc/header.php'; ?>
<?php require 'inc/msg.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <form action="" method="post" class="border p-4 bg-white shadow-sm rounded">
            <h2 class="text-center mb-4">Register</h2>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required="required" />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required="required" />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required="required" />
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required="required" />
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
        </form>
    </div>
</div>

<?php require 'inc/footer.php'; ?>
