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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'saveImage') {
        if (($_FILES['imageFile']['error'] == 0)
            && ($_FILES['imageFile']['type'] == 'image/jpeg')
            && isset($_POST['products'])) {
            $productId = $_POST['products'];
            $filename = $_FILES['imageFile']['name'];
            $path = '../content/images/products/' . $productId . '/';
            if (!file_exists($path)) {
                mkdir($path);
            }
            $path .= $filename;
            if (!file_exists($path)) {
                $upload = move_uploaded_file($_FILES['imageFile']['tmp_name'], $path);
            } else {
                echo "<div class=\"flash-message text-center alert alert-danger alert-dismissible\" role=\"alert\">";
                echo '<strong>Zdjęcie o podanej nazwie już istnieje!</strong>';
                echo "</div>";
                die();
            }
            if ($upload) {
                $image = new Image();
                $image
                    ->setImagePath($path)
                    ->setProductId($_POST['products']);
                $upload = ImageRepository::saveToDB($connection, $image);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Zdjęcie dodane pomyślnie :)</strong>';
                echo "</div>";
            } else {
                echo "<div class=\"flash-message text-center alert alert-danger alert-dismissible\" role=\"alert\">";
                echo '<strong>Wystąpił błąd podczas dodawania zdjęcia!</strong>';
                echo "</div>";
                die();
            }
        }
    }


    ?>

    <form method="POST" action="#" enctype="multipart/form-data">
        Dodaj zdjęcie:<br/>
        <div class="file forms">
            <input type="file" name="imageFile"/>
        </div>
        <br/>
        Wybierz przedmiot:<br/>
        <select name="products" class="forms">
            <?php
            $products = ProductRepository::loadAllProducts($connection);
            foreach ($products as $product) {
                echo "<option value='" . $product['id'] . "' class='forms'>" . $product['name'] . "</option>";
            }
            ?>
        </select>
        <div>
            <input type="hidden" name="action" value="saveImage"/>
            <button type="submit" class="btn btn-success button">Dodaj zdjęcie</button>
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