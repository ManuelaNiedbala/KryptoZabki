<?php

namespace App\API;

use App\Model\Lecturer;
use App\Service\Config;
use PDO;
use PDOException;
use Exception;

class ScrapeAPI
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                Config::get('db_dsn'),
                Config::get('db_user'),
                Config::get('db_pass'),
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
                $lecturer->setTitle('');
                $lecturer->save();
            }
        }
    }
}