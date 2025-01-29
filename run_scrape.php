<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\API\ScrapeAPI;

$scrapeAPI = new ScrapeAPI();
$startDate = '2025-01-28T00:00:00+02:00';
$endDate = '2025-01-29T00:00:00+02:00';
$scrapeAPI->scrapeSchedule($startDate, $endDate);
$scrapeAPI->fetchAndSaveStudent(53878);