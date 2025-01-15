<?php
namespace App\Model;

use App\Service\Config;

class Student
{
    private ?int $id = null;
    private ?int $faculty_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Student
    {
        $this->id = $id;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Student
    {
        $this->faculty_id = $faculty_id;
    }

    public static function fromArray($array): Student
    {
        $student = new self();
        $student->fill($array);

        return $student;
    }

    public function fill($array): Student
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['faculty_id'])) {
            $this->setFacultyId($array['faculty_id']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM student';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $students = [];
        $studentsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($studentsArray as $studentArray) {
            $students[] = self::fromArray($studentArray);
        }

        return $students;
    }

    public static function findStudent(int $faculty_id): ?Student
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM student WHERE faculty_id = :faculty_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['faculty_id' => $faculty_id]);

        $studentArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $studentArray) {
            return null;
        }
        $student = self::fromArray($studentArray);

        return $student;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getID()) {
            $sql = 'INSERT INTO student (faculty_id) VALUES (:faculty_id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'faculty_id' => $this->getFacultyId()
            ]);
            $this->setId((int) $pdo->lastInsertId());
        } else {
            $sql = 'UPDATE student SET faculty_id = :faculty WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'faculty_id' => $this->getFacultyId(),
                'id' => $this->getId()
            ]);
        }    
    }    
}