<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Service\Config;

try {
    $pdo = new PDO(
        Config::get('db_dsn'),
        Config::get('db_user'),
        Config::get('db_pass'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Check data in lecturer table
    $statement = $pdo->query('SELECT * FROM lecturer');
    $lecturers = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($lecturers) {
        echo "Dane w tabeli 'lecturer':\n";
        foreach ($lecturers as $lecturer) {
            echo "ID: {$lecturer['id']}, Name: {$lecturer['lecturer_name']}, Title: {$lecturer['title']}\n";
        }
    } else {
        echo "Tabela 'lecturer' jest pusta.\n";
    }

    // Check data in faculty table
    $statement = $pdo->query('SELECT * FROM faculty');
    $faculties = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($faculties) {
        echo "Dane w tabeli 'faculty':\n";
        foreach ($faculties as $faculty) {
            echo "ID: {$faculty['id']}, Name: {$faculty['faculty_name']}\n";
        }
    } else {
        echo "Tabela 'faculty' jest pusta.\n";
    }

    // Check data in subject table
    $statement = $pdo->query('SELECT * FROM subject');
    $subjects = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($subjects) {
        echo "Dane w tabeli 'subject':\n";
        foreach ($subjects as $subject) {
            echo "ID: {$subject['id']}, Name: {$subject['subject_name']}, Form: {$subject['subject_form']}\n";
        }
    } else {
        echo "Tabela 'subject' jest pusta.\n";
    }

    //Check data in group table
    $statement = $pdo->query('SELECT * FROM groups');
    $groups = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($groups) {
        echo "Dane w tabeli 'groups':\n";
        foreach ($groups as $group) {
            echo "ID: {$group['id']}, Name: {$group['group_name']}\n";
        }
    } else {
        echo "Tabela 'groups' jest pusta.\n";
    }

} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}