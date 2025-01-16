<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

ob_start(); ?>
<div class="dropdown management-dropdown" role="group" aria-labelledby="zarzadzanie">
    <button class="dropdown-toggle" type="button" id="zarzadzanie" aria-expanded="false">
        Zarządzanie
    </button>
    <div class="dropdown-menu management-menu" aria-hidden="true">
        <button type="button" class="dropdown-item" onclick="save()">Zapisz</button>
        <button type="button" class="dropdown-item" onclick="share()">Udostępnij</button>
        <button type="button" class="dropdown-item" onclick="print()">Drukuj</button>
    </div>
</div>

<script>
    function save() {
        // Implementacja funkcji zapisu
        alert('Zapisano');
    }

    function share() {
        // Implementacja funkcji udostępniania
        alert('Udostępniono');
    }

    function print() {
        // Implementacja funkcji drukowania
        window.print();
    }
</script>