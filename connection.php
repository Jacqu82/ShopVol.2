<?php

$config = require_once 'config.php';

$options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $connection = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
        $config['user'], $config['password'], $options);
} catch (PDOException $e) {
    echo '<span style="color:red;">Błąd serwera. Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span><br/>';
    exit('Database error!' . $e->getMessage());
}
