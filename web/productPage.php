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

    $product = ProductRepository::loadProductById($connection, $_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'buyNow') {
        if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $userId = (int)$_POST['user_id'];
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            $amount = $product->getPrice() * $quantity;
            $is_ok = true;

            if (empty($quantity)) {
                $is_ok = false;
                $_SESSION['e_quantity'] = 'Wybierz ilość!';
            }
            if ($product->getAvailability() < $quantity) {
                echo '<div class="flash-message alert alert-warning">';
                echo '<strong>Brak takiej ilości na stanie!, dostępnych: ' . $product->getAvailability() . ' szt.</strong>';
                echo '</div>';
            } else {
                $order = new Order();
                $order
                    ->setUserId($userId)
                    ->setProductId($productId)
                    ->setQuantity($quantity)
                    ->setAmount($amount);
                if ($is_ok) {
                    OrderRepository::saveToDB($connection, $order);
                    ProductRepository::updateAvailabilityByQuantity($connection, $quantity, $productId);
                    header('Location: paymentPage.php');
                }
            }
        }
    }

    echo '<h3>' . $product->getName() . '</h3>';
    $price = number_format($product->getPrice(), 2);
    echo '<h2>Cena: ' . $price . ' zł</h2>';
    echo '<h4>Dostępnych: ' . $product->getAvailability() . ' szt.</h4>';

    if ($product->getAvailability() > 0) {
        ?>

        <form method="POST" action="#">
            <div>
                <input type="hidden" name="action" value="buyNow"/>
                <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>"/>
                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>"/>
                <?php
                if ($product->getAvailability() > 1) {
                    ?>
                    Wybierz ilość:<br/>
                    <input type="number" name="quantity" value="1" class="forms"/><br/>
                    <?php
                }
                if (isset($_SESSION['e_quantity'])) {
                    echo '<div class="flash-message alert alert-warning">';
                    echo '<strong>' . $_SESSION['e_quantity'] . '</strong>';
                    echo '</div>';
                    unset($_SESSION['e_quantity']);
                }
                ?>
                <button type="submit" class="btn btn-info links">Kup Teraz</button>
            </div>
        </form>

        <?php
    } else {
        echo '<div class="alert alert-danger">';
        echo '<strong>Brak na stanie, spróbuj później :)</strong>';
        echo '</div>';
    }
    echo '<hr/>';
    echo '<h4>' . $product->getDescription() . '</h4>';

    $images = ImageRepository::loadImageByProductId($connection, $_GET['id']);
    foreach ($images as $image) {

        echo "
        <div class='img-thumbnail1'>
            <img src='" . $image['image_path'] . "' width='450' height='350'/><br/>
        </div>";
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