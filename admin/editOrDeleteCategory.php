<?php
require_once '../src/lib.php';
require_once '../connection.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../web/index.php');
    exit();
}
//if for every page for logged user!!!

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
        if (isset($_POST['name']) && isset($_POST['category_id']) && isset($_POST['update'])) {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            if (CategoryRepository::updateCategoryName($connection, $name, $_POST['category_id'])) {
                echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Poprawnie zmieniono nazwe kategorii</strong>';
                echo "</div>";
            }
        }

        if (isset($_POST['category_id']) && isset($_POST['delete'])) {
            $toDelete = CategoryRepository::loadCategoryById($connection, $_POST['category_id']);
            if (CategoryRepository::delete($connection, $toDelete)) {
                echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Poprawnie usunięto kategorię</strong>';
                echo "</div>";
            }
        }
    }

    $categories = CategoryRepository::loadAllCategories($connection);
    foreach ($categories as $category) {

        ?>

        <form method="POST" action="#">
            <div>
                <input type="text" name="name" class="forms"
                       value="<?php echo $category['name']; ?>" /><br/>
                <input type="hidden" name="category_id" value="<?php echo $category['id'];; ?>"/>
                <input type="submit" class="btn btn-warning links" name="update" value="Edytuj"/>
            </div>
            <div>
                <input type='hidden' name='category_id' value="<?php echo $category['id']; ?> ">
                <input type="submit" class="btn btn-danger links" name="delete" value="Usuń kategorię"/>
            </div>
        </form>
        <hr/>

        <?php
    }
    ?>

    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php
include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>