<?php

namespace App\Repositories;

use App\Dto\DtoInterface;
use PDO;
use PDOException;

class UserRepository extends BaseRepository
{
    /**
     * @param int $userId
     * @return false|null
     */
    public function getUserById(int $userId) {
        try {
            $query = "SELECT `username`,`email` FROM `users` WHERE `id`=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount())  {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * @param string $email
     * @return false|mixed|null
     */
    public function getUserByEmail(string $email) {
        try {
            $query = "SELECT `email` FROM `users` WHERE `email`=:email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createUser(DtoInterface $dto) {
        $query = "INSERT INTO `users`(`username`,`email`,`password`) VALUES(:name,:email,:password)";

        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($dto->username));
        $email = htmlspecialchars(strip_tags($dto->email));
        $password = password_hash($dto->password, PASSWORD_DEFAULT);

        // DATA BINDING
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        // TODO throw exception if statement executed with error
        if (!$stmt->execute()) {
            var_dump($stmt->errorInfo());
            die;
        } else {
            return true;
        }
    }
}
