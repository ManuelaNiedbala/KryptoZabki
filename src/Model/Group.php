<?php
namespace App\Model;

use App\Service\Config;

class Group
{
    private ?int $id = null;
    private ?string $group_name = null;
    private ?int $faculty_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Group
    {
        $this->id = $id;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): Group
    {
        $this->group_name = $group_name;
    }

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): Group
    {
        $this->faculty_id = $faculty_id;
    }
}