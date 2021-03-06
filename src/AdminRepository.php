<?php


class AdminRepository
{
    /**
     * @param PDO $connection
     * @param $login
     * @return Admin|bool
     */
    public static function loadAdminByLogin(PDO $connection, $login)
    {
        $sql = "SELECT * FROM admins WHERE login = :login";

        $result = $connection->prepare($sql);
        $result->bindParam('login', $login, PDO::PARAM_STR);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $admin = new Admin();
            $admin
                ->setId($row['id'])
                ->setLogin($row['login'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setCreatedAt($row['created_at']);

            return $admin;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return Admin|bool
     */
    public static function loadAdminById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM admins WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $admin = new Admin();
            $admin
                ->setId($row['id'])
                ->setLogin($row['login'])
                ->setEmail($row['email'])
                ->setPassword($row['password'])
                ->setCreatedAt($row['created_at']);

            return $admin;
        }

        return false;
    }
}
