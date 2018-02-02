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
    <div>
        <form action="#" method="POST">
            <h3>Napisz wiadomość: </h3>
            <?php

            $users = UserRepository::loadAllUsers($connection);

            echo "Wybierz użytkownika: <br/>";
            echo '<div>';
            echo "<select name='userId' class='forms'>";
            foreach ($users as $user) {
                echo "<option value='" . $user['id'] . "'>" . $user['username'] . "</option>";
            }
            echo "</select>";
            echo "</div>";
            ?>
            <textarea name="text" rows="4" class="forms" placeholder="Napisz wiadomość"
                      maxlength="140"></textarea><br/>
            <button class='btn btn-success links' type='submit'>Wyślij wiadomość</button>
        </form>
    </div>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userId']) && isset($_POST['text'])) {
    $userId = (int)$_POST['userId'];
    $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);

    $message = new Message();
    $message
        ->setAdminId($admin->getId())
        ->setUserId($userId)
        ->setText($text)
        ->setIsRead(0);

    if ($message) {
    MessageRepository::saveToDB($connection, $message);
    ?>
    <div class="flash-message alert alert-success alert-dismissible" role="alert">
        <strong>Wysłałeś wiadomość do
            <?php foreach ($users as $user) {
                if ($userId === $user['id']) {
                    echo $user['username'];
                }
            }
            echo '</strong>
            </div>';
            }
        }
    }

    $sent = MessageRepository::countAllSentMessages($connection, $admin->getId());

    ?>
    <hr/>
    <a href="outbox.php" class="btn btn-info links">Skrzynka nadawcza
        <span class="badge"><?php echo $sent; ?></span></a>

    <h3><a href="adminPanel.php" class="btn btn-default links">Powrót do panelu Admina</a></h3>
    </div>

    <?php

    include '../widget/footer.php';
    include '../widget/scripts.php';

    ?>
</body>
</html>