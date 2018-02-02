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

    <h2>Historia Twoich zakupów</h2>

    <table align="center">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Ilość</th>
            <th>Łączna kwota</th>
            <th>Status</th>
            <th>Metoda dostawy</th>
            <th>Metoda płatności</th>
        </tr>
        </thead>
        <?php

        $orders = OrderRepository::loadAllOrdersByUserId($connection, $user->getId());
        foreach ($orders as $order) {
            $amount = number_format($order['amount'], 2);
            ?>
            <tbody>
            <tr>
                <td><?php echo $order['name']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $amount; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><?php echo $order['delivery_method']; ?></td>
                <td><?php echo $order['payment_method']; ?></td>
            </tr>
            </tbody>
            <?php
        }
        ?>
    </table>

    <hr/>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>