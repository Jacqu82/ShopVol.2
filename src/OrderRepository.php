<?php


class OrderRepository
{

    /**
     * @param PDO $connection
     * @param Order $order
     * @return bool
     */
    public static function saveToDB(PDO $connection, Order $order)
    {
        $id = $order->getId();
        $userId = $order->getUserId();
        $productId = $order->getProductId();
        $quantity = $order->getQuantity();
        $amount = $order->getAmount();

        if ($id == -1) {
            $sql = "INSERT INTO orders (user_id, product_id, quantity, amount)
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
    public static function loadLastOrderByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT o.id, o.quantity, o.amount, o.status, p.name, i.image_path FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                LEFT JOIN images i ON i.product_id = p.id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC LIMIT 1";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @param $delivery
     * @param $payment
     * @return bool
     */
    public static function updateDeliveryAndPayment(PDO $connection, $id, $delivery, $payment)
    {
        $sql = "UPDATE orders SET delivery_method = :delivery_method,
                                  payment_method = :payment_method,
                                  status = :status
                WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->bindParam('delivery_method', $delivery, PDO::PARAM_STR);
        $result->bindParam('payment_method', $payment, PDO::PARAM_STR);
        $result->bindValue('status', 'Opłacony');
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadAllOrdersByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT o.id, o.quantity, o.amount, o.status, o.delivery_method, o.payment_method, p.name FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }
}
