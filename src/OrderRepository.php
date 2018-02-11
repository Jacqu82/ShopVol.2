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
        $kind = $order->getKind();

        if ($id == -1) {
            $sql = "INSERT INTO orders (user_id, product_id, quantity, amount, kind)
                    VALUES (:user_id, :product_id, :quantity, :amount, :kind)";

            $result = $connection->prepare($sql);
            $result->bindParam('user_id', $userId, PDO::PARAM_INT);
            $result->bindParam('product_id', $productId, PDO::PARAM_INT);
            $result->bindParam('quantity', $quantity, PDO::PARAM_INT);
            $result->bindParam('amount', $amount, PDO::PARAM_INT);
            $result->bindParam('kind', $kind, PDO::PARAM_INT);
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
        $sql = "SELECT o.id, o.quantity, o.amount, o.delivery_method, o.payment_method, p.name, i.image_path 
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                LEFT JOIN images i ON i.product_id = p.id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC LIMIT 1";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    public static function updateBuyNowDeliveryAndPayment(PDO $connection, $userId, $delivery, $payment)
    {
        $sql = "UPDATE orders SET delivery_method = :delivery_method,
                                  payment_method = :payment_method,
                                  status = :status
                WHERE user_id = :user_id 
                AND status = 'Nieopłacony'
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindParam('delivery_method', $delivery, PDO::PARAM_STR);
        $result->bindParam('payment_method', $payment, PDO::PARAM_STR);
        $result->bindValue('status', 'Opłacony');
        $result->bindValue('kind', 'Kup Teraz');
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @param $delivery
     * @param $payment
     * @return bool
     */
    public static function updateBasketDeliveryAndPayment(PDO $connection, $userId, $delivery, $payment)
    {
        $sql = "UPDATE orders SET delivery_method = :delivery_method,
                                  payment_method = :payment_method,
                                  status = :status
                WHERE user_id = :user_id 
                AND status = 'Nieopłacony'
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindParam('delivery_method', $delivery, PDO::PARAM_STR);
        $result->bindParam('payment_method', $payment, PDO::PARAM_STR);
        $result->bindValue('status', 'Opłacony');
        $result->bindValue('kind', 'Koszyk');
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
        $sql = "SELECT o.quantity, o.amount, o.status, o.kind,
                o.delivery_method, o.payment_method, o.created_at, p.name 
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @return bool
     */
    public static function sumBoughtProducts(PDO $connection, $productId)
    {
        $sql = "SELECT sum(quantity) as sum 
                FROM orders WHERE product_id = :product_id";
        $result = $connection->prepare($sql);
        $result->bindParam('product_id', $productId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['sum'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @return bool
     */
    public static function countUsersFromOrders(PDO $connection, $productId)
    {
        $sql = "SELECT count(user_id) as user FROM (SELECT count(id) as user_id
                FROM orders
                WHERE product_id = :product_id
                GROUP BY user_id) as first";
        $result = $connection->prepare($sql);
        $result->bindParam('product_id', $productId, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['user'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @return PDOStatement
     */
    public static function loadWholeShopHistory(PDO $connection)
    {
        $sql = "SELECT o.quantity, o.amount, o.status, o.delivery_method, o.kind, 
                o.payment_method, o.created_at, p.name ,u.username
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";

        $result = $connection->prepare($sql);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function sumOrderAmountByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT sum(amount) as amount FROM orders
                WHERE user_id = :user_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['amount'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadAllUnpaidOrdersByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT o.quantity, o.amount, p.id, p.name FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE user_id = :user_id
                AND status = :status
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindValue('status', 'Nieopłacony');
        $result->bindValue('kind', 'Kup Teraz');
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @param $userId
     * @return PDOStatement
     */
    public static function loadUnpaidOrderByProductIdAndUserId(PDO $connection, $productId, $userId)
    {
        $sql = "SELECT o.id, o.quantity, o.amount, p.name, p.id as product_id
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                AND o.product_id = :product_id
                AND status = :status
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindParam('product_id', $productId, PDO::PARAM_INT);
        $result->bindValue('status', 'Nieopłacony');
        $result->bindValue('kind', 'Kup Teraz');
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $orderId
     * @param $userId
     * @param $delivery
     * @param $payment
     * @return bool
     */
    public static function updateUnpaidDeliveryAndPaymentByOrderId(PDO $connection, $orderId, $userId, $delivery, $payment)
    {
        $sql = "UPDATE orders SET delivery_method = :delivery_method,
                                  payment_method = :payment_method,
                                  status = :status
                WHERE user_id = :user_id AND id = :order_id";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindParam('order_id', $orderId, PDO::PARAM_INT);
        $result->bindParam('delivery_method', $delivery, PDO::PARAM_STR);
        $result->bindParam('payment_method', $payment, PDO::PARAM_STR);
        $result->bindValue('status', 'Opłacony');
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @param $orderId
     * @return PDOStatement
     */
    public static function loadLastUnpaidOrderByUserIdAndOrderId(PDO $connection, $userId, $orderId)
    {
        $sql = "SELECT o.quantity, o.amount, o.delivery_method, o.payment_method, 
                p.id as product_id, p.name
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                AND o.id = :order_id
                AND status = :status";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId, PDO::PARAM_INT);
        $result->bindParam('order_id', $orderId, PDO::PARAM_INT);
        $result->bindValue('status', 'Opłacony');
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function countAllUnPaidBuyNowOrdersByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT count(id) as unpaid_count FROM orders
                WHERE user_id = :user_id
                AND kind = :kind
                AND status = :status";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->bindValue('kind', 'Kup Teraz');
        $result->bindValue('status', 'Nieopłacony');
        $result->execute();

        if ($result->rowCount() > 0) {
            foreach ($result as $row) {
                return $row['unpaid_count'];
            }
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @return PDOStatement
     */
    public static function loadBestSellerProducts(PDO $connection)
    {
        $sql = "SELECT sum(quantity) as suma, p.id, p.name, p.price
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                GROUP BY product_id
                HAVING suma >= 2
                ORDER BY suma DESC LIMIT 8";
        $result = $connection->prepare($sql);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return PDOStatement
     */
    public static function loadUnpaidBasketOrdersByUserId(PDO $connection, $userId)
    {
        $sql = "SELECT id FROM orders
                WHERE user_id = :user_id
                AND status = :status
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->bindValue('kind', 'Koszyk');
        $result->bindValue('status', 'Nieopłacony');
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $userId
     * @return bool
     */
    public static function deleteAllUnpaidOrdersByUserId(PDO $connection, $userId)
    {
        $sql = "DELETE FROM orders 
                WHERE user_id = :user_id
                AND status = :status
                AND kind = :kind";

        $result = $connection->prepare($sql);
        $result->bindParam('user_id', $userId);
        $result->bindValue('kind', 'Koszyk');
        $result->bindValue('status', 'Nieopłacony');
        $result->execute();

        return true;
    }
}
