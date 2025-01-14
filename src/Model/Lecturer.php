<?php
namespace App\Model;
use App\Service\Config;

class lecturer
{
    private ?int $id = null;
    private ?string $lecturerName = null;
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLecturerName(): ?string
    {
        return $this->lecturerName;
    }

    public function setLecturerName(?string $lecturerName): void
    {
        $this->lecturerName = $lecturerName;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    

}
