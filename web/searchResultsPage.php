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

    echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
    echo '<strong>' . $_SESSION['count'] . '</strong>';
    echo "</div>";

    $searchProducts = ProductRepository::searchProductsByName($connection, $_SESSION['search']);
    foreach ($searchProducts as $searchProduct) {
        $id = $searchProduct['id'];
        $name = substr($searchProduct['name'], 0, 30);
        $image = ImageRepository::loadFirstImageByProductId($connection, $id);
        echo "<h4><a href='productPage.php?id=$id' class='btn btn-success links'>$name</a><br/>
        <img src='" . $image['image_path'] . "' width='300' height='200'/></h4>";
        echo '<h3 class="price">Cena: ' . number_format($searchProduct['price'], 2) . ' zł</h3>';
        $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
        $countUsers = OrderRepository::countUsersFromOrders($connection, $id);
        echo handlingPolishGrammaticalCase::sumProductsAndCountUsers($sumProducts, $countUsers);
        echo '<hr/>';
    }
    ?>

    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>