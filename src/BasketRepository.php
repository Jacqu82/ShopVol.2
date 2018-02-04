<?php


class BasketRepository
{
    /**
     * @param PDO $connection
     * @param Basket $basket
     * @return bool
     */
    public static function saveToDB(PDO $connection, Basket $basket)
    {
        $id = $basket->getId();
        $userId = $basket->getUserId();
        $productId = $basket->getProductId();
        $quantity = $basket->getQuantity();
        $amount = $basket->getAmount();

        if ($id == -1) {
            $sql = "INSERT INTO basket (user_id, product_id, quantity, amount)
                    VALUES (:user_id, :product_id, :quantity, :amount)";

            $result = $connection->prepare($sql);
            $result->bindParam('user_id', $userId, PDO::PARAM_INT);
            $result->bindParam('product_id', $productId, PDO::PARAM_INT);
            $result->bindParam('quantity', $quantity, PDO::PARAM_INT);
            $result->bindParam('amount', $amount, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadBasketProductsByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT b.id, b.amount, b.product_id, b.quantity, p.id as product_id, p.name, i.image_path 
                FROM basket b
                LEFT JOIN products p ON b.product_id = p.id
                LEFT JOIN images i ON i.product_id = p.id
                WHERE b.user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    public static function sumBasketProductsByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT sum(amount) as sum FROM basket WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['sum'];
            }
        }

        return false;
    }

    public static function countBasketProductsByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT count(id) as count FROM basket WHERE user_id = :user_id";

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

    public static function loadBasketById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM basket WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $basket = new Basket();
            $basket
                ->setId($row['id'])
                ->setUserId($row['user_id'])
                ->setProductId($row['product_id'])
                ->setQuantity($row['quantity'])
                ->setAmount($row['amount'])
                ->setCreatedAt($row['created_at']);

            return $basket;
        }

        return false;
    }

    //to fix!!!
    public static function delete(PDO $connection, Basket $basket)
    {
        $id = $basket->getId();
        if ($id != -1) {
            $sql = "DELETE FROM basket WHERE id = :id";
            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    public static function deleteWholeBasketByUserId(PDO $connection, Basket $basket, $userId)
    {
        $sql = "DELETE FROM basket WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->execute();

        return true;
    }
}