<?php
namespace App\Model;

use App\Service\Config;

class Faculty
{
    private ?int $id = null;
    private ?string $faculty_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Faculty
    {
        $this->id = $id;
    }

    public function getFacultyName(): ?string
    {
        return $this->faculty_name;
    }

    public function setFacultyName(string $faculty_name): Faculty
    {
        $this->faculty_name = $faculty_name;
    }




}