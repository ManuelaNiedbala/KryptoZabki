<?php
include 'db.php';

// Konfiguracja API
$apiUrl = "https://plan.zut.edu.pl/schedule_student.php?room=WI+WI1-+215&start=2024-09-30T00%3A00%3A00%2B02%3A00&end=2024-10-07T00%3A00%3A00%2B02%3A00&fbclid=IwY2xjawH1FYZleHRuA2FlbQIxMAABHT2dH8h76Rta5vXSwqHgIjWKlVC-uCLEvB4mwvk1wfTy-SiiJuU2Mz8gxQ_aem_YcJ0rvJN60U8fT9sNpx3Pg"; // Zamień na rzeczywisty URL API

// Pobierz dane z API
function fetchFromApi($apiUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode === 200) {
        return json_decode($response, true); // Zwróć wynik jako tablica
    } else {
        die("Error fetching data from API. HTTP Code: $httpCode");
    }
}

// Pobierz dane
$data = fetchFromApi($apiUrl);

// Funkcja do zapisu danych do bazy
function saveData($pdo, $table, $data) {
    $columns = implode(', ', array_keys($data[0]));
    $placeholders = ':' . implode(', :', array_keys($data[0]));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);

    foreach ($data as $row) {
        $stmt->execute($row);
    }
}

// Przykład: Zapis danych do tabel
if (isset($data['faculties'])) {
    saveData($pdo, 'faculty', $data['faculties']);
}
if (isset($data['lecturers'])) {
    saveData($pdo, 'lecturer', $data['lecturers']);
}
if (isset($data['subjects'])) {
    saveData($pdo, 'subject', $data['subjects']);
}

echo "Data from API has been successfully saved!";
?>
