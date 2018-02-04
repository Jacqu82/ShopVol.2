<?php


class ProductRepository
{
    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function saveToDB(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $name = $product->getName();
        $price = $product->getPrice();
        $description = $product->getDescription();
        $availability = $product->getAvailability();
        $categoryId = $product->getCategoryId();

        if ($id == -1) {
            $sql = "INSERT INTO products (name, price, description, availability, category_id)
                    VALUES (:name, :price, :description, :availability, :category_id)";

            $result = $connection->prepare($sql);
            $result->bindParam('name', $name);
            $result->bindParam('price', $price);
            $result->bindParam('description', $description);
            $result->bindParam('availability', $availability);
            $result->bindParam('category_id', $categoryId);

            $result->execute();
            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return bool|Product
     */
    public static function loadProductById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $product = new Product();
            $product
                ->setId($row['id'])
                ->setName($row['name'])
                ->setPrice($row['price'])
                ->setDescription($row['description'])
                ->setAvailability($row['availability'])
                ->setCategoryId($row['category_id']);

            return $product;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function updateProductName(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $name = $product->getName();

        $sql = "UPDATE products SET name = :name WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('name', $name, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function updateProductPrice(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $price = $product->getPrice();

        $sql = "UPDATE products SET price = :price WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('price', $price, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function updateProductDescription(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $description = $product->getDescription();

        $sql = "UPDATE products SET description = :description WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('description', $description, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function updateProductAvailability(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $availability = $product->getAvailability();

        $sql = "UPDATE products SET availability = :availability WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('availability', $availability, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function updateProductCategory(PDO $connection, Product $product)
    {
        $id = $product->getId();
        $categoryId = $product->getCategoryId();

        $sql = "UPDATE products SET category_id = :category_id WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @return PDOStatement
     */
    public static function loadAllProducts(PDO $connection)
    {
        $sql = "SELECT id, name FROM products";

        $result = $connection->prepare($sql);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $categoryId
     * @return PDOStatement
     */
    public static function loadAllProductsByCategoryId(PDO $connection, $categoryId)
    {
        $sql = "SELECT p.id, p.name, p.price FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id";

        $result = $connection->prepare($sql);
        $result->bindParam('category_id', $categoryId);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $categoryId
     * @return PDOStatement
     */
    public static function loadTwoRandomProductsByCategoryId(PDO $connection, $categoryId)
    {
        $sql = "SELECT p.id, p.name, p.price FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id
                ORDER BY RAND() LIMIT 2";

        $result = $connection->prepare($sql);
        $result->bindParam('category_id', $categoryId);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param Product $product
     * @return bool
     */
    public static function delete(PDO $connection, Product $product)
    {
        $id = $product->getId();

        if ($id != -1) {
            $sql = "DELETE FROM products WHERE id = :id";

            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $quantity
     * @param $id
     * @return bool
     */
    public static function updateAvailabilityByQuantity(PDO $connection, $quantity, $id)
    {
        $sql = "UPDATE products SET availability = availability - :quantity WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('quantity', $quantity, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param $quantity
     * @param $id
     * @return bool
     */
    public static function updateAvailabilityAfterDeleteFromBasket(PDO $connection, $quantity, $id)
    {
        $sql = "UPDATE products SET availability = availability + :quantity WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('quantity', $quantity, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }
}
