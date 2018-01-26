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
        $sql = "SELECT p.id, p.name, p.price, i.image_path FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN images i ON i.product_id = p.id
                WHERE p.category_id = :category_id";

        $result = $connection->prepare($sql);
        $result->bindParam('category_id', $categoryId);
        $result->execute();

        return $result;
    }
}
