<?php
namespace App\Model;

use App\Service\Config;

class Student
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Student
    {
        $this->id = $id;
        return $this;
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

    public static function findStudent(int $id): ?Student
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM student WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

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
        if (self::findStudent($this->getId())) {
            $sql = 'UPDATE student SET id = :id WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId()
            ]);
        } else {
            $sql = 'INSERT INTO student (id) VALUES (:id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId()
            ]);
        }
    }
}