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
    <h3>Witaj <?php echo $admin->getLogin(). "!"; ?></h3>
    <h3>Panel Administracyjny</h3>

    <a href="addCategory.php" class="btn btn-success links">Dodaj kategorię przedmiotów</a>
    <a href="categoryList.php" class="btn btn-warning links">Edytuj lub usuń kategorię</a>
    <a href="addProduct.php" class="btn btn-success links">Dodaj nowy przedmiot do bazy</a>
    <a href="addProductImage.php" class="btn btn-primary links">Dodaj zdjęcia do produktu</a>
<!--    <a href="manageComments.php" class="btn btn-info links">Zarządzaj komentarzami</a>-->
<!--    <a href="manageImages.php" class="btn btn-primary links">Zarządzaj zdjęciami użytkowników</a>-->
<!--    <a href="addNationalTeamFlag.php" class="btn btn-warning links">Dodaj flagi reprezentacji</a>-->
<!--    <a href="editNationalTeamFlag.php" class="btn btn-warning links">Edytuj flage reprezentacji</a>-->

    <hr/>
<!--    <a href="../web/mainPage.php" class="btn btn-default links">Strona główna</a>-->
    <hr/>
</div>

<?php

include '../widget/footer.php';
include '../widget/scripts.php';
?>
</body>
</html>
