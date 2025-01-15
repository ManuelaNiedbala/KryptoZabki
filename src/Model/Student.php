<?php
namespace App\Model;

use App\Service\Config;

class Student
{
    private ?int $id = null;
    private ?int $group_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Student
    {
        $this->id = $id;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function setGroupId(int $group_id): Student
    {
        $this->group_id = $group_id;
    }

    
}