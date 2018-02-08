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
        $amount = number_format($order['amount'], 2);
        echo '<h3>Łączna kwota: ' . $amount . ' zł</h3>';
        echo "
        <div class='img-thumbnail1'>
            <img src='" . $order['image_path'] . "' width='150' height='100'/><br/>
        </div><br/>";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deliveryMethod']) && isset($_POST['paymentMethod'])) {
            $deliveryMethod = filter_input(INPUT_POST, 'deliveryMethod', FILTER_SANITIZE_STRING);
            $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);

            if (OrderRepository::updateDeliveryAndPayment($connection, $user->getId(), $deliveryMethod, $paymentMethod)) {
                header('Location: summaryPage.php');
                $_SESSION['payment_done'] = 'Poprawnie dokonano płatności :)';
            }
        }
    }
    ?>
    <hr/>
    <form method="post" action="#">
        <label for="deliveryMethod">Wybierz sposób dostawy:</label><br/>
        <select name="deliveryMethod" class="forms">
            <option value="Kurier">Kurier</option>
            <option value="Poczta Polska">Poczta Polska</option>
            <option value="Odbior osobisty">Odbior osobisty</option>
        </select><br/>
        <label for="paymentMethod">Wybierz sposób płatności:</label><br/>
        <select name="paymentMethod" class="forms">
            <option value="Gotówka">Gotówka</option>
            <option value="Karta płatnicza">Karta płatnicza</option>
            <option value="Przelew jednorazowy">Przelew jednorazowy</option>
            <option value="payU">payU</option>
            <option value="payPal">payPal</option>
        </select><br/>
        <button type="submit" class="btn btn-success button">Zapłać i przejdź do podsmumowania</button>
    </form>

    <hr/>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>