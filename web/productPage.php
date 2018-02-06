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
        if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['o_quantity'])) {
            $userId = (int)$_POST['user_id'];
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['o_quantity'];
            $amount = $product->getPrice() * $quantity;
            $is_ok = true;

            if (empty($quantity)) {
                $is_ok = false;
                $_SESSION['quantity'] = 'Wybierz ilość!';
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
                    ->setAmount($amount)
                    ->setKind('Kup Teraz');
                if ($is_ok) {
                    OrderRepository::saveToDB($connection, $order);
                    ProductRepository::updateAvailabilityByQuantity($connection, $quantity, $productId);
                    header('Location: paymentPage.php');
                }
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'basket') {
        if (isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $userId = (int)$_POST['user_id'];
            $productId = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            $amount = $product->getPrice() * $quantity;
            $is_ok = true;

            if (empty($quantity)) {
                $is_ok = false;
                $_SESSION['quantity'] = 'Wybierz ilość!';
            }
            if ($product->getAvailability() < $quantity) {
                echo '<div class="flash-message alert alert-warning">';
                echo '<strong>Brak takiej ilości na stanie!, dostępnych: ' . $product->getAvailability() . ' szt.</strong>';
                echo '</div>';
            } else {
                $basket = new Basket();
                $basket
                    ->setUserId($userId)
                    ->setProductId($productId)
                    ->setQuantity($quantity)
                    ->setAmount($amount);
                if ($is_ok) {
                    BasketRepository::saveToDB($connection, $basket);
                    ProductRepository::updateAvailabilityByQuantity($connection, $quantity, $productId);
                    header('Location: basketPage.php');
                }
            }
        }
    }

    echo '<h3>' . $product->getName() . '</h3>';
    $category = CategoryRepository::loadCategoryById($connection, $product->getCategoryId());
    echo '<h4>Kategoria: ' . $category->getName() . '</h4>';
    $price = number_format($product->getPrice(), 2);
    echo '<h2 class="price">Cena: ' . $price . ' zł</h2>';
    echo '<h4>Dostępnych: ' . $product->getAvailability() . ' szt.</h4><hr/>';

    if ($product->getAvailability() > 0) {
        if (isset($_SESSION['quantity'])) {
            echo '<div class="flash-message alert alert-warning">';
            echo '<strong>' . $_SESSION['quantity'] . '</strong>';
            echo '</div>';
            unset($_SESSION['quantity']);
        }
        ?>
<!--    <div class="col-md-6">-->
        <form method="POST" action="#">
            <input type="hidden" name="action" value="buyNow"/>
            <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>"/>
            <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>"/>
            <input type="number" name="o_quantity" placeholder="Wybierz ilość" class="forms"/>
            <button type="submit">Kup Teraz</button>
        </form>
        <hr/>
<!--    </div>-->
<!--    <div class="col-md-6">-->
        <form method="POST" action="#">
            <input type="hidden" name="action" value="basket"/>
            <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>"/>
            <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>"/>
            <input type="number" name="quantity" placeholder="Wybierz ilość" class="forms"/>
            <button type="submit" class="btn btn-warning links">Kup przez koszyk</button>
        </form>
<!--    </div>-->

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