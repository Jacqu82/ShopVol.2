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

    $categories = CategoryRepository::loadAllCategories($connection);
    foreach ($categories as $category) {
        $id = $category['id'];
        $name = $category['name'];

        echo "<a href='productImageList.php?id=$id&name=$name'
                class='btn btn-success links'>$name</a> ";
    }
    ?>

    <hr/>
    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>