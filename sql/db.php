<?php
$host = 'localhost';
$dbname = 'pilk_schedule';
$username = 'root'; // Upewnij się, że masz odpowiednie dane do logowania
$password = ''; // Wprowadź hasło, jeśli jest wymagane

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
