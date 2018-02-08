<?php

require_once '../src/lib.php';
require_once '../connection.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

$user = loggedUser($connection);

?>

<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>
<body>
<?php

include '../widget/header.php';

?>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username'], $_POST['userSubmit'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $is_ok = true;

            //check username length
            if ((strlen($username) < 3) || (strlen($username) > 15)) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Login musi zawierać od 3 do 15 znaków!</strong>";
                echo "</div>";
            }

            //check exiting username
            $users = UserRepository::loadAllUsersByUsername($connection, $username);
            if ($users->rowCount() > 0) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo '<strong>Login ' . $_POST['username'] . ' już znajduje się w bazie danych! Wybierz inny!</strong>';
                echo "</div>";
            }

            //alphanumeric letters
            if (ctype_alnum($username) === false) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Login może skaładać się tylko z liter i cyfr (bez polskich znaków)</strong>";
                echo "</div>";
            }

            if ($is_ok) {
                $user->setUsername($username);
                UserRepository::updateUsername($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Login zmieniony na ' . $user->getUsername() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['email'], $_POST['emailSubmit'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $is_ok = true;

            //check unique email
            $emails = UserRepository::loadAllUsersByEmail($connection, $email);
            if ($emails->rowCount() > 0) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo '<strong>Adres ' . $_POST['email'] . ' już istnieje w bazie danych!</strong>';
                echo "</div>";
            }

            //check correct e-mail
            if (empty($email)) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>To nie jest poprawny adres e-mail!</strong>";
                echo "</div>";
            }

            if ($is_ok) {
                $user->setEmail($email);
                UserRepository::updateEmail($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Adres E-mail zmieniony na ' . $user->getEmail() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['oldPassword']) && isset($_POST['newPassword1']) && isset($_POST['newPassword2'],
                $_POST['passSubmit'])) {
            $oldPassword = filter_input(INPUT_POST, 'oldPassword', FILTER_SANITIZE_STRING);
            $newPassword1 = filter_input(INPUT_POST, 'newPassword1', FILTER_SANITIZE_STRING);
            $newPassword2 = filter_input(INPUT_POST, 'newPassword2', FILTER_SANITIZE_STRING);
            $is_ok = true;

            //enter old password
            if (!password_verify($oldPassword, $user->getPassword1())) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Wpisz swoje stare hasło!</strong>";
                echo "</div>";
            }

            //check password length
            if ((strlen($newPassword2) < 6) || (strlen($newPassword2) > 15)) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Hasło musi zawierać od 6 do 15 znaków!</strong>";
                echo "</div>";
            }

            //check password repeat
            if ($newPassword1 != $newPassword2) {
                $is_ok = false;
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Hasła muszą być identyczne!</strong>";
                echo "</div>";
            }

            if ($is_ok) {
                $user->setPassword1($newPassword2);
                UserRepository::updatePassword($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Hasło poprawnie zmienione</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['city'], $_POST['citySubmit'])) {
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
            $is_ok = true;

            if (empty($city)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Nazwa miasta nie może być pusta!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if ($is_ok) {
                $user->setCity($city);
                UserRepository::updateCity($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Miasto zostało zmienione na ' . $user->getCity() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['postalCode'], $_POST['postalCodeSubmit'])) {
            $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
            $is_ok = true;

            if (empty($postalCode)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Uzupełnij kod pocztowy!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if ($is_ok) {
                $user->setPostalCode($postalCode);
                UserRepository::updatePostalCode($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Kod pocztowy zmieniony na ' . $user->getPostalCode() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['street'], $_POST['streetSubmit'])) {
            $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
            $is_ok = true;

            if (empty($street)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Uzupełnij ulice i nr domu!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if ($is_ok) {
                $user->setStreet($street);
                UserRepository::updateStreet($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Ulica zmieniona na ' . $user->getStreet() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['houseNr'], $_POST['houseNrSubmit'])) {
            $houseNr = filter_input(INPUT_POST, 'houseNr', FILTER_SANITIZE_NUMBER_INT);
            $is_ok = true;

            if (empty($houseNr)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Uzupełnij nr mieszkania!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if ($is_ok) {
                $user->setHouseNr($houseNr);
                UserRepository::updateHouseNr($connection, $user);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Numer mieszkania zmieniony na ' . $user->getHouseNr() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['deleteAccount'])) {
            if (UserRepository::delete($connection, $user)) {
                if (isset($_SESSION['login'])) {
                    unset($_SESSION['login']);
                }
                $_SESSION['delete_account'] = "Poprawnie usunołeś swoje konto!";
                header('Location: index.php');
            }
        }
    }

    ?>

    <form action="#" method="post">
        <h3>Edycja profilu</h3>
        <p class="text-primary">Zmień login:</p>
        <div class="form-group">
            <input type="text" class="forms" name="username" value="<?php echo $user->getUsername(); ?>">
            <br/>
            <button type="submit" name="userSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Aktualizuj adres E-mail:</p>
        <div class="form-group">
            <input type="email" class="forms" name="email" value="<?php echo $user->getEmail(); ?>">
            <br/>
            <button type="submit" name="emailSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Zmień hasło:</p>
        <div class="form-group">
            <input type="password" class="forms" name="oldPassword" placeholder="Stare hasło">
            <br/>
            <input type="password" class="forms" name="newPassword1" placeholder="Nowe hasło">
            <br/>
            <input type="password" class="forms" name="newPassword2" placeholder="Powtórz nowe hasło">
            <br/>
            <button type="submit" name="passSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Aktualizuj swoje miasto:</p>
        <div class="form-group">
            <input type="text" class="forms" name="city" value="<?php echo $user->getCity(); ?>">
            <br/>
            <button type="submit" name="citySubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Aktualizuj kod pocztowy:</p>
        <div class="form-group">
            <input type="text" class="forms" name="postalCode" value="<?php echo $user->getPostalCode(); ?>">
            <br/>
            <button type="submit" name="postalCodeSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Aktualizuj ulice i nr domu:</p>
        <div class="form-group">
            <input type="text" class="forms" name="street" value="<?php echo $user->getStreet(); ?>">
            <br/>
            <button type="submit" name="streetSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <p class="text-primary">Aktualizuj nr mieszkania:</p>
        <div class="form-group">
            <input type="text" class="forms" name="houseNr" value="<?php echo $user->getHouseNr(); ?>">
            <br/>
            <button type="submit" name="houseNrSubmit" class="btn btn-warning links">Zmień</button>
        </div>
        <hr/>
        <div>
            <div class="form-group">
                <button type="submit" name="deleteAccount" class="btn btn-danger links">Usuń konto</button>
            </div>
        </div>
    </form>
    <hr/>
    <h3><a href="userPanel.php" class="btn btn-default links">Powrót do Strony użytkownika</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>