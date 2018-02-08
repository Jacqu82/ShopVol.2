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

    $unpaidOrder = OrderRepository::loadUnpaidOrderByProductIdAndUserId($connection, $_GET['id'], $user->getId());
    foreach ($unpaidOrder as $item) {
        var_dump($item);
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
        <button type="submit" class="btn btn-success button">Przejdź do płatności</button>
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