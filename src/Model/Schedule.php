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
    private ?string $color = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Schedule
    {
        $this->id = $id;
        return $this;
    }

    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    public function setSubjectId(int $subject_id): Schedule
    {
        $this->subject_id = $subject_id;
        return $this;
    }

    public function getLecturerId(): ?int
    {
        return $this->lecturer_id;
    }

    public function setLecturerId(int $lecturer_id): Schedule
    {
        $this->lecturer_id = $lecturer_id;
        return $this;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Schedule
    {
        $this->faculty_id = $faculty_id;
        return $this;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id): Schedule
    {
        $this->group_id = $group_id;
        return $this;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): Schedule
    {
        $this->room_id = $room_id;
        return $this;
    }

    public function getTimeStart(): ?string
    {
        return $this->time_start;
    }

    public function setTimeStart(string $time_start): Schedule
    {
        $this->time_start = $time_start;
        return $this;
    }

    public function getTimeEnd(): ?string
    {
        return $this->time_end;
    }

    public function setTimeEnd(string $time_end): Schedule
    {
        $this->time_end = $time_end;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): Schedule
    {
        $this->color = $color;
        return $this;
    }

    public static function fromArray($array): Schedule
    {
        $schedule = new self();
        $schedule->fill($array);

        return $schedule;
    }

    public function fill($array): Schedule
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['subject_id'])) {
            $this->setSubjectId($array['subject_id']);
        }
        if(isset($array['lecturer_id'])) {
            $this->setLecturerId($array['lecturer_id']);
        }
        if(isset($array['faculty_id'])) {
            $this->setFacultyId($array['faculty_id']);
        }
        if(isset($array['group_id'])) {
            $this->setGroupId($array['group_id']);
        }
        if(isset($array['room_id'])) {
            $this->setRoomId($array['room_id']);
        }
        if(isset($array['time_start'])) {
            $this->setTimeStart($array['time_start']);
        }
        if(isset($array['time_end'])) {
            $this->setTimeEnd($array['time_end']);
        }
        if(isset($array['color'])) {
            $this->setColor($array['color']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM schedules';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $schedules = [];
        $schedulesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($schedulesArray as $scheduleArray) {
            $schedules[] = self::fromArray($scheduleArray);
        }

        return $schedules;
    }

    public static function findSchedule(int $id): ?Schedule
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM schedules WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $scheduleArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$scheduleArray) {
            return null;
        }
        $schedule = self::fromArray($scheduleArray);

        return $schedule;
    }

    public static function findSubjectId($subjectName, $subjectForm): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT id FROM subject WHERE subject_name = :subject_name AND subject_form = :subject_form';
        $statement = $pdo->prepare($sql);
        $statement->execute(['subject_name' => $subjectName, 'subject_form' => $subjectForm]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int)$data['id'] : null;
    }

    public static function findLecturerId($lecturerName): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT id FROM lecturer WHERE lecturer_name = :lecturer_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['lecturer_name' => $lecturerName]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int)$data['id'] : null;
    }

    public static function findFacultyId($facultyName): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT id FROM faculty WHERE faculty_name = :faculty_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['faculty_name' => $facultyName]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int)$data['id'] : null;
    }

    public static function findRoomId($roomName, $facultyId): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT id FROM room WHERE room_name = :room_name AND faculty_id = :faculty_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['room_name' => $roomName, 'faculty_id' => $facultyId]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int)$data['id'] : null;
    }

    public static function findGroupId($groupName): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT id FROM groups WHERE group_name = :group_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['group_name' => $groupName]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int)$data['id'] : null;
    }

    public function save(): Schedule
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
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
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId(),
                'subject_id' => $this->getSubjectId(),
                'lecturer_id' => $this->getLecturerId(),
                'faculty_id' => $this->getFacultyId(),
                'group_id' => $this->getGroupId(),
                'room_id' => $this->getRoomId(),
                'time_start' => $this->getTimeStart(),
                'time_end' => $this->getTimeEnd(),
                'color' => $this->getColor()
            ]);
        }
        return $this;
    }
}