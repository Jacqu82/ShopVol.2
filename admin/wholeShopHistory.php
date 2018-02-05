<?php

require_once '../src/lib.php';
require_once '../connection.php';

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: ../web/index.php');
    exit();
}

$admin = loggedAdmin($connection);

?>

<!DOCTYPE html>
<html lang="pl">
<?php

include '../widget/head.php';

?>
<body>
<?php

include 'header.php';

?>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>

    <h3>Pełna historia zakupów</h3>

    <table align="center">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Zamawiający</th>
            <th>Ilość</th>
            <th>Łączna kwota</th>
            <th>Status</th>
            <th>Metoda dostawy</th>
            <th>Metoda płatności</th>
            <th>Rodzaj zakupu</th>
            <th>Data zamówienia</th>
        </tr>
        </thead>
        <?php

        $histories = OrderRepository::loadWholeShopHistory($connection);
        foreach ($histories as $history) {
            $amount = number_format($history['amount'], 2);
            ?>
            <tbody>
            <tr>
                <td><?php echo $history['name']; ?></td>
                <td><?php echo $history['username']; ?></td>
                <td><?php echo $history['quantity']; ?></td>
                <td><?php echo $amount; ?></td>
                <td><?php echo $history['status']; ?></td>
                <td><?php echo $history['delivery_method']; ?></td>
                <td><?php echo $history['payment_method']; ?></td>
                <td><?php echo $history['kind']; ?></td>
                <td><?php echo $history['created_at']; ?></td>
            </tr>
            </tbody>
            <?php
        }
        ?>
    </table>

    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>