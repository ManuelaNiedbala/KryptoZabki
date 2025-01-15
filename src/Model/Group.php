<?php
namespace App\Model;

use App\Service\Config;

class Group
{
    private ?int $id = null;
    private ?string $group_name = null;

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

    public static function fromArray($array): Group
    {
        $group = new self();
        $group->fill($array);

        return $group;
    }

    public function fill($array): Group
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['group_name'])) {
            $this->setGroupName($array['group_name']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM group';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $groups = [];
        $groupsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($groupsArray as $groupArray) {
            $groups[] = self::fromArray($groupArray);
        }

        return $groups;
    }

    public static function findGroup(string $group_name): Group
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM group WHERE group_name = :group_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['group_name' => $group_name]);

        $groupArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $groupArray) {
            return null;
        }
        $group = self::fromArray($groupArray);

        return $group;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = 'INSERT INTO group (group_name) VALUES (:group_name)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'group_name' => $this->getGroupName()
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = 'UPDATE group SET group_name = :group_name WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'group_name' => $this->getGroupName(), 
                'id' => $this->getId()
            ]);
        }
    }
}