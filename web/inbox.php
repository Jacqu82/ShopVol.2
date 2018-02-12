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

        if (isset($_POST['set_message_as_read']) && isset($_POST['message_id'])) {
            $id = $_POST['message_id'];
            MessageRepository::setMessageStatus($connection, $id, 1);
        } else if (isset($_POST['set_message_as_unread']) && isset($_POST['message_id'])) {
            $id = $_POST['message_id'];
            MessageRepository::setMessageStatus($connection, $id, 0);
        }
    }

    $countReceived = MessageRepository::countAllReceivedMessages($connection, $user->getId());
    echo '<h3>Wszystkie otrzymane wiadomości ( ' . $countReceived . ' )</h3>';
    $countUnread = MessageRepository::countAllUnreadMessagesByUserId($connection, $user->getId());
    echo '<h4>Wszystkie nieprzyczatane wiadomości ( ' . $countUnread . ' )</h4>';

    $received = MessageRepository::loadAllReceivedMessagesByUserId($connection, $user->getId());
    foreach ($received as $message) {
        echo 'Od ' . $message['login'] . '<br/>';
        if ($message['is_read'] == 0) {
            echo "<form method='POST'>";
            echo "<b>" . $message['text'] . "<br/>" . $message['created_at'] . "</b><br/>
                    <input type='submit'  name='set_message_as_read' value='Oznacz jako przeczytaną' class='btn btn-success links' />
                    <input type='hidden' name='message_id' value='" . $message['id'] . " '>
                    <input type=\"submit\" class=\"btn btn-danger links\" name=\"delete_message\" value=\"Usuń wiadomość\"/>
                    <input type='hidden' name='message_id' value='" . $message['id'] . " '>
                </form>";
        } else if ($message['is_read'] == 1) {
            echo "<form method='POST'>";
            echo $message['text'] . "<br/>" . $message['created_at'] . "<br/>
                    <input type='submit'  name='set_message_as_unread' value='Oznacz jako nie przeczytaną' class='btn btn-success links' />
                    <input type='hidden' name='message_id' value='" . $message['id'] . " '>
                    <input type=\"submit\" class=\"btn btn-danger links\" name=\"delete_messege\" value=\"Usuń wiadomość\"/>
                    <input type='hidden' name='message_id' value='" . $message['id'] . " '>
                </form>";
        }
        echo "<hr/>";
    }
    ?>
    <a href="mainPage.php" class="btn btn-default links">Powrót do Strony głównej</a>
    <hr/>
</div>
<?php

include '../widget/footer.php';
include '../widget/scripts.php';

?>
</body>
</html>