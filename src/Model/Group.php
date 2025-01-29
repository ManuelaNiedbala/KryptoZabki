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
        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): Group
    {
        $this->group_name = $group_name;
        return $this;
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

    public static function findGroup(string $group_name): ?Group
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM groups WHERE group_name = :group_name';
        $statement = $pdo->prepare($sql);
        $statement->execute(['group_name' => $group_name]);

        $search = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$search) {
            return null;
        }
        $group = self::fromArray($search);

        return $group;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (!$this->getId()) {
            $sql = 'INSERT INTO groups (group_name) VALUES (:group_name)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'group_name' => $this->getGroupName()
            ]);

            $this->setId((int)$pdo->lastInsertId());
        } else {
            $sql = 'UPDATE groups SET group_name = :group_name WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'id' => $this->getId(),
                'group_name' => $this->getGroupName()
            ]);
        }
    }
}