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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])
            && isset($_POST['availability']) && isset($_POST['categories'])) {
            $is_ok = true;

            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $availability = filter_input(INPUT_POST, 'availability', FILTER_SANITIZE_NUMBER_INT);

            if (strlen($name) > 30) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Nazwa przedmiotu może zawierać max 30 znaków!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if (empty($name)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Nazwa przedmiotu nie może być pusta!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if (empty($price)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Cena nie może być pusta!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if (!$price > 0) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Cena musi być większa od zera!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            if (empty($availability)) {
                echo "<div class=\"text-center alert alert-danger\">";
                echo "<strong>Pole dostępność nie może być puste!</strong>";
                echo "</div>";
                $is_ok = false;
            }

            $_SESSION['fr_name'] = $name;
            $_SESSION['fr_price'] = $price;
            $_SESSION['fr_description'] = $description;
            $_SESSION['fr_availability'] = $availability;

            if ($is_ok) {
                $product = new Product();
                $product
                    ->setName($name)
                    ->setPrice($price)
                    ->setDescription($description)
                    ->setAvailability($availability)
                    ->setCategoryId($_POST['categories']);

                ProductRepository::saveToDB($connection, $product);

                echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Poprawnie dodano przedmiot do bazy</strong>';
                echo "</div>";
            }
        }
    }

    ?>

    <form method="POST" action="#">
        <div>
            <input type="text" name="name" class="forms" placeholder="Nazwa"
                   value="<?php
                   if (isset($_SESSION['fr_name'])) {
                       echo $_SESSION['fr_name'];
                       unset($_SESSION['fr_name']);
                   }
                   ?>"/>
        </div>
        <div>
            <input type="text" name="price" class="forms" placeholder="Cena"
                   value="<?php
                   if (isset($_SESSION['fr_price'])) {
                       echo $_SESSION['fr_price'];
                       unset($_SESSION['fr_price']);
                   }
                   ?>"/>
        </div>
        <div>
            <textarea name="description" rows="4" class="forms" placeholder="Opis produktu"><?php
                if (isset($_SESSION['fr_description'])) {
                    echo $_SESSION['fr_description'];
                    unset($_SESSION['fr_description']);
                }
                ?></textarea>
        </div>
        <div>
            <input type="number" name="availability" class="forms" placeholder="Dostępność"
                   value="<?php
                   if (isset($_SESSION['fr_availability'])) {
                       echo $_SESSION['fr_availability'];
                       unset($_SESSION['fr_availability']);
                   }
                   ?>"/>
        </div>
        <br/>
        Wybierz kategorie:<br/>
        <select name="categories" class="forms">
            <?php
            $categories = CategoryRepository::loadAllCategories($connection);
            foreach ($categories as $category) {
                echo "<option value='" . $category['id'] . "' class='forms'>" . $category['name'] . "</option>";
            }
            ?>
        </select>
        <div>
            <button type="submit" class="btn btn-success button">Dodaj produkt</button>
        </div>
    </form>
    <hr/>

    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php

if (isset($_SESSION['fr_name'])) {
    unset($_SESSION['fr_name']);
}
if (isset($_SESSION['fr_price'])) {
    unset($_SESSION['fr_price']);
}
if (isset($_SESSION['fr_description'])) {
    unset($_SESSION['fr_description']);
}
if (isset($_SESSION['fr_availability'])) {
    unset($_SESSION['fr_availability']);
}

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>