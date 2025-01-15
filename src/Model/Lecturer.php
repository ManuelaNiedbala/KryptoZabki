<?php
namespace App\Model;

use App\Service\Config;

class Lecturer
{
    private ?int $id = null;
    private ?string $lecturer_name = null;
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Lecturer
    {
        $this->id = $id;
    }

    public function getLecturerName(): ?string
    {
        return $this->lecturer_name;
    }

    public function setLecturerName(string $lecturer_name): Lecturer
    {
        $this->lecturer_name = $lecturer_name;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Lecturer
    {
        $this->title = $title;
    }
}
