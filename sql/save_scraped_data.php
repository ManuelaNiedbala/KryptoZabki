<?php
// Dołącz plik z połączeniem z bazą danych
include 'db.php';

// Zakładając, że dane do zapisania zostały pobrane z scraper (np. tablica z danymi)
$data = [
    'faculty' => [
        ['faculty_name' => 'Computer Science'],
        ['faculty_name' => 'Mathematics'],
    ],
    'lecturer' => [
        ['lecturer_name' => 'John Doe', 'title' => 'Dr.'],
        ['lecturer_name' => 'Jane Smith', 'title' => 'Prof.'],
    ],
    'subject' => [
        ['subject_name' => 'Data Structures', 'subject_form' => 'Lecture'],
        ['subject_name' => 'Algorithms', 'subject_form' => 'Seminar'],
    ],
    'room' => [
        ['room_name' => 'Room 101', 'faculty_id' => 1],
        ['room_name' => 'Room 102', 'faculty_id' => 2],
    ],
    'groups' => [
        ['group_name' => 'Group 1'],
        ['group_name' => 'Group 2'],
    ]
];

// Funkcja zapisująca dane do bazy
function saveData($pdo, $table, $data) {
    $columns = implode(', ', array_keys($data[0]));
    $placeholders = ':' . implode(', :', array_keys($data[0]));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);

    foreach ($data as $row) {
        $stmt->execute($row);
    }
}

// Zapisz dane do tabel
foreach ($data as $table => $rows) {
    saveData($pdo, $table, $rows);
}

echo "Data has been successfully saved!";
?>
