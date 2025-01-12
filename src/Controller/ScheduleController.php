<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Schedule;
use App\Service\Router;
use App\Service\Templating;

class ScheduleController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $schedules = Schedule::findAll();
        $html = $templating->render('schedule/index.html.php', [
            'schedules' => $schedules,
            'router' => $router,
        ]);
        return $html;
    }
}
