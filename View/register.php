<?php require 'inc/header.php' ?>
<?php require 'inc/msg.php' ?>

<form action="" method="post">

    <p><label for="email">Email:</label><br />
        <input type="email" name="email" id="email" required="required" />
    </p>

    <p><label for="password">Password:</label><br />
        <input type="password" name="password" id="password" required="required" />
    </p>

    <p><label for="confirm_password">Confirm Password:</label><br />
        <input type="password" name="confirm_password" id="confirm_password" required="required" />
    </p>

    <p><input type="submit" value="Register" /></p>
</form>

<?php require 'inc/footer.php' ?>
