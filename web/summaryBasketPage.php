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

    if (isset($_SESSION['payment_done'])) {
        echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
        echo '<strong>' . $_SESSION['payment_done'] . '</strong>';
        echo '</div>';
        unset($_SESSION['payment_done']);
    }

    echo '<h2>Podsmumowanie zakupów</h2>';
    $sum = BasketRepository::sumBasketProductsByUserId($connection, $user->getId());
    $basket = BasketRepository::loadBasketProductsByUserId($connection, $user->getId());
    foreach ($basket as $item) {
        $image = ImageRepository::loadFirstImageByProductId($connection, $item['product_id']);
        echo "<img src='" . $image['image_path'] . "' width='100' height='75'/>";
        echo '<h3>' . $item['name'] . ' | ';
        $amount = number_format($item['amount'], 2);
        echo 'Cena: ' . $amount . ' zł | Ilość: ' . $item['quantity'] . '</h3>';
    }

    $total = number_format($sum, 2);
    echo '<h3 class="price">Łączna kwota do zapłaty: ' . $total . '</h3>';
    echo '<hr/>';
    if (isset($_SESSION['deliveryMethod'])) {
        echo '<h3>Metoda dostawy: ' . $_SESSION['deliveryMethod'] . '<h3/>';
        unset ($_SESSION['deliveryMethod']);
    }

    if (isset($_SESSION['paymentMethod'])) {
        echo '<h3>Metoda płatności: ' . $_SESSION['paymentMethod'] . '<h3/>';
        unset ($_SESSION['paymentMethod']);
    }

    echo '<h3>Adres do wysyłki:</h3>';
    echo '<h3>' . $user->getPostalCode() . ', ' . $user->getCity() . '</h3>';
    echo '<h3>ul.' . $user->getStreet() . '/' . $user->getHouseNr() . '</h3>';

    if ($basket->rowCount() > 0) {
        BasketRepository::deleteWholeBasketByUserId($connection, $user->getId());
    }
    ?>

    <hr/>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>