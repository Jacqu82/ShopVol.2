<?php


class UserRepository
{
    public static function saveToDB(PDO $connection, User $user)
    {
        $id = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword1();
        $role = $user->getRole();

        if ($id == -1) {
            $sql = "INSERT INTO users (username, email, password, role)
                    VALUES (:username, :email, :password, :role)";

            $result = $connection->prepare($sql);
            $result->bindParam('username', $username, PDO::PARAM_STR);
            $result->bindParam('email', $email, PDO::PARAM_STR);
            $result->bindParam('password', $password, PDO::PARAM_STR);
            $result->bindParam('role', $role, PDO::PARAM_STR);

            $result->execute();
            return true;
        }

        return false;
    }

    public static function loadAllUsersByUsername(PDO $connection, $username)
    {
        $sql = "SELECT id FROM users WHERE username = :username";

        $result = $connection->prepare($sql);
        $result->bindParam('username', $username, PDO::PARAM_STR);
        $result->execute();

        return $result;
    }

    public static function loadAllUsersByEmail(PDO $connection, $email)
    {
        $sql = "SELECT id FROM users WHERE email = :email";

        $result = $connection->prepare($sql);
        $result->bindParam('email', $email, PDO::PARAM_STR);
        $result->execute();

        return $result;
    }

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
                ->setRole($row['role'])
                ->setCreatedAt($row['created_at']);

            return $user;
        }

        return false;
    }


}
