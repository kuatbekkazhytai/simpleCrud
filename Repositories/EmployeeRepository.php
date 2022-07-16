<?php
namespace App\Repositories;

use App\Dto\DtoInterface;
use PDO;

class EmployeeRepository extends BaseRepository
{
    /**
     * @return false|string
     */
    public function getEmployees() {
        $sqlQuery = "SELECT id, name, email, age, designation, created FROM " . $this->model->getTableName();
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $itemCount = $stmt->rowCount();
        if ($itemCount > 0) {
            $employees['itemCount'] = $itemCount;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $employee = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'age' => $age,
                    'designation' => $designation,
                    'created' => $created
                );
                $employees['body'][] = $employee;

            }
            $response = json_encode($employees);

        } else {
            http_response_code(404);
            $response = json_encode(
                array('message' => 'No record found.')
            );
        }
        return $response;
    }

    /**
     * @return false|string
     */
    public function getEmployeeById(int $id) {
        $sqlQuery = "SELECT id, name, email, age, designation, created
                      FROM
                        ". $this->model->getTableName() ."
                      WHERE 
                       id = ?
                      LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->model->id = $dataRow['id'];
            $this->model->name = $dataRow['name'];
            $this->model->email = $dataRow['email'];
            $this->model->age = $dataRow['age'];
            $this->model->designation = $dataRow['designation'];
            $this->model->created = $dataRow['created'];

            http_response_code(200);
            $response = json_encode($this->model);
        } else {
            http_response_code(404);
            $response = json_encode('Employee not found.');
        }

        return $response;
    }

    /**
     * @param DtoInterface $dto
     * @return bool
     */
    public function createEmployee(DtoInterface $dto): bool {
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
        $name = htmlspecialchars(strip_tags($dto->name));
        $email = htmlspecialchars(strip_tags($dto->email));
        $age = htmlspecialchars(strip_tags($dto->age));
        $designation = htmlspecialchars(strip_tags($dto->designation));
        $created = htmlspecialchars(strip_tags($dto->created));

        // bind data
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":designation", $designation);
        $stmt->bindParam(":created", $created);

        return $stmt->execute();
    }

    /**
     * @param DtoInterface $dto
     * @return bool
     */
    public function updateEmployee(DtoInterface $dto): bool {
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

        $name = htmlspecialchars(strip_tags($dto->name));
        $email = htmlspecialchars(strip_tags($dto->email));
        $age = htmlspecialchars(strip_tags($dto->age));
        $designation = htmlspecialchars(strip_tags($dto->designation));
        $created = htmlspecialchars(strip_tags($dto->created));
        $id = htmlspecialchars(strip_tags($dto->id));

        // bind data
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":designation", $designation);
        $stmt->bindParam(":created", $created);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteEmployeeById(int $id): bool {
        $sqlQuery = "DELETE FROM " . $this->model->getTableName() . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1,$id);

        return $stmt->execute();
    }
}
