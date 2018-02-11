<?php


class FollowRepository
{
    /**
     * @param PDO $connection
     * @param Follow $follow
     * @return bool
     */
    public static function saveToDB(PDO $connection, Follow $follow)
    {
        $id = $follow->getId();
        $userId = $follow->getUserId();
        $productId = $follow->getProductId();

        if ($id == -1) {
            $sql = "INSERT INTO follow (user_id, product_id) VALUES (:user_id, :product_id)";

            $result = $connection->prepare($sql);
            $result->bindParam('user_id', $userId, PDO::PARAM_INT);
            $result->bindParam('product_id', $productId, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @param $productId
     * @return PDOStatement
     */
    public static function secureToAddOneProductToFollow(PDO $connection, $userId, $productId)
    {
        $sql = "SELECT id FROM follow
                WHERE user_id = :user_id
                AND product_id = :product_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->bindParam('product_id', $productId);
        $result->execute();

        if (!$result) {
            die("Connection Error" . $connection->errorInfo());
        }

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadAllFollowedProductsByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT p.id, p.name, p.price, f.id as follow_id FROM products p
                LEFT JOIN follow f ON p.id = f.product_id
                WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return bool|Follow
     */
    public static function loadFollowById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM follow WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $follow = new Follow();
            $follow
                ->setId($row['id'])
                ->setUserId($row['user_id'])
                ->setProductId($row['product_id'])
                ->setCreatedAt($row['created_at']);

            return $follow;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param Follow $follow
     * @return bool
     */
    public static function delete(PDO $connection, Follow $follow)
    {
        $id = $follow->getId();
        if ($id != -1) {
            $sql = "DELETE FROM follow WHERE id = :id";
            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function countAllFollowProductsByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT count(id) as count FROM follow WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['count'];
            }
        }

        return false;
    }
}
