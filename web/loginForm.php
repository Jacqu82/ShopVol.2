<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>
<body>
<div class="container text-center">
    <h1>Zaloguj się na swoje konto</h1>
    <form method="POST" action="login.php">
        <div>
            <label for="nameField">Login:</label>
            <div>
                <input type="text" name="username" class="forms" id="nameField" placeholder="Login"/>
            </div>
        </div>
        <div>
            <label for="passwordField">Hasło:</label>
            <div>
                <input type="password" name="password" class="forms" id="passwordField" placeholder="Hasło"/>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-success button">Zaloguj się</button>
        </div>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class=\"alert alert-danger\">";
            echo '<strong>' . $_SESSION['error'] . '</strong>';
            echo "</div>";
            unset($_SESSION['error']);
        }
        ?>
    </form>
    <hr/>
    <div class="row">
        <h3>Nie masz konta?</h3>
        <h4><a href="registerForm.php" class="btn btn-info links">Zarejestruj się</a></h4>
    </div>
    <a href="index.php" class="btn btn-default links">Powrót</a>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>