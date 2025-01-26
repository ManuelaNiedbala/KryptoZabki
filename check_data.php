<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Service\Config;

try {
    $pdo = new PDO(
        'sqlite:d:/KryptoŻabki/KryptoZabki/data.db',
        '',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

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
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}