<?php

session_start();

require_once '../connection.php';
require_once 'autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email'])
        && isset($_POST['password1']) && isset($_POST['password2'])
        && isset($_POST['city']) && isset($_POST['postalCode'])
        && isset($_POST['street']) && isset($_POST['houseNr'])) {
        $is_ok = true;
        //check validate

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
        $houseNr = filter_input(INPUT_POST, 'houseNr', FILTER_SANITIZE_NUMBER_INT);

        if ((strlen($username) < 3) || (strlen($username) > 15)) {
            $is_ok = false;
            $_SESSION['e_username'] = 'Login musi zawierać od 3 do 15 znaków!';
            header('Location: registerForm.php');
        }

        //check exiting username
        $users = UserRepository::loadAllUsersByUsername($connection, $username);
        if ($users->rowCount() > 0) {
            $is_ok = false;
            $_SESSION['e_username'] = 'Login ' . $_POST['username'] . ' już znajduje się w bazie danych! Wybierz inny!';
            header('Location: registerForm.php');
        }

        if (ctype_alnum($username) === false) {
            $is_ok = false;
            $_SESSION['e_username'] = 'Login może skaładać się tylko z liter i cyfr (bez polskich znaków)';
            header('Location: registerForm.php');
        }

        //check unique email
        $emails = UserRepository::loadAllUsersByEmail($connection, $email);
        if ($emails->rowCount() > 0) {
            $is_ok = false;
            $_SESSION['e_email'] = 'Adres ' . $_POST['email'] . ' już istnieje w bazie danych!';
            header('Location: registerForm.php');
        }

        if (empty($email)) {
            $is_ok = false;
            $_SESSION['e_email'] = 'To nie jest poprawny adres e-mail!';
            header('Location: registerForm.php');
        }

        if ((strlen($password1) < 6) || (strlen($password1) > 15)) {
            $is_ok = false;
            $_SESSION['e_password'] = 'Hasło musi zawierać od 6 do 15 znaków!';
            header('Location: registerForm.php');
        }

        if ($password1 != $password2) {
            $is_ok = false;
            $_SESSION['e_password'] = 'Hasła muszą być identyczne!';
            header('Location: registerForm.php');
        }

        if (empty($city)) {
            $is_ok = false;
            $_SESSION['e_city'] = 'Nazwa miasta nie może być pusta!';
            header('Location: registerForm.php');
        }

        if (empty($postalCode)) {
            $is_ok = false;
            $_SESSION['e_postal_code'] = 'Uzupełnij kod pocztowy!';
            header('Location: registerForm.php');
        }

        if (empty($street)) {
            $is_ok = false;
            $_SESSION['e_street'] = 'Uzupełnij ulice i nr domu!';
            header('Location: registerForm.php');
        }

        if (empty($houseNr)) {
            $is_ok = false;
            $_SESSION['e_house_nr'] = 'Uzupełnij nr mieszkania!';
            header('Location: registerForm.php');
        }

        if (!isset($_POST['terms'])) {
            $is_ok = false;
            $_SESSION['e_terms'] = "Potwierdź akceptację regulaminu!";
            header('Location: registerForm.php');
        }

        //check recaptcha
        $secret_key = "6Lcj6UEUAAAAALrGkFaVo6ZhIUpXA_aNe3wA5ndL";
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
        $response = json_decode($check);

        if ($response->success === false) {
            $is_ok = false;
            $_SESSION['e_bot'] = "Potwierdź że, nie jesteś botem!";
            header('Location: registerForm.php');
        }

        $_SESSION['fr_username'] = $username;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_password1'] = $password1;
        $_SESSION['fr_password2'] = $password2;
        $_SESSION['fr_city'] = $city;
        $_SESSION['fr_postal_code'] = $postalCode;
        $_SESSION['fr_street'] = $street;
        $_SESSION['fr_house_nr'] = $houseNr;
        if (isset($_POST['terms'])) {
            $_SESSION['fr_terms'] = true;
        }

        //validate_success!
        if ($is_ok) {
            $user = new User();
            $user
                ->setUsername($username)
                ->setEmail($email)
                ->setPassword1($password1)
                ->setCity($city)
                ->setPostalCode($postalCode)
                ->setStreet($street)
                ->setHouseNr($houseNr);
            UserRepository::saveToDB($connection, $user);

            $_SESSION['register_success'] = true;
            header('Location: registerSuccess.php');
        }
    } else {
        header('Location: registerForm.php');
        exit();
    }
}
