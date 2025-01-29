<?php
namespace App\Model;

use App\Service\Config;

class Faculty
{
    private ?int $id = null;
    private ?string $faculty_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Faculty
    {
        $this->id = $id;
        return $this;
    }

    public function getFacultyName(): ?string
    {
        return $this->faculty_name;
    }

    public function setFacultyName(string $faculty_name): Faculty
    {
        $this->faculty_name = $faculty_name;
        return $this;
    }

    public static function fromArray($array): Faculty
    {
        $faculty = new self();
        $faculty->fill($array);

        return $faculty;
    }

    public function fill($array): Faculty
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['faculty_name'])) {
            $this->setFacultyName($array['faculty_name']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM faculty';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $faculties = [];
        $facultiesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($facultiesArray as $facultyArray) {
            $faculties[] = self::fromArray($facultyArray);
        }

        return $faculties;
    }

    public static function findFaculty(string $faculty_name): ?Faculty
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM faculty WHERE faculty_name = :faculty_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['faculty_name' => $faculty_name]);

        $facultyArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $facultyArray) {
            return null;
        }
        $faculty = self::fromArray($facultyArray);

        return $faculty;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getID()) {
            $sql = 'INSERT INTO faculty (faculty_name) VALUES (:faculty_name)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'faculty_name' => $this->getFacultyName()
            ]);

            $this->setId((int)$pdo->lastInsertId());
        } else {
            $sql = 'UPDATE faculty SET faculty_name = :faculty_name WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'faculty_name' => $this->getFacultyName(), 
                'id' => $this->getId()
            ]);
        }
    }
}