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

    $sum = BasketRepository::sumBasketProductsByUserId($connection, $user->getId());
    $basket = BasketRepository::loadBasketProductsByUserId($connection, $user->getId());
    foreach ($basket as $item) {
        $image = ImageRepository::loadFirstImageByProductId($connection, $item['product_id']);
        echo "<img src='" . $image['image_path'] . "' width='100' height='75'/>";
        echo '<h3>' . $item['name'] . ' | ';
        echo 'Cena: ' . number_format($item['amount'], 2) . ' zł | Ilość: ' . $item['quantity'] . '</h3>';
    }
    echo '<h3 class="price">Łączna kwota do zapłaty: ' . number_format($sum, 2) . ' zł</h3>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deliveryMethod']) && isset($_POST['paymentMethod'])) {
            $deliveryMethod = filter_input(INPUT_POST, 'deliveryMethod', FILTER_SANITIZE_STRING);
            $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);
            $_SESSION['deliveryMethod'] = $deliveryMethod;
            $_SESSION['paymentMethod'] = $paymentMethod;

            if (OrderRepository::updateBasketDeliveryAndPayment(
                $connection, $user->getId(), $deliveryMethod, $paymentMethod)) {
                header('Location: summaryBasketPage.php');
                $_SESSION['payment_done'] = 'Poprawnie dokonano płatności :)';
            }
        }
    }
    include '../widget/paymentAndDeliveryForm.php';
    ?>

    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>