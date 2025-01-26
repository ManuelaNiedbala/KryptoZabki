<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\API\ScrapeAPI;

$scrapeAPI = new ScrapeAPI();
$scrapeAPI->scrapeSchedule();