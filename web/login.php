<?php

require_once '../connection.php';
require_once 'autoload.php';

session_start();

if (isset($_POST['username']) || isset($_POST['password'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $user = UserRepository::loadUserByUsername($connection, $username);
    if (!$user) {
        $_SESSION['error'] = 'Niepoprawny login lub hasło!';
        header('Location: loginForm.php');
    }
    if (password_verify($password, $user->getPassword1())) {
        $_SESSION['login'] = true;
        $_SESSION['id'] = $user->getId();

        unset($_SESSION['error']);
        header('Location: mainPage.php');
    } else {
        $_SESSION['error'] = 'Niepoprawny login lub hasło!';
        header('Location: loginForm.php');
    }
} else {
    header('Location: index.php');
    exit();
}
