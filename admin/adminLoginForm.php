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
    <h2>Zaloguj się jako Administrator</h2>
    <hr/>
    <form method="POST" action="adminLogin.php">
        <div>
            <input type="text" name="login" class="forms" placeholder="Login"/>
        </div>
        <div>
            <input type="password" name="password" class="forms" placeholder="Hasło"/>
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
    <a href="../web/index.php" class="btn btn-default links">Powrót</a>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>