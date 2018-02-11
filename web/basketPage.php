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
        if (isset($_POST['basket_id'], $_POST['delete_basket'])) {
            $basketId = (int)$_POST['basket_id'];

            $toDelete = BasketRepository::loadBasketById($connection, $basketId);
            BasketRepository::delete($connection, $toDelete);
            ProductRepository::updateAvailabilityAfterDeleteFromBasket($connection, $toDelete->getQuantity(), $_SESSION['product_id']);
            header('Location: basketPage.php');
        }
    }

    $unpaidBasket = OrderRepository::loadUnpaidBasketOrdersByUserId($connection, $user->getId());
    if ($unpaidBasket->rowCount() > 0) {
        OrderRepository::deleteAllUnpaidOrdersByUserId($connection, $user->getId());
    }

    $sum = BasketRepository::sumBasketProductsByUserId($connection, $user->getId());
    $basket = BasketRepository::loadBasketProductsByUserId($connection, $user->getId());
    if ($basket->rowCount() > 0) {
    echo '<h2>Wszytkie produkty w Twoim koszyku:</h2>';
    foreach ($basket as $item) {
        $_SESSION['product_id'] = $item['product_id'];
        $image = ImageRepository::loadFirstImageByProductId($connection, $item['product_id']);
        echo "<img src='" . $image['image_path'] . "' width='100' height='75'/>";
        echo '<h3>' . $item['name'] . ' | ';
        $amount = number_format($item['amount'], 2);
        echo 'Cena: ' . $amount . ' zł | Ilość: ' . $item['quantity'] . '</h3>';
        echo "<form method='POST'>
                <input type=\"submit\" class=\"btn btn-danger links\" name=\"delete_basket\" value=\"Usuń z koszyka\"/>
                <input type='hidden' name='basket_id' value='" . $item['id'] . " '>
              </form>";
        echo '<hr/>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['user_id'])) {
                $userId = (int)$_POST['user_id'];

                $order = new Order();
                $order
                    ->setUserId($userId)
                    ->setProductId($item['product_id'])
                    ->setQuantity($item['quantity'])
                    ->setAmount($item['amount'])
                    ->setKind('Koszyk');
                OrderRepository::saveToDB($connection, $order);
                header('Location: paymentBasketPage.php');
            }
        }
    }
    $total = number_format($sum, 2);
    echo '<h3>Łączna kwota do zapłaty ' . $total . '</h3>';

    ?>

    <form method="POST" action="#">
        <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>"/>
        <button type="submit" class="btn btn-success links">Przejdź do płatności</button>
    </form>
    <h3>LUB</h3>
    <h3><a href="mainPage.php" class="btn btn-info links">Kontynuuj zakupy</a></h3>
    <hr/>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

} else {
    echo '<h3>Twój koszyk jest pusty!</h3>';
    ?>

    <div>
        <img src="../images/shop.jpg" width="500" height="300"/>
    </div>
    <hr/>
    <?php
}

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>