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

    $orders = OrderRepository::loadLastOrderByUserId($connection, $user->getId());
    foreach ($orders as $order) {
        echo "<img src='" . $order['image_path'] . "' width='100' height='75'/>";
        echo '<h3>' . $order['name'] . ' | Ilość: ' . $order['quantity'] . '</h3>';
        echo '<h3 class="price">Łączna kwota do zapłaty: ' . number_format($order['amount'], 2) . ' zł</h3>';
        echo '<hr/>';
        echo '<h3>Metoda dostawy: ' . $order['delivery_method'] . '</h3>';
        echo '<h3>Metoda płatności: ' . $order['payment_method'] . '</h3>';
    }
    echo '<h3>Adres do wysyłki:</h3>';
    echo '<h3>' . $user->getPostalCode() . ', ' . $user->getCity() . '</h3>';
    echo '<h3>ul.' . $user->getStreet() . '/' . $user->getHouseNr() . '</h3>';

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