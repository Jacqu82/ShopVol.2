<?php

require_once '../src/lib.php';
require_once '../connection.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}

//if for every page for logged user!!!

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

    $product = ProductRepository::loadProductDetailsById($connection, $_GET['id']);

    echo '<h3>' . $product->getName() . '</h3>';
    $price = number_format($product->getPrice(), 2);
    echo '<h2>Cena: ' . $price . ' zł</h2>';
    echo '<h4>' . $product->getDescription() . '</h4>';
    echo '<h4>Dostępnych: ' . $product->getAvailability() . ' szt.</h4>';

    $images = ImageRepository::loadImageByProductId($connection, $_GET['id']);
    foreach ($images as $image) {

        echo "
        <div class='img-thumbnail1'>
            <img src='" . $image['image_path'] . "' width='450' height='300'/><br/>
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