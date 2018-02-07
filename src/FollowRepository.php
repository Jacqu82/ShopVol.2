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
        $sql = "SELECT p.id, p.name, p.price FROM products p
                LEFT JOIN follow f ON p.id = f.product_id
                WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->execute();

        return $result;
    }
}
