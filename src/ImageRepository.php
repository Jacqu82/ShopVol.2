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

            $id = $connection->lastInsertId();
            return true;
        }
        return false;
    }
}
