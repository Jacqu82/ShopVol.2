<?php

require_once '../src/lib.php';
require_once '../connection.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

$user = loggedUser($connection);

?>

<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>
<body>

<?php

include '../widget/header.php';

?>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>

    <div class="col-md-4 navbar-left">
        <h2>Wszystkie kategorie</h2>
        <hr/>
        <?php

        $categories = CategoryRepository::loadAllCategories($connection);
        foreach ($categories as $category) {
            $id = $category['id'];
            echo '<h4><ul class="nav nav-pills nav-stacked sidebar">
                <li><a href="category.php?id=' . $id . '">' . $category['name'] . '</a></li>
                </ul></h4>';
        }

        ?>
    </div>
    <div class="col-md-8">

        <?php

        $categories = CategoryRepository::loadAllCategories($connection);
        foreach ($categories as $category) {
            $categoryName = $category['name'];
            echo '<h2>' . $categoryName . '</h2>';
            $products = ProductRepository::loadTwoRandomProductsByCategoryId($connection, $category['id']);

            if ($products->rowCount() > 0) {
                foreach ($products as $product) {
                    $id = $product['id'];
                    $name = substr($product['name'], 0, 30);

                    $image = ImageRepository::loadFirstImageDetailsByProductId($connection, $id);
                    $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
                    $countUsers = OrderRepository::countUsersFromOrders($connection, $user->getId(), $id);
                    echo '<div class="col-md-4 col-md-offset-1"><ul class="nav navbar-nav">';
                    echo "<li><a href='productPage.php?id=$id'>$name</a>";
                    $price = number_format($product['price'], 2);
                    echo '<span class="price">Cena: ' . $price . ' zł</span></li><br/>';
                    if (($sumProducts === null) && (!$countUsers)) {
                        echo '<span class="glyphicon glyphicon-user"></span> 0 osób kupiło 0 sztuk';
                    } else {
                        echo '<span class="glyphicon glyphicon-user"></span> ' . $countUsers . ' osób kupiło ' . $sumProducts . ' sztuk';
                    }
                    echo '</ul></div>';
                    echo "<img src='" . $image['image_path'] . "' width='150' height='100'/><hr/>";
                }
            } else {
                echo '<div class="alert alert-warning">';
                echo '<strong>Brak produktów w kategorii ' . $category['name'] . '</strong>';
                echo '</div>';
            }
        }

        ?>
    </div>
    <hr/>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>