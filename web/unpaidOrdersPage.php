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

    $unpaidOrders = OrderRepository::loadAllUnpaidOrdersByUserId($connection, $user->getId());
    foreach ($unpaidOrders as $unpaidOrder) {
        $id = $unpaidOrder['id'];
        $name = substr($unpaidOrder['name'], 0, 28);
        $totalAmount = number_format($unpaidOrder['amount'], 2);
        $image = ImageRepository::loadFirstImageByProductId($connection, $id);
        echo "<h4><a href='productPage.php?id=$id' class='btn btn-success links'>$name</a><br/>
            <img src='" . $image['image_path'] . "' width='300' height='200'/></h4>
            <h3 class='price'>Łączna cena: $totalAmount zł</h3>";
        echo '<h4>Ilość: ' . $unpaidOrder['quantity']. '</h4>';
        echo "<a href='unpaidPaymentPage.php?id=$id'
                class='btn btn-primary links'>Zapłać za przedmiot</a>";
        echo '<hr/>';
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