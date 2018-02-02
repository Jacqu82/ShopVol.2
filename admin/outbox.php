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
        if (isset($_POST['message_id'], $_POST['delete_message'])) {
            $messageId = (int)$_POST['message_id'];

            $toDelete = MessageRepository::loadMessageById($connection, $messageId);
            if (MessageRepository::delete($connection, $toDelete)) {
                echo "<div class=\"flash-message alert alert-success alert-dismissible\" role=\"alert\">";
                echo '<strong>Wiadomość poprawnie usunięta :)</strong>';
                echo "</div>";
            }
        }
    }

    $countSent = MessageRepository::countAllSentMessages($connection, $admin->getId());
    echo '<h3>Wszystkie wysłane wiadomości ( ' . $countSent . ' )</h3>';

    $sent = MessageRepository::loadAllSentMessagesByAdminId($connection, $admin->getId());
    foreach ($sent as $message) {
        echo 'Do: ' . $message['username'] . '<br/>';
        echo $message['text'] . '<br/>';
        echo $message['created_at'] . '<br/>';
        echo "<form method='POST'>
                <input type=\"submit\" class=\"btn btn-danger links\" name=\"delete_message\" value=\"Usuń wiadomość\"/>
                <input type='hidden' name='message_id' value='" . $message['id'] . " '>
              </form>";
        echo "<hr/>";
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