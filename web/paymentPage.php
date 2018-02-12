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

    echo '<h3>Kupiłeś:</h3>';

    $orders = OrderRepository::loadLastOrderByUserId($connection, $user->getId());
    foreach ($orders as $order) {
        echo '<h2>' . $order['name'] . ' - ' . $order['quantity'] . ' szt.</h2>';
        echo '<h3 class="price">Łączna kwota: ' . number_format($order['amount'], 2) . ' zł</h3>';
        echo "<img src='" . $order['image_path'] . "' width='150' height='100'/>";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deliveryMethod']) && isset($_POST['paymentMethod'])) {
            $deliveryMethod = filter_input(INPUT_POST, 'deliveryMethod', FILTER_SANITIZE_STRING);
            $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);

            if (OrderRepository::updateDeliveryAndPaymentByOrderId(
                $connection, $order['id'], $user->getId(), $deliveryMethod, $paymentMethod)) {
                header('Location: summaryPage.php');
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