<?php


class CategoryRepository
{
    public static function saveToDB(PDO $connection, Category $category)
    {
        $id = $category->getId();
        $name = $category->getName();

        if ($id == -1) {
            $sql = "INSERT INTO categories (name) VALUES (:name)";

            $result = $connection->prepare($sql);
            $result->bindParam('name', $name);
            $result->execute();

            return true;
        }

        return false;
    }

    public static function loadAllCategories(PDO $connection)
    {
        $sql = "SELECT id, name FROM categories";

        $result = $connection->prepare($sql);
        $result->execute();

        return $result;
    }

    public static function updateCategoryName(PDO $connection, $name, $id)
    {
        $sql = "UPDATE categories SET name = :name WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('name', $name, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return $result;

    }

    public static function loadCategoryById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM categories WHERE id = :id";

        $result = $connection->prepare($sql);
        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $category = new Category();
            $category
                ->setId($row['id'])
                ->setName($row['name']);
            return $category;
        }

        return false;
    }

    public static function delete(PDO $connection, Category $category)
    {
        $id = $category->getId();

        if ($id != -1) {
            $sql = "DELETE FROM categories WHERE id = :id";

            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }
}
