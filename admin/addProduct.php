<?php

require_once '../src/lib.php';
require_once '../connection.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../web/index.php');
    exit();
}

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
        if (isset($_POST['name'])
            && isset($_POST['price'])
            && isset($_POST['description'])
            && isset($_POST['availability'])
            && isset($_POST['categories'])
            && $_POST['name'] != ''
            && $_POST['price'] > 0
            && $_POST['description'] != '') {

            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_NUMBER_INT);

            $product = new Product();
            $product
                ->setName($name)
                ->setPrice($price)
                ->setDescription($description)
                ->setAvailability($availability)
                ->setCategoryId($_POST['categories']);

            ProductRepository::saveToDB($connection, $product);

            echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
            echo '<strong>Poprawnie dodano przedmiot do bazy</strong>';
            echo "</div>";
        } else {
            echo "<div class=\"flash-message alert alert-danger alert-dismissible\" role=\"alert\">";
            echo '<strong>Wprowadź poprawne dane!</strong>';
            echo "</div>";
        }
    }


    ?>

    <form method="POST" action="#">
        <div>
            <input type="text" name="name" class="forms" placeholder="Nazwa"/>
        </div>
        <div>
            <input type="text" name="price" class="forms" placeholder="Cena"/>
        </div>
        <div>
            <textarea name="description" rows="4" class="forms" placeholder="Opis produktu"></textarea>
        </div>
        <div>
            <input type="number" name="availability" class="forms" placeholder="Dostępność"/>
        </div>
        <br/>
        Wybierz kategorie:<br/>
        <select name="categories" class="forms">
            <?php
            $categories = CategoryRepository::loadAllCategories($connection);
            foreach ($categories as $category) {
                echo "<option value='" . $category['id'] . "' class='forms'>" . $category['name'] . "</option>";
            }
            ?>
        </select>
        <div>
            <button type="submit" class="btn btn-success button">Dodaj produkt</button>
        </div>
    </form>
    <hr/>

    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>