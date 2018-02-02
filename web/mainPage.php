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

    $categories = CategoryRepository::loadAllCategories($connection);
    foreach ($categories as $category) {
        echo '<h2>' . $category['name'] . '</h2>';

        $products = ProductRepository::loadAllProductsByCategoryId($connection, $category['id']);
        foreach ($products as $product) {

            $id = $product['id'];
            $name = $product['name'];

            echo "<h3><a href='productPage.php?id=$id'
                class='btn btn-success links'>$name</a></h3>";
            $price = number_format($product['price'], 2);
            echo '<h5>Cena: ' . $price . ' zł</h5>';

            $image = ImageRepository::loadFirstImageDetailsByProductId($connection, $id);
            echo "
            <div class='img-thumbnail1'>
                <img src='" . $image['image_path'] . "' width='150' height='100'/><br/>
            </div>
            <hr/>";
        }
    }

    ?>

    <hr/>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>