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
        return $this;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Student
    {
        $this->faculty_id = $faculty_id;
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
<<<<<<< HEAD
        if (! $this->getID()) {
            $sql = 'INSERT INTO student (id, faculty_id) VALUES (:id, :faculty_id)';
=======
        if(! $this->getId()) {
            $sql = 'INSERT INTO schedules (subject_id, lecturer_id, faculty_id, group_id, room_id, time_start, time_end, color) VALUES (:subject_id, :lecturer_id, :faculty_id, :group_id, :room_id, :time_start, :time_end, :color)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'subject_id' => $this->getSubjectId(),
                'lecturer_id' => $this->getLecturerId(),
                'faculty_id' => $this->getFacultyId(),
                'group_id' => $this->getGroupId(),
                'room_id' => $this->getRoomId(),
                'time_start' => $this->getTimeStart(),
                'time_end' => $this->getTimeEnd(),
                'color' => $this->getColor()
            ]);

            $this->setId((int)$pdo->lastInsertId());
        } else {
            $sql = 'UPDATE schedules SET subject_id = :subject_id, lecturer_id = :lecturer_id, faculty_id = :faculty_id, group_id = :group_id, room_id = :room_id, time_start = :time_start, time_end = :time_end, color = :color WHERE id = :id';
>>>>>>> 61f9b68bdda23bd8938c4013947f88d6cdba52d2
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId(),
                'faculty_id' => $this->getFacultyId()
            ]);
<<<<<<< HEAD
        } else {
            $sql = 'UPDATE student SET faculty_id = :faculty_id WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'faculty_id' => $this->getFacultyId(),
                'id' => $this->getId()
            ]);
        }    
    }    
=======
        }
    }
>>>>>>> 61f9b68bdda23bd8938c4013947f88d6cdba52d2
}