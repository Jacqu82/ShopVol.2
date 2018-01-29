<?php


class MessageRepository
{
    /**
     * @param PDO $connection
     * @param Message $message
     * @return bool
     */
    public static function saveToDB(PDO $connection, Message $message)
    {
        $id = $message->getId();
        $adminId = $message->getAdminId();
        $userId = $message->getUserId();
        $text = $message->getText();
        $isRead = $message->getIsRead();

        if ($id == -1) {
            $sql = "INSERT INTO messages (admin_id, user_id, text, is_read)
                    VALUES (:admin_id, :user_id, :text, :is_read)";

            $result = $connection->prepare($sql);
            $result->bindParam('admin_id', $adminId, PDO::PARAM_INT);
            $result->bindParam('user_id', $userId, PDO::PARAM_INT);
            $result->bindParam('text', $text, PDO::PARAM_STR);
            $result->bindParam('is_read', $isRead, PDO::PARAM_INT);

            $result->execute();
            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $adminId
     * @return PDOStatement
     */
    public static function loadAllSentMessagesByAdminId(PDO $connection, $adminId)
    {
        $sql = "SELECT m.id, m.text, m.created_at, u.username FROM messages m
                LEFT JOIN users u ON m.user_id = u.id
                WHERE m.admin_id = :admin_id
                ORDER BY created_at DESC";

        $result = $connection->prepare($sql);
        $result->bindParam('admin_id', $adminId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param Message $message
     * @return bool
     */
    public static function delete(PDO $connection, Message $message)
    {
        $id = $message->getId();
        if ($id != -1) {
            $sql = "DELETE FROM messages WHERE id = :id";
            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return bool|Message
     */
    public static function loadMessageById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM messages WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $message = new Message();
            $message
                ->setId($row['id'])
                ->setAdminId($row['admin_id'])
                ->setUserId($row['user_id'])
                ->setText($row['text'])
                ->setIsRead($row['is_read'])
                ->setCreatedAt($row['created_at']);

            return $message;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $adminId
     * @return bool
     */
    public static function countAllSentMessages(PDO $connection, $adminId)
    {
        $sql = "SELECT count(id) as sent FROM messages WHERE admin_id = :admin_id";
        $result = $connection->prepare($sql);
        $result->bindParam('admin_id', $adminId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['sent'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadAllReceivedMessagesByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT m.id, m.text, m.is_read, m.created_at, a.login FROM messages m
                LEFT JOIN admins a ON m.admin_id = a.id
                WHERE m.user_id = :user_id
                ORDER BY created_at DESC";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $messageId
     * @param $status
     * @return PDOStatement
     */
    public static function setMessageStatus(PDO $connection, $messageId, $status)
    {
        $sql = "UPDATE messages SET is_read = :status WHERE id = :message_id";

        $result = $connection->prepare($sql);
        $result->bindParam('status', $status, PDO::PARAM_INT);
        $result->bindParam('message_id', $messageId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function countAllReceivedMessages(PDO $connection, $userId)
    {
        $sql = "SELECT count(id) as received FROM messages WHERE user_id = :user_id";
        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['received'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function countAllUnreadMessagesByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT count(id) as unread FROM messages WHERE is_read = 0 AND user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['unread'];
            }
        }

        return false;
    }
}
