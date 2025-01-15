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
    }

    public function getRoomName(): ?string
    {
        return $this->room_name;
    }

    public function setRoomName(string $room_name): Room
    {
        $this->room_name = $room_name;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Room
    {
        $this->faculty_id = $faculty_id;
    }
}