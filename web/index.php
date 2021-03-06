<?php

session_start();

if (isset($_SESSION['login'])) {
    header('Location: mainPage.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>

<body>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>

    <?php
    if (isset($_SESSION['delete_account'])) {
        echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
        echo '<strong>' . $_SESSION['delete_account'] . '</strong>';
        echo "</div>";
        unset($_SESSION['delete_account']);
    }
    ?>

    <h3><a href="loginForm.php" class="btn btn-primary links">Zaloguj się na swoje konto</a></h3>
    <h3><a href="../admin/adminLoginForm.php" class="btn btn-danger links">Zaloguj jako Administrator</a></h3>
    <h3><a href="registerForm.php" class="btn btn-primary links">Utwórz nowe konto</a></h3>
    <div>
        <img src="../images/shop.jpg" width="500" height="300"/>
    </div>
    <hr/>
</div>

<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>

</body>
</html>