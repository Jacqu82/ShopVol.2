<?php

class ImageRepository
{
    /**
     * @param PDO $connection
     * @param Image $image
     * @return bool
     */
    public static function saveToDB(PDO $connection, Image $image)
    {
        $id = $image->getId();
        $imagePath = $image->getImagePath();
        $productId = $image->getProductId();

        if ($id == -1) {
            $sql = "INSERT INTO images (image_path, product_id) 
                    VALUES (:image_path, :product_id)";

            $result = $connection->prepare($sql);
            $result->bindParam('image_path', $imagePath, PDO::PARAM_STR);
            $result->bindParam('product_id', $productId, PDO::PARAM_INT);
            $result->execute();

            return true;
        }
        return false;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @return array
     */
    public static function loadAllImagesDetailsByProductId(PDO $connection, $productId)
    {
        $sql = "SELECT * FROM images WHERE product_id = :product_id";

        $result = $connection->prepare($sql);
        $result->bindParam('product_id', $productId);
        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->execute();
        $imageArray = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $imageArray[] = $row;
        }
        return $imageArray;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @return PDOStatement
     */
    public static function loadImageByProductId(PDO $connection, $productId)
    {
        $sql = "SELECT image_path FROM images WHERE product_id = :product_id";

        $result = $connection->prepare($sql);
        $result->bindParam('product_id', $productId, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $productId
     * @return mixed|null
     */
    public static function loadFirstImageByProductId(PDO $connection, $productId)
    {
        $sql = "SELECT image_path FROM images WHERE product_id = :product_id";

        $result = $connection->prepare($sql);
        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('product_id', $productId, PDO::PARAM_INT);
        $result->execute();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return mixed
     */
    public static function loadImagePath(PDO $connection, $id)
    {
        $sql = "SELECT image_path FROM images WHERE id = :id";

        $result = $connection->prepare($sql);
        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();
        $path = '';

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $path = $row['image_path'];
        }
        return $path;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return bool|Image
     */
    public static function loadImageById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM images WHERE id = :id";

        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $image = new Image();
            $image
                ->setId($row['id'])
                ->setImagePath($row['image_path'])
                ->setProductId($row['product_id']);

            return $image;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param Image $image
     * @return bool
     */
    public static function delete(PDO $connection, Image $image)
    {
        $id = $image->getId();

        if ($id != -1) {
            $sql = "DELETE FROM images WHERE id = :id";
            $result = $connection->prepare($sql);

            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $path
     * @param $id
     * @return PDOStatement
     */
    public static function updateImagePath(PDO $connection, $path, $id)
    {
        $sql = "UPDATE images SET image_path = :image_path WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('image_path', $path, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if (!$result) {
            die("Connection Error" . $connection->errorInfo());
        }

        return $result;
    }
}
