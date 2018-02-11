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
$unpaidCount = OrderRepository::countAllUnPaidBuyNowOrdersByUserId($connection, $user->getId());

?>
<div class="container text-center">
    <h1>All Or Nothing</h1>
    <hr/>
    <h2>Witaj <?php echo $user->getUsername() . "!"; ?></h2>
    <h3>Twój adres E-mail: <?php echo $user->getEmail(); ?></h3>
    <h3>Adres do wysyłki:</h3>
    <h3><?php echo $user->getPostalCode() . ', ' . $user->getCity(); ?></h3>
    <h3><?php echo 'ul.' . $user->getStreet() . '/' . $user->getHouseNr(); ?></h3>
    <h3>Data utworzenia profilu: <?php echo $user->getCreatedAt(); ?></h3>
    <hr/>
    <h3><a href="followedProductPage.php" class="btn btn-success links">Twoje obserwowane oferty</a></h3>
    <h3><a href="unpaidOrdersPage.php" class="btn btn-success links">Nieopłacone zamówienia
            <span class="badge"><?php echo $unpaidCount; ?></span></a></h3>
    <h3><a href="editUserProfile.php" class="btn btn-warning links">Edytuj profil</a></h3>
    <hr/>
    <h3><a href="mainPage.php" class="btn btn-default links">Powrót do Strony głównej</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>