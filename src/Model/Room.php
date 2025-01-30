<?php
namespace App\Model;

use App\Service\Config;

class Room
{
    private ?int $id = null;
    private ?string $room_name = null;
    private ?int $faculty_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Room
    {
        $this->id = $id;
        return $this;
    }

    public function getRoomName(): ?string
    {
        return $this->room_name;
    }

    public function setRoomName(string $room_name): Room
    {
        $this->room_name = $room_name;
        return $this;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Room
    {
        $this->faculty_id = $faculty_id;
        return $this;
    }

    public static function fromArray($array): Room
    {
        $room = new self();
        $room->fill($array);

        return $room;
    }

    public function fill($array): Room
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['room_name'])) {
            $this->setRoomName($array['room_name']);
        }
        if(isset($array['faculty_id'])) {
            $this->setFacultyId($array['faculty_id']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM room';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $rooms = [];
        $roomsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($roomsArray as $roomArray) {
            $rooms[] = self::fromArray($roomArray);
        }

        return $rooms;
    }

    public static function findRoom(string $room_name, int $faculty_id): ?Room
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM room WHERE room_name = :room_name AND faculty_id = :faculty_id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['room_name' => $room_name, 'faculty_id' => $faculty_id]);

        $roomArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $roomArray) {
            return null;
        }
        $room = self::fromArray($roomArray);

        return $room;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getID()) {
            $sql = 'INSERT INTO room (room_name, faculty_id) VALUES (:room_name, :faculty_id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'room_name' => $this->getRoomName(),
                'faculty_id' => $this->getFacultyId()
            ]);
            
            $this->setId($pdo->lastInsertId());
        } else {
            $sql = 'UPDATE room SET room_name = :room_name, faculty_id = :faculty_id WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId(),
                'room_name' => $this->getRoomName(),
                'faculty_id' => $this->getFacultyId()
            ]);
        }
    }
}