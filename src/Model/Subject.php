<?php
namespace App\Model;

use App\Service\Config;

class Subject
{
    private ?int $id = null;
    private ?string $subject_name = null;
    private ?string $subject_form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Subject
    {
        $this->id = $id;
        return $this;
    }

    public function getSubjectName(): ?string
    {
        return $this->subject_name;
    }

    public function setSubjectName(string $subject_name): Subject
    {
        $this->subject_name = $subject_name;
        return $this;
    }

    public function getSubjectForm(): ?string
    {
        return $this->subject_form;
    }

    public function setSubjectForm(string $subject_form): Subject
    {
        $this->subject_form = $subject_form;
        return $this;
    }

    public static function fromArray($array): Subject
    {
        $subject = new self();
        $subject->fill($array);

        return $subject;
    }

    public function fill($array): Subject
    {
        if(isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if(isset($array['subject_name'])) {
            $this->setSubjectName($array['subject_name']);
        }
        if(isset($array['subject_form'])) {
            $this->setSubjectForm($array['subject_form']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM subjects';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $subjects = [];
        $subjectsArray = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($subjectsArray as $subjectArray) {
            $subjects[] = self::fromArray($subjectArray);
        }

        return $subjects;
    }

    public static function findSubject($subjectName, $subjectForm): ?Subject
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM subject WHERE subject_name = :subject_name AND subject_form = :subject_form';
        $statement = $pdo->prepare($sql);
        $statement->execute(['subject_name' => $subjectName, 'subject_form' => $subjectForm]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::fromArray($data);
        }
        return null;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if ($this->getId()) {
            $sql = 'UPDATE subject SET subject_name = :subject_name, subject_form = :subject_form WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'subject_name' => $this->getSubjectName(),
                'subject_form' => $this->getSubjectForm(),
                'id' => $this->getId()
            ]);
        } else {
            $sql = 'INSERT INTO subject (subject_name, subject_form) VALUES (:subject_name, :subject_form)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'subject_name' => $this->getSubjectName(),
                'subject_form' => $this->getSubjectForm()
            ]);
            $this->setId($pdo->lastInsertId());
        }
    }
}