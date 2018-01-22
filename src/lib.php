<?php

require_once 'autoload.php';

function loggedUser($connection)
{
    if (isset($_SESSION['id'])) {
        return UserRepository::loadUserById($connection, $_SESSION['id']);
    }
    return false;
}
