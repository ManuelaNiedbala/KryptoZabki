<?php

// URL API
$api_url = "https://plan.zut.edu.pl/schedule_student.php?room=WI+WI1-+215&start=2024-09-30T00%3A00%3A00%2B02%3A00&end=2024-10-07T00%3A00%3A00%2B02%3A00&fbclid=IwY2xjawH1FYZleHRuA2FlbQIxMAABHT2dH8h76Rta5vXSwqHgIjWKlVC-uCLEvB4mwvk1wfTy-SiiJuU2Mz8gxQ_aem_YcJ0rvJN60U8fT9sNpx3Pg";

// Funkcja pobierająca dane z API
function fetch_schedule($url) {
    try {
        // Wykonanie zapytania GET
        $response = file_get_contents($url);

        // Sprawdzenie, czy zapytanie się powiodło
        if ($response === FALSE) {
            throw new Exception("Error fetching data");
        }

        // Konwersja odpowiedzi do tablicy PHP
        $data = json_decode($response, true);
        return $data;
    } catch (Exception $e) {
        echo "Error fetching data: " . $e->getMessage() . "\n";
        return null;
    }
}

// Funkcja wyświetlająca dane
function display_schedule($data) {
    if (!$data) {
        echo "No data to display.\n";
        return;
    }

    echo "Schedule data:\n";
    foreach ($data as $entry) {
        // Upewniamy się, że entry to tablica
        if (is_array($entry)) {
            $start = $entry['start'] ?? 'No start time';
            $end = $entry['end'] ?? 'No end time';
            $title = $entry['title'] ?? 'No title';
            echo "Start: $start, End: $end, Title: $title\n";
        } else {
            echo "Unexpected data format:\n";
            print_r($entry);
        }
    }
}

// Główna część programu
$schedule_data = fetch_schedule($api_url);
display_schedule($schedule_data);
