<?php

namespace App\API;

use App\Model\Lecturer;
use PDO;
use PDOException;
use Exception;

class ScrapeAPI
{
    private PDO $pdo;

    public function __construct()
    {
        global $config;
        if (!isset($config)) {
            require __DIR__ . '/../../config/config.php';
        }

        try {
            $this->pdo = new PDO(
                $config['db_dsn'],
                $config['db_user'],
                $config['db_pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            throw new Exception("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function scrapeSchedule()
    {
        $url = "https://plan.zut.edu.pl/schedule.php?kind=teacher&query=Karczmar";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!$data) {
            throw new Exception("Błąd pobierania danych z API");
        }

        foreach ($data as $item) {
            if (is_array($item) && isset($item['item'])) {
                $lecturerName = $item['item'];
                $lecturer = new Lecturer();
                $lecturer->setLecturerName($lecturerName);
                $lecturer->setTitle(''); // Assuming title is not provided in the data
                $lecturer->save();
            }
        }
    }
}