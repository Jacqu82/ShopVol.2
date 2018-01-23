<?php
require_once '../src/lib.php';
require_once '../connection.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../web/index.php');
    exit();
}
//if for every page for logged user!!!

$admin = loggedAdmin($connection);


?>

<!DOCTYPE html>
<html lang="pl">
<?php
include '../widget/head.php';
?>
<body>
<?php
include 'header.php';
?>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name'])) {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            $category = new Category();
            $category->setName($name);
            CategoryRepository::saveToDB($connection, $category);

            echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
            echo '<strong>Poprawnie dodano kategorię do bazy</strong>';
            echo "</div>";
        }
    }

    ?>

    <form method="POST" action="addCategory.php">
        <div>
            <input type="text" name="name" class="forms" placeholder="Kategoria"/>
        </div>
        <div>
            <button type="submit" class="btn btn-success button">Dodaj</button>
        </div>
    </form>
    <hr/>

    <?php

    $categories = CategoryRepository::loadAllCategories($connection);
    echo '<h3>Dostępne kategorie:</h3>';
    foreach ($categories as $category) {
        echo '<h4>' . $category['name'] . '</h4>';
    }

    ?>

    <hr/>
    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>