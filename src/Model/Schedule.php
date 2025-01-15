<?php
namespace App\Model;

use App\Service\Config;

class  Schedule
{
    private ?int $id = null;
    private ?int $subject_id = null;
    private ?int $lecturer_id = null;
    private ?int $faculty_id = null;
    private ?int $group_id = null;
    private ?int $room_id = null;
    private ?string $time_start = null;
    private ?string $time_end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id):  Schedule
    {
        $this->id = $id;
    }

    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    public function setSubjectId(int $subject_id):  Schedule
    {
        $this->subject_id = $subject_id;
    }

    public function getLecturerId(): ?int
    {
        return $this->lecturer_id;
    }

    public function setLecturerId(int $lecturer_id):  Schedule
    {
        $this->lecturer_id = $lecturer_id;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id):  Schedule
    {
        $this->faculty_id = $faculty_id;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id):  Schedule
    {
        $this->group_id = $group_id;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id):  Schedule
    {
        $this->room_id = $room_id;
    }

    public function getTimeStart(): ?string
    {
        return $this->time_start;
    }

    public function setTimeStart(string $time_start):  Schedule
    {
        $this->time_start = $time_start;
    }

    public function getTimeEnd(): ?string
    {
        return $this->time_end;
    }

    public function setTimeEnd(string $time_end):  Schedule
    {
        $this->time_end = $time_end;
    }



    public static function fromArray($array):  Schedule
    {
        $schedule = new self();
        $schedule->fill($array);

        return $schedule;
    }

    public function fill($array):  Schedule
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['subject'])) {
            $this->setSubject($array['subject']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM schedule';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (!$this->getId()) {
            $sql = "INSERT INTO schedule (subject, content) VALUES (:subject, :content)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':subject' => $this->getSubject(),
                ':content' => $this->getContent(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE schedule SET subject = :subject, content = :content WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':subject' => $this->getSubject(),
                ':content' => $this->getContent(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM schedule WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setSubject(null);
        $this->setContent(null);
    }
}
