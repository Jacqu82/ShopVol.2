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

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search'])) {
            $_SESSION['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
            $is_ok = true;

            $searchProducts = ProductRepository::searchProductsByName($connection, $_POST['search']);
            if (empty($_POST['search'])) {
                $is_ok = false;
            }
            if ($is_ok) {
                if ($searchProducts->rowCount() > 0) {
                    $_SESSION['count'] = 'Liczba znalezionych przedmiotów: ' . $searchProducts->rowCount();
                    header('Location: searchResultsPage.php');
                } else {
                    echo "<div class=\"flash-message alert alert-danger alert-dismissible\" role=\"alert\">";
                    echo '<strong>Brak wyników wyszukiwania</strong>';
                    echo "</div>";
                }
            }
        }
    }
    ?>

    <div class="col-md-4 navbar-left text-center">
        <form method="post" action="#">
            <input type="text" name="search" class="forms-search" placeholder="Czego szukasz?"><br/>
            <button type="submit" class="btn btn-success links-search">Szukaj</button>
        </form>
        <hr/>
        <h2>Wszystkie kategorie</h2>
        <hr/>
        <?php

        $categories = CategoryRepository::loadAllCategories($connection);
        foreach ($categories as $category) {
            $id = $category['id'];
            echo '<h4><ul class="nav nav-pills nav-stacked sidebar">
                <li><a href="categoryPage.php?id=' . $id . '">' . $category['name'] . '</a></li>
                </ul></h4>';
        }
        ?>
        <hr/>
        <h2>Bestsellery</h2>
        <hr/>
        <?php

        $bestSellers = OrderRepository::loadBestSellerProducts($connection);
        foreach ($bestSellers as $bestSeller) {
            $id = $bestSeller['id'];
            $name = substr($bestSeller['name'], 0, 28);
            $image = ImageRepository::loadFirstImageByProductId($connection, $id);
            echo "<h4><a href='productPage.php?id=$id' class='btn btn-success links-search'>$name</a><br/>
            <img src='" . $image['image_path'] . "' width='200' height='150'/></h4>";
            echo '<h3 class="price">Cena: ' . number_format($bestSeller['price'], 2) . ' zł</h3>';
            $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
            $countUsers = OrderRepository::countUsersFromOrders($connection, $id);
            echo handlingPolishGrammaticalCase::sumProductsAndCountUsers($sumProducts, $countUsers);
        }
        ?>

    </div>
    <div class="col-md-8">

        <?php

        $categories = CategoryRepository::loadAllCategories($connection);
        foreach ($categories as $category) {
            echo '<h2>' . $category['name'] . '</h2>';
            $products = ProductRepository::loadTwoRandomProductsByCategoryId($connection, $category['id']);
            if ($products->rowCount() > 0) {
                foreach ($products as $product) {
                    $id = $product['id'];
                    $name = trim(substr($product['name'], 0, 30));
                    $image = ImageRepository::loadFirstImageByProductId($connection, $id);
                    echo '<div class="col-md-4 col-md-offset-1"><ul class="nav navbar-nav">';
                    echo "<li><a href='productPage.php?id=$id'>$name</a>";
                    echo '<span class="price">Cena: ' . number_format($product['price'], 2) . ' zł</span></li><br/>';
                    $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
                    $countUsers = OrderRepository::countUsersFromOrders($connection, $id);
                    echo handlingPolishGrammaticalCase::sumProductsAndCountUsers($sumProducts, $countUsers);
                    echo '</ul></div>';
                    echo "<img src='" . $image['image_path'] . "' width='150' height='110'/><br/><br/>";
                }
            } else {
                echo '<div class="alert alert-warning">';
                echo '<strong>Brak produktów w kategorii ' . $category['name'] . '</strong>';
                echo '</div>';
            }
            echo '<hr/>';
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