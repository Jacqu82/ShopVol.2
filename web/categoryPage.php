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
    $category = CategoryRepository::loadCategoryById($connection, $_GET['id']);
    ?>

    <div class="col-md-6">
        <form method="post" action="#">
            <input type="text" name="search" class="forms-filter"
                   placeholder='Szukaj w <?php echo $category->getName() ?>'>
            <button type="submit" class="btn btn-primary links">Szukaj</button>
        </form>
    </div>
    <div class="col-md-6">
        <form method="post" action="#">
            <select name="filter" class="forms-filter">
                <option value="" selected>Sortowanie</option>
                <option value="name">Nazwa</option>
                <option value="price">Cena: od najniższej</option>
                <option value="price DESC">Cena: od najwyższej</option>
            </select>
            <button type="submit" class="btn btn-warning links">Sortuj</button>
        </form>
    </div>

    <?php
    $search = '';
    $filter = 'name';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search'])) {
            $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        }
        if (isset($_POST['filter'])) {
            $filter = ($_POST['filter']);
        }
    }

    $products = ProductRepository::searchAndFilterProductsByNameAndCategory($connection, $search, $filter, $_GET['id']);

    foreach ($products as $product) {
        $id = $product['id'];
        $name = substr($product['name'], 0, 30);
        $price = number_format($product['price'], 2);
        $image = ImageRepository::loadRandomImageByProductId($connection, $id);
        echo "<h4><a href='productPage.php?id=$id' class='btn btn-success links'>$name</a><br/>
        <img src='" . $image['image_path'] . "' width='300' height='200'/></h4>
        <h3 class='price'>Cena: $price zł</h3>";
        $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
        $countUsers = OrderRepository::countUsersFromOrders($connection, $user->getId(), $id);
        if (($sumProducts === null) && (!$countUsers)) {
            echo '<h4><span class="glyphicon glyphicon-user"></span> 0 osób kupiło 0 sztuk</h4>';
        } else {
            echo '<h4><span class="glyphicon glyphicon-user"></span> ' . $countUsers . ' osób kupiło ' . $sumProducts . ' sztuk</h4>';
        }
        echo '<hr/>';
    }


    ?>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
    <hr/>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>