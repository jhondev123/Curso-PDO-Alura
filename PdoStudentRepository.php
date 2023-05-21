<?php

namespace Alura\Pdo\infrastructer\Repository;
require_once "vendor/autoload.php";

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use http\Exception\RuntimeException;
use mysql_xdevapi\Exception;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private \PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $insertQuery = "SELECT * FROM students;";
        $stmt = $this->connection->query($insertQuery);
        return $this->hydrateStudentList($stmt);

    }

    public function studentsBirthAt(\DateTimeInterface $birthday): array
    {
        $sqlQuery = "SELECT * FROM students WHERE birth_day = ?;";
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(1, $birthday->format("Y-m-d"));
        $stmt->execute();
        return $this->hydrateStudentList($stmt);

    }

    public function saveStudent(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }
        return $this->update($student);
    }

    public function removeStudent(Student $student): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM STUDENTS WHERE id = ?;');
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function insert(Student $student)
    {
        $insertQuery = "INSERT INTO students (id,nome,birth_day)VALUES(:id,:name,:birth_day);";
        $stmt = $this->connection->prepare($insertQuery);
        if($stmt === false){
            throw new RuntimeException($this->connection->errorInfo()[2]);
        }
        $sucess = $stmt->execute([
            ":id" => $student->id(),
            ":name" => $student->name(),
            "birth_day" => $student->birthDate()->format("Y-m-d")
        ]);

        if ($sucess) {
            $student->defineId($this->connection->lastInsertId());
        }
        return $sucess;


    }

    private function update(Student $student):bool
    {
        $updateQuery = "UPDATE SET name = :name, birth_day = :birth_day WHERE id = :id;";
        $stmt = $this->connection->prepare($updateQuery);

        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birthday,$student', $student->birthDate()->format("Y-m-d"));
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($studentDataList as $studentData) {
           $studentList[] = new Student(
                $studentData["id"],
                $studentData["nome"],
                new DateTimeImmutable($studentData["birth_day"])
            );

        }
        return $studentList;
    }

    public function students(): array
    {
       return [];
    }

    public function studentsBirth_day(\DateTimeInterface $birthday): array
    {
       return [];
    }


    public function studentsWithPhone(): array
    {
        $querySql = "SELECT students.id,
        students.nome,
        students.birth_day,
        phones.id,
        phones.area_code,
        phones.number
        FROM students
        join phones ON students.id = phones.students_id;
         ";
        $stmt=$this->connection->query($querySql);
        $result =$stmt->fetchAll();
        $studentList=[];
        foreach ($result as $row){
            if(!array_key_exists($row['id'],$studentList)){
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                   new DateTimeImmutable($row['birth_day'])
                );
            }
            $phone = new Phone($row['id'],$row['area_code'],$row['number']);
            $studentList[$row['id']]->addPhone($phone);

        }
    }
}
