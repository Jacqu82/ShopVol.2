<?php


class UserRepository
{
    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function saveToDB(PDO $connection, User $user)
    {
        $id = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword1();
        $city = $user->getCity();
        $postalCode = $user->getPostalCode();
        $street = $user->getStreet();
        $houseNr = $user->getHouseNr();

        if ($id == -1) {
            $sql = "INSERT INTO users (username, email, password, city, postal_code, street, house_nr)
                    VALUES (:username, :email, :password, :city, :postal_code, :street, :house_nr)";

            $result = $connection->prepare($sql);
            $result->bindParam('username', $username, PDO::PARAM_STR);
            $result->bindParam('email', $email, PDO::PARAM_STR);
            $result->bindParam('password', $password, PDO::PARAM_STR);
            $result->bindParam('city', $city, PDO::PARAM_STR);
            $result->bindParam('postal_code', $postalCode, PDO::PARAM_INT);
            $result->bindParam('street', $street, PDO::PARAM_STR);
            $result->bindParam('house_nr', $houseNr, PDO::PARAM_INT);

            $result->execute();
            return true;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $username
     * @return PDOStatement
     */
    public static function loadAllUsersByUsername(PDO $connection, $username)
    {
        $sql = "SELECT id FROM users WHERE username = :username";

        $result = $connection->prepare($sql);
        $result->bindParam('username', $username, PDO::PARAM_STR);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $email
     * @return PDOStatement
     */
    public static function loadAllUsersByEmail(PDO $connection, $email)
    {
        $sql = "SELECT id FROM users WHERE email = :email";

        $result = $connection->prepare($sql);
        $result->bindParam('email', $email, PDO::PARAM_STR);
        $result->execute();

        return $result;
    }

    /**
     * @param PDO $connection
     * @param $username
     * @return bool|User
     */
    public static function loadUserByUsername(PDO $connection, $username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";

        $result = $connection->prepare($sql);
        $result->bindParam('username', $username, PDO::PARAM_STR);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $user = new User();
            $user
                ->setId($row['id'])
                ->setUsername($row['username'])
                ->setEmail($row['email'])
                ->setHash($row['password'])
                ->setCreatedAt($row['created_at']);

            return $user;
        }

        return false;
    }

    /**
     * @param PDO $connection
     * @param $id
     * @return bool|User
     */
    public static function loadUserById(PDO $connection, $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        $result = $connection->prepare($sql);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $user = new User();
            $user
                ->setId($row['id'])
                ->setUsername($row['username'])
                ->setEmail($row['email'])
                ->setHash($row['password'])
                ->setCity($row['city'])
                ->setPostalCode($row['postal_code'])
                ->setStreet($row['street'])
                ->setHouseNr($row['house_nr'])
                ->setCreatedAt($row['created_at']);

            return $user;
        }

        return false;
    }
}
