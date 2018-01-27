<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<body>
<div class="container text-center">
    <h1>All Or Nothing - stwórz darmowe konto</h1>
    <div class="row">
        <h3>Masz konto?</h3>
        <h4><a href="loginForm.php" class="btn btn-info links">Zaloguj się</a></h4>
    </div>
    <hr/>
    <form method="POST" action="register.php">
        <div>
            <input type="text" name="username" class="forms" placeholder="Login"
                   value="<?php
                   if (isset($_SESSION['fr_username'])) {
                       echo $_SESSION['fr_username'];
                       unset($_SESSION['fr_username']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_username'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_username'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_username']);
        }
        ?>
        <div>
            <input type="email" name="email" class="forms" placeholder="E-mail"
                   value="<?php
                   if (isset($_SESSION['fr_email'])) {
                       echo $_SESSION['fr_email'];
                       unset($_SESSION['fr_email']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_email'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_email'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_email']);
        }
        ?>
        <div>
            <input type="password" name="password1" class="forms" placeholder="Hasło"
                   value="<?php
                   if (isset($_SESSION['fr_password1'])) {
                       echo $_SESSION['fr_password1'];
                       unset($_SESSION['fr_password1']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_password'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_password'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_password']);
        }
        ?>
        <div>
            <input type="password" name="password2" class="forms" placeholder="Powtórz hasło"
                   value="<?php
                   if (isset($_SESSION['fr_password2'])) {
                       echo $_SESSION['fr_password2'];
                       unset($_SESSION['fr_password2']);
                   }
                   ?>"/>
        </div>
        <div>
            <input type="text" name="city" class="forms" placeholder="Miasto"
                   value="<?php
                   if (isset($_SESSION['fr_city'])) {
                       echo $_SESSION['fr_city'];
                       unset($_SESSION['fr_city']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_city'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_city'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_city']);
        }
        ?>
        <div>
            <input type="text" name="postalCode" class="forms" placeholder="Kod pocztowy"
                   value="<?php
                   if (isset($_SESSION['fr_postal_code'])) {
                       echo $_SESSION['fr_postal_code'];
                       unset($_SESSION['fr_postal_code']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_postal_code'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_postal_code'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_postal_code']);
        }
        ?>
        <div>
            <input type="text" name="street" class="forms" placeholder="Ulica, Numer domu"
                   value="<?php
                   if (isset($_SESSION['fr_street'])) {
                       echo $_SESSION['fr_street'];
                       unset($_SESSION['fr_street']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_street'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_street'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_street']);
        }
        ?>
        <div>
            <input type="number" name="houseNr" class="forms" placeholder="Numer mieszkania"
                   value="<?php
                   if (isset($_SESSION['fr_house_nr'])) {
                       echo $_SESSION['fr_house_nr'];
                       unset($_SESSION['fr_house_nr']);
                   }
                   ?>"/>
        </div>
        <?php
        if (isset($_SESSION['e_house_nr'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_house_nr'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_house_nr']);
        }
        ?>
        <label>
            <input type="checkbox" name="terms" <?php
            if (isset($_SESSION['fr_terms'])) {
                echo 'checked';
                unset($_SESSION['fr_terms']);
            }
            ?> /> Akceptuję regulamin
        </label>
        <?php
        if (isset($_SESSION['e_terms'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_terms'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_terms']);
        }
        ?>
        <div class="g-recaptcha" data-sitekey="6Lcj6UEUAAAAACVwdIcYIGSXP4Ds4Lz5OmvcD9H2"></div>
        <?php
        if (isset($_SESSION['e_bot'])) {
            echo '<div class="alert alert-warning">';
            echo '<strong>' . $_SESSION['e_bot'] . '</strong>';
            echo '</div>';
            unset($_SESSION['e_bot']);
        }
        ?>
        <div>
            <button type="submit" class="btn btn-success button">Zarejestruj się</button>
        </div>
    </form>
    <hr/>
    <a href="index.php" class="btn btn-default links">Powrót</a>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>