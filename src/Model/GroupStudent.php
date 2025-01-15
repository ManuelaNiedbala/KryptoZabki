<?php
namespace App\Model;

use App\Service\Config;

class GroupStudent
{
    private ?int $group_id = null;
    private ?int $student_id = null;

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id): GroupStudent
    {
        $this->group_id = $group_id;
    }

    public function getStudentId(): ?int
    {
        return $this->student_id;
    }

    public function setStudentId(int $student_id): GroupStudent
    {
        $this->student_id = $student_id;
    }

    public static function fromArray($array): GroupStudent
    {
        $groupStudent = new self();
        $groupStudent->fill($array);

        return $groupStudent;
    }

    public function fill($array): GroupStudent
    {
        if(isset($array['group_id']) && ! $this->getGroupId()) {
            $this->setGroupId($array['group_id']);
        }
        if(isset($array['student_id'])) {
            $this->setStudentId($array['student_id']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM group_student';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $groupStudents = [];
        $groupStudentsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($groupStudentsArray as $groupStudentArray) {
            $groupStudents[] = self::fromArray($groupStudentArray);
        }

        return $groupStudents;
    }

    public static function findGroupStudent(int $group_id, int $student_id): ?GroupStudent
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM group_student WHERE group_id = :group_id AND student_id = :student_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['group_id' => $group_id, 'student_id' => $student_id]);

        $groupStudentArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $groupStudentArray) {
            return null;
        }
        $groupStudent = self::fromArray($groupStudentArray);

        return $groupStudent;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(! $this->getGroupId() || ! $this->getStudentId()) {
            $sql = 'INSERT INTO group_student (group_id, student_id) VALUES (:group_id, :student_id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'group_id' => $this->getGroupId(), 
                'student_id' => $this->getStudentId()
            ]);
        } else {
            $sql = 'UPDATE group_student SET group_id = :group_id, student_id = :student_id WHERE group_id = :group_id AND student_id = :student_id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'group_id' => $this->getGroupId(), 
                'student_id' => $this->getStudentId()
            ]);
        }
    }
}