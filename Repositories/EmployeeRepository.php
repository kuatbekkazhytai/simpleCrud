<?php
namespace App\Repositories;

use PDO;
use PDOStatement;
use App\Models\ModelInterface;

class EmployeeRepository
{
    /** @var ModelInterface */
    private $model;
    /** @var PDO */
    private $conn;

    /**
     * @param PDO $db
     * @param ModelInterface $model
     */
    public function __construct(PDO $db, ModelInterface $model) {
        $this->model = $model;
        $this->conn = $db;
    }

    /**
     * @return false|PDOStatement
     */
    public function getEmployees() {
        $sqlQuery = "SELECT id, name, email, age, designation, created FROM " . $this->model->getTableName();
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    /**
     * @return bool
     */
    public function createEmployee(): bool {
        $sqlQuery = "INSERT INTO
                        ". $this->model->getTableName() ."
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation, 
                        created = :created";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->model->name=htmlspecialchars(strip_tags($this->model->name));
        $this->model->email=htmlspecialchars(strip_tags($this->model->email));
        $this->model->age=htmlspecialchars(strip_tags($this->model->age));
        $this->model->designation=htmlspecialchars(strip_tags($this->model->designation));
        $this->model->created=htmlspecialchars(strip_tags($this->model->created));

        // bind data
        $stmt->bindParam(":name", $this->model->name);
        $stmt->bindParam(":email", $this->model->email);
        $stmt->bindParam(":age", $this->model->age);
        $stmt->bindParam(":designation", $this->model->designation);
        $stmt->bindParam(":created", $this->model->created);

        return $stmt->execute();
    }

    /**
     * @return void
     */
    public function getSingleEmployee(): void {
        $sqlQuery = "SELECT
                        id, 
                        name, 
                        email, 
                        age, 
                        designation, 
                        created
                      FROM
                        ". $this->model->getTableName() ."
                    WHERE 
                       id = ?
                    LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->model->id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->model->name = $dataRow['name'];
        $this->model->email = $dataRow['email'];
        $this->model->age = $dataRow['age'];
        $this->model->designation = $dataRow['designation'];
        $this->model->created = $dataRow['created'];
    }

    /**
     * @return bool
     */
    public function updateEmployee(): bool {
        $sqlQuery = "UPDATE
                        ". $this->model->getTableName() ."
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation, 
                        created = :created
                    WHERE 
                        id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->model->name = htmlspecialchars(strip_tags($this->model->name));
        $this->model->email = htmlspecialchars(strip_tags($this->model->email));
        $this->model->age = htmlspecialchars(strip_tags($this->model->age));
        $this->model->designation = htmlspecialchars(strip_tags($this->model->designation));
        $this->model->created = htmlspecialchars(strip_tags($this->model->created));
        $this->model->id = htmlspecialchars(strip_tags($this->model->id));

        // bind data
        $stmt->bindParam(":name", $this->model->name);
        $stmt->bindParam(":email", $this->model->email);
        $stmt->bindParam(":age", $this->model->age);
        $stmt->bindParam(":designation", $this->model->designation);
        $stmt->bindParam(":created", $this->model->created);
        $stmt->bindParam(":id", $this->model->id);

        return $stmt->execute();
    }

    /**
     * @return bool
     */
    public function deleteEmployee(): bool {
        $sqlQuery = "DELETE FROM " . $this->model->getTableName() . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->model->id = htmlspecialchars(strip_tags($this->model->id));
        $stmt->bindParam(1,$this->model->id);

        return $stmt->execute();
    }
}
