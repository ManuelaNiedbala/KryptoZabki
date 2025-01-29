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
        return $this;
    }

    public function getLecturerName(): ?string
    {
        return $this->lecturer_name;
    }

    public function setLecturerName(string $lecturer_name): Lecturer
    {
        $this->lecturer_name = $lecturer_name;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Lecturer
    {
        $this->title = $title;
        return $this;
    }

    public static function fromArray($array): Lecturer
    {
        $lecturer = new self();
        $lecturer->fill($array);

        return $lecturer;
    }

    public function fill($array): Lecturer
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['lecturer_name'])) {
            $this->setLecturerName($array['lecturer_name']);
        }
        if(isset($array['title'])) {
            $this->setTitle($array['title']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM lecturer';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $lecturers = [];
        $lecturersArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($lecturersArray as $lecturerArray) {
            $lecturers[] = self::fromArray($lecturerArray);
        }

        return $lecturers;
    }

    public static function findLecturer(string $lecturer_name, string $title): ?Lecturer
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM lecturer WHERE lecturer_name = :lecturer_name AND title = :title';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'lecturer_name' => $lecturer_name,
            'title' => $title
        ]);

        $lecturerArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $lecturerArray) {
            return null;
        }
        $lecturer = self::fromArray($lecturerArray);

        return $lecturer;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getID()) {
            $sql = 'INSERT INTO lecturer (lecturer_name, title) VALUES (:lecturer_name, :title)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'lecturer_name' => $this->getLecturerName(),
                'title' => $this->getTitle()
            ]);
            $this->setId($pdo->lastInsertId());
            echo "Dodano wykładowcę: {$this->getLecturerName()}\n";
        } else {
            $sql = 'UPDATE lecturer SET lecturer_name = :lecturer_name, title = :title WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'lecturer_name' => $this->getLecturerName(),
                'title' => $this->getTitle(),
                'id' => $this->getId()
            ]);
            echo "Zaktualizowano wykładowcę: {$this->getLecturerName()}\n";
        }
    }
}