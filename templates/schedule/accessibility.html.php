<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

ob_start(); ?>
<div id="accessibility-settings">
    <button id="accessibility-button">☰</button>
    <div id="accessibility-dropdown">
        <details>
            <summary>👁</summary>
            <div class="dropdown-menu">
                <button type="button" onclick="setEyeOption('eye1')">Eye 1</button>
                <button type="button" onclick="setEyeOption('eye2')">Eye 2</button>
                <button type="button" onclick="setEyeOption('eye3')">Eye 3</button>
            </div>
        </details>
        <button>A</button>
        <button>A+</button>
        <button>A++</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('accessibility-button');
        const dropdown = document.getElementById('accessibility-dropdown');

        // Początkowo menu jest ukryte
        dropdown.style.display = 'none';

        button.addEventListener('click', function () {
            // Przełączanie widoczności menu
            if (dropdown.style.display === 'none') {
                dropdown.style.display = 'flex'; // Wyświetlenie menu w poziomie
            } else {
                dropdown.style.display = 'none'; // Ukrycie menu
            }
        });
    });

    function setEyeOption(option) {
        alert(`Wybrano opcję: ${option}`);
    }
</script>



