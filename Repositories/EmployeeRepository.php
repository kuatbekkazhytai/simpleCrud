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

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow) {
            $employee = array(
                'id' =>  $id,
                'name' => $dataRow['name'],
                'email' => $dataRow['email'],
                'age' => $dataRow['age'],
                'designation' => $dataRow['designation'],
                'created' => $dataRow['created']
            );
            http_response_code(200);
            $response = json_encode($employee);
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
        $this->model->name = htmlspecialchars(strip_tags($dto->name));
        $this->model->email = htmlspecialchars(strip_tags($dto->email));
        $this->model->age = htmlspecialchars(strip_tags($dto->age));
        $this->model->designation = htmlspecialchars(strip_tags($dto->designation));
        $this->model->created = htmlspecialchars(strip_tags($dto->created));

        // bind data
        $stmt->bindParam(":name", $this->model->name);
        $stmt->bindParam(":email", $this->model->email);
        $stmt->bindParam(":age", $this->model->age);
        $stmt->bindParam(":designation", $this->model->designation);
        $stmt->bindParam(":created", $this->model->created);

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

        $this->model->name = htmlspecialchars(strip_tags($dto->name));
        $this->model->email = htmlspecialchars(strip_tags($dto->email));
        $this->model->age = htmlspecialchars(strip_tags($dto->age));
        $this->model->designation = htmlspecialchars(strip_tags($dto->designation));
        $this->model->created = htmlspecialchars(strip_tags($dto->created));
        $this->model->id = htmlspecialchars(strip_tags($dto->id));

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
     * @param int $id
     * @return bool
     */
    public function deleteEmployeeById(int $id): bool {
        $sqlQuery = "DELETE FROM " . $this->model->getTableName() . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->model->id = htmlspecialchars(strip_tags($this->model->id));
        $stmt->bindParam(1,$id);

        return $stmt->execute();
    }
}
