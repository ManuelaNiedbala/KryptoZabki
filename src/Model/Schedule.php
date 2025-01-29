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
    }

    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    public function setSubjectId(int $subject_id): Schedule
    {
        $this->subject_id = $subject_id;
    }

    public function getLecturerId(): ?int
    {
        return $this->lecturer_id;
    }

    public function setLecturerId(int $lecturer_id): Schedule
    {
        $this->lecturer_id = $lecturer_id;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Schedule
    {
        $this->faculty_id = $faculty_id;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id): Schedule
    {
        $this->group_id = $group_id;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): Schedule
    {
        $this->room_id = $room_id;
    }

    public function getTimeStart(): ?string
    {
        return $this->time_start;
    }

    public function setTimeStart(string $time_start): Schedule
    {
        $this->time_start = $time_start;
    }

    public function getTimeEnd(): ?string
    {
        return $this->time_end;
    }

    public function setTimeEnd(string $time_end): Schedule
    {
        $this->time_end = $time_end;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): Schedule
    {
        $this->color = $color;
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
    }
}