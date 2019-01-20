<?php

require_once '../autoload.php';

/**
 * @param $connection
 * @return bool|User
 */
function loggedUser($connection)
{
    if (isset($_SESSION['id'])) {
        return UserRepository::loadUserById($connection, $_SESSION['id']);
    }

    return false;
}

/**
 * @param $connection
 * @return Admin|bool
 */
function loggedAdmin($connection)
{
    if (isset($_SESSION['adminId'])) {
        return AdminRepository::loadAdminById($connection, $_SESSION['adminId']);
    }

    return false;
}
