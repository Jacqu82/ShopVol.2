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

    $followedProducts = FollowRepository::loadAllFollowedProductsByUserId($connection, $user->getId());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['follow_id'], $_POST['delete_follow'])) {
            $followId = $_POST['follow_id'];
            $toDelete = FollowRepository::loadFollowById($connection, $followId);
            FollowRepository::delete($connection, $toDelete);
            header('Location: followedProductPage.php');
        }
    }

    if ($followedProducts->rowCount() > 0) {
        echo '<h2>Twoje obserwowane oferty</h2>';
        foreach ($followedProducts as $product) {
            $id = $product['id'];
            $name = substr($product['name'], 0, 28);
            $price = number_format($product['price'], 2);
            $image = ImageRepository::loadFirstImageByProductId($connection, $id);
            echo "<h4><a href='productPage.php?id=$id' class='btn btn-success links'>$name</a><br/>
            <img src='" . $image['image_path'] . "' width='300' height='200'/></h4>
            <h3 class='price'>Cena: $price zł</h3>";
            $sumProducts = OrderRepository::sumBoughtProducts($connection, $id);
            $countUsers = OrderRepository::countUsersFromOrders($connection, $id);
            echo handlingPolishGrammaticalCase::sumProductsAndCountUsers($sumProducts, $countUsers);
            echo "<form method='POST'>
                <input type=\"submit\" class=\"btn btn-danger links\" name=\"delete_follow\" value=\"Usuń z obserwowanych\"/>
                <input type='hidden' name='follow_id' value='" . $product['follow_id'] . " '>
              </form>";
            echo '<hr/>';
        }
    } else {
        echo '<h3>Nie obserwujesz żadnych przedmiotów!</h3>';
        echo '<div>
            <img src="../images/shop.jpg" width="500" height="300"/>
            </div>';
    }
    ?>

    <h3><a href="userPanel.php" class="btn btn-default links">Powrót do Strony użytkownika</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>