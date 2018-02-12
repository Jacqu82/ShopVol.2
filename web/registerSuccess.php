<?php

session_start();

if (!isset($_SESSION['register_success'])) {
    header('Location: index.php');
    exit();
} else {
    unset($_SESSION['register_success']);
}

//delete register errors

if (isset($_SESSION['e_username'])) {
    unset($_SESSION['e_username']);
}
if (isset($_SESSION['e_email'])) {
    unset($_SESSION['e_email']);
}
if (isset($_SESSION['e_password'])) {
    unset($_SESSION['e_password']);
}
if (isset($_SESSION['e_terms'])) {
    unset($_SESSION['e_terms']);
}
if (isset($_SESSION['e_bot'])) {
    unset($_SESSION['e_bot']);
}
if (isset($_SESSION['e_city'])) {
    unset($_SESSION['e_city']);
}
if (isset($_SESSION['e_postal_code'])) {
    unset($_SESSION['e_postal_code']);
}
if (isset($_SESSION['e_street'])) {
    unset($_SESSION['e_street']);
}
if (isset($_SESSION['e_house_nr'])) {
    unset($_SESSION['e_house_nr']);
}

//delete values from form

if (isset($_SESSION['fr_username'])) {
    unset($_SESSION['fr_username']);
}
if (isset($_SESSION['fr_email'])) {
    unset($_SESSION['fr_email']);
}
if (isset($_SESSION['fr_password1'])) {
    unset($_SESSION['fr_password1']);
}
if (isset($_SESSION['fr_password2'])) {
    unset($_SESSION['fr_password2']);
}
if (isset($_SESSION['fr_terms'])) {
    unset($_SESSION['fr_terms']);
}
if (isset($_SESSION['fr_city'])) {
    unset($_SESSION['fr_city']);
}
if (isset($_SESSION['fr_postal_code'])) {
    unset($_SESSION['fr_postal_code']);
}
if (isset($_SESSION['fr_street'])) {
    unset($_SESSION['fr_street']);
}
if (isset($_SESSION['fr_house_nr'])) {
    unset($_SESSION['fr_house_nr']);
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
    <h2>Gratulacje! Poprawnie założyłeś konto na All Or Nothing!</h2>
    <h3><a href="loginForm.php" class="btn btn-info links">Zaloguj się na swoje konto</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>