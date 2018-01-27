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

    <?php

    $product = ProductRepository::loadProductById($connection, $_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name'], $_POST['nameSubmit'])) {
            $is_ok = true;
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            //validate name
            if (strlen($name) > 30) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Nazwa przedmiotu może zawierać max 50 znaków!</strong>";
                echo "</div>";
                $is_ok = false;
            }
            if (empty($name)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Nazwa przedmiotu nie może być pusta!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            //validate success
            if ($is_ok) {
                $product->setName($name);
                ProductRepository::updateProductName($connection, $product);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Zmieniono nazwe na ' . $product->getName() . '</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['price'], $_POST['priceSubmit'])) {
            $is_ok = true;
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);

            //validate price
            if (!$price > 0) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Cena musi być większa od zera!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            //validate success
            if ($is_ok) {
                $product->setPrice($price);
                ProductRepository::updateProductPrice($connection, $product);
                echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Zmieniono cene na ' . $product->getPrice() . ' zł</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['description'], $_POST['descriptionSubmit'])) {
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

            $product->setDescription($description);
            ProductRepository::updateProductDescription($connection, $product);
            echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
            echo '<strong>Opis zmieniony pomyślnie :)</strong>';
            echo "</div>";
        }

        if (isset($_POST['availability'], $_POST['availabilitySubmit'])) {
            $availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_NUMBER_INT);

            $product->setAvailability($availability);
            ProductRepository::updateProductAvailability($connection, $product);
            echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
            echo '<strong>Dostępność zmieniona na ' . $product->getAvailability() . '</strong>';
            echo "</div>";
        }

        if (isset($_POST['categories'], $_POST['categorySubmit'])) {
            $product->setCategoryId($_POST['categories']);
            ProductRepository::updateProductCategory($connection, $product);
            $categoryName = CategoryRepository::loadAllCategoriesById($connection, $product->getCategoryId());
            echo "<div class=\"flash-message text-center alert alert-success alert-dismissible\" role=\"alert\">";
            echo '<strong>Przedmiot przenisiony do kategorii: ' . $categoryName['name'] . '</strong>';
            echo "</div>";
        }

        if (isset($_POST['deleteProduct'])) {
            ProductRepository::delete($connection, $product);
            $_SESSION['delete_product'] = "Przedmiot został usunięty z bazy danych!";
            header('Location: adminPanel.php');
        }
    }

    ?>

    <div class="container" align="center">
        <form action="#" method="post">
            <h3>Edycja przedmiotu <?php echo $product->getName(); ?></h3>
            <p class="text-primary">Zmień nazwe:</p>
            <div class="form-group">
                <input type="text" class="forms" name="name" value="<?php echo $product->getName(); ?>"/>
                <br/>
                <button type="submit" name="nameSubmit" class="btn btn-warning links">Zmień</button>
            </div>
            <hr/>
            <p class="text-primary">Zmień cene:</p>
            <div class="form-group">
                <input type="text" class="forms" name="price" value="<?php echo $product->getPrice(); ?>"/>
                <br>
                <button type="submit" name="priceSubmit" class="btn btn-warning links">Zmień</button>
            </div>
            <hr/>
            <p class="text-primary">Zmień opis:</p>
            <div class="form-group">
                <textarea rows="5" name="description" class="forms"><?php echo $product->getDescription(); ?></textarea>
                <br>
                <button type="submit" name="descriptionSubmit" class="btn btn-warning links">Zmień</button>
            </div>
            <p class="text-primary">Zmień dostępność:</p>
            <div class="form-group">
                <input type="number" class="forms" name="availability"
                       value="<?php echo $product->getAvailability(); ?>"/>
                <br>
                <button type="submit" name="availabilitySubmit" class="btn btn-warning links">Zmień</button>
            </div>
            <p class="text-primary">Przenieś do innej kategorii:</p>
            <div class="form-group">
                <select name="categories" class="forms">
                    <?php
                    $categories = CategoryRepository::loadAllCategories($connection);
                    foreach ($categories as $category) {
                        echo "<option value='" . $category['id'] . "' class='forms'>" . $category['name'] . "</option>";
                    }
                    ?>
                </select>
                <br>
                <button type="submit" name="categorySubmit" class="btn btn-warning links">Zmień</button>
            </div>
            <hr/>
            <div>
                <div class="form-group">
                    <button type="submit" name="deleteProduct" class="btn btn-danger links">Usuń przedmiot</button>
                </div>
            </div>
        </form>
    </div>
    <hr/>
    <?php

    $categoryId = $product->getCategoryId();
    $catName = CategoryRepository::loadAllCategoriesById($connection, $product->getCategoryId());
    $categoryName = $catName['name'];

    echo "<a href='productList.php?id=$categoryId&name=$categoryName' class='btn btn-default links'>Powrót do przedmiotów</a>";

    ?>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>