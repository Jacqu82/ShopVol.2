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
     * @return array|bool
     */
    public static function loadAllUsers(PDO $connection)
    {
        $sql = "SELECT id, username FROM users";

        $result = $connection->prepare($sql);
        $result->execute();

        $users = array();
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }

            return $users;
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

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updateUsername(PDO $connection, User $user)
    {
        $id = $user->getId();
        $name = $user->getUsername();

        $sql = "UPDATE users SET username = :username WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('username', $name, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updateEmail(PDO $connection, User $user)
    {
        $id = $user->getId();
        $email = $user->getEmail();

        $sql = "UPDATE users SET email = :email WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('email', $email, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updatePassword(PDO $connection, User $user)
    {
        $id = $user->getId();
        $password = $user->getPassword1();

        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('password', $password, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updateCity(PDO $connection, User $user)
    {
        $id = $user->getId();
        $city = $user->getCity();

        $sql = "UPDATE users SET city = :city WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('city', $city, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updatePostalCode(PDO $connection, User $user)
    {
        $id = $user->getId();
        $postalCode = $user->getPostalCode();

        $sql = "UPDATE users SET postal_code = :postal_code WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('postal_code', $postalCode, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updateStreet(PDO $connection, User $user)
    {
        $id = $user->getId();
        $street = $user->getStreet();

        $sql = "UPDATE users SET street = :street WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('street', $street, PDO::PARAM_STR);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function updateHouseNr(PDO $connection, User $user)
    {
        $id = $user->getId();
        $houseNr = $user->getHouseNr();

        $sql = "UPDATE users SET house_nr = :house_nr WHERE id = :id";
        $result = $connection->prepare($sql);

        if (!$result) {
            die("Query Error!" . $connection->errorInfo());
        }

        $result->bindParam('house_nr', $houseNr, PDO::PARAM_INT);
        $result->bindParam('id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * @param PDO $connection
     * @param User $user
     * @return bool
     */
    public static function delete(PDO $connection, User $user)
    {
        $id = $user->getId();

        if ($id != -1) {
            $sql = "DELETE FROM users WHERE id = :id";

            $result = $connection->prepare($sql);
            $result->bindParam('id', $id, PDO::PARAM_INT);
            $result->execute();

            return true;
        }

        return false;
    }
}
