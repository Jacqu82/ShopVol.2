<?php

require_once 'autoload.php';

function loggedUser($connection)
{
    if (isset($_SESSION['id'])) {
        return UserRepository::loadUserById($connection, $_SESSION['id']);
    }

    return false;
}

function loggedAdmin($connection)
{
    if (isset($_SESSION['adminId'])) {
        return AdminRepository::loadUserById($connection, $_SESSION['adminId']);
    }

    return false;
}
