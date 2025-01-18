<?php

// Konfiguracja połączenia z bazą danych
$dsn = 'sqlite:database.db'; // Nazwa pliku bazy danych SQLite
try {
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Funkcja zapisywania danych do bazy danych
function saveData($db, $tableName, $data) {
    try {
        // Tworzenie dynamicznego zapytania INSERT
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);

        // Przypisanie wartości do zapytania
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Wykonanie zapytania
        $stmt->execute();
        echo "Data inserted into $tableName successfully.\n";
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage() . "\n";
    }
}

// Przykładowe dane do zapisania
$facultyData = [
    'faculty_name' => 'Engineering'
];
$roomData = [
    'room_name' => '101',
    'faculty_id' => 1
];

// Zapisywanie danych do tabel
saveData($db, 'faculty', $facultyData);
saveData($db, 'room', $roomData);

// Zamknij połączenie z bazą danych
$db = null;
