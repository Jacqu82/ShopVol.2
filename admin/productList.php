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

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id']) && isset($_GET['name'])) {
            $categoryId = $_GET['id'];
            $categoryName = $_GET['name'];

            echo '<h3>Przedmioty w kategorii: ' . $categoryName . '</h3>';

            $products = ProductRepository::loadAllProductsByCategoryId($connection, $categoryId);
            foreach ($products as $product) {
                $productId = $product['id'];
                $productName = $product['name'];

                echo "<a href='editOrDeleteProduct.php?id=$productId'
                class='btn btn-success links'>$productName</a> ";
            }
        }
    }

    ?>

    <h3><a href="categoryProductList.php" class="btn btn-default links">Powrót do listy kategorii</a></h3>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>