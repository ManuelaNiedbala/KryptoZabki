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

    $tables = ['lecturer', 'faculty', 'subject', 'groups', 'group_student', 'student', 'room', 'schedules'];

    foreach ($tables as $table) {
        $statement = $pdo->query("SELECT * FROM $table LIMIT 10");
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            echo "Dane w tabeli '$table':\n";
            foreach ($rows as $row) {
                echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "Tabela '$table' jest pusta.\n";
        }
    }

} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}