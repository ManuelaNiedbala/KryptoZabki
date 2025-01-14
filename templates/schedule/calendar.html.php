<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

ob_start(); ?>
<div id="calendar"></div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth' // Zmiana kolejności przycisków
            },
            locale: 'pl', // Ustawienie języka na polski
            firstDay: 1, // Ustawienie pierwszego dnia tygodnia na poniedziałek
            buttonText: {
                today: 'Dziś',
                month: 'Miesiąc',
                week: 'Tydzień',
                day: 'Dzień'
            },
            allDaySlot: false, // Usunięcie wiersza "all day"
            slotMinTime: '07:00:00', // Ustawienie minimalnej godziny
            slotMaxTime: '21:00:00'  // Ustawienie maksymalnej godziny
        });
        calendar.render();
    });
</script>