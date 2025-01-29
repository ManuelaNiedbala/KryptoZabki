<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

ob_start(); ?>
<div id="accessibility-settings">
    <button id="accessibility-button">☰</button>
    <div id="accessibility-dropdown">
        <details>
            <summary><i class="fas fa-eye"></i></summary>
            <div class="dropdown-menu">
                <button type="button" onclick="setEyeOption('reset')"><i class="fas fa-eye"></i></button>
                <button type="button" onclick="setEyeOption('contrast')"><i class="fas fa-eye"></i></button>
                <button type="button" onclick="setEyeOption('high_contrast')"><i class="fas fa-eye"></i></button>
            </div>
        </details>
        <button id="reset-font-size">A</button>
        <button id="increase-font-size">A+</button>
        <button>A++</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('accessibility-button');
        const dropdown = document.getElementById('accessibility-dropdown');
        const increaseFontSizeButton = document.getElementById('increase-font-size');
        const increaseFontSizeButtonPlusPlus = document.querySelector('#accessibility-dropdown button:nth-child(4)');
        const resetFontSizeButton = document.getElementById('reset-font-size');
        let currentFontSize = 16; // Domyślna wielkość czcionki

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

        increaseFontSizeButton.addEventListener('click', function () {
            currentFontSize = 16 + 2; // Ustawienie wielkości czcionki na 18px
            document.body.style.fontSize = currentFontSize + 'px';
            document.querySelectorAll('.legend, aside, .dropdown-menu, .fc-button, .legend li, aside label, aside input, .dropdown-toggle, .search-button, .reset-button, .management-button').forEach(function (el) {
                el.style.fontSize = currentFontSize + 'px';
            });
        });

        increaseFontSizeButtonPlusPlus.addEventListener('click', function () {
            currentFontSize = 16 + 4; // Ustawienie wielkości czcionki na 20px
            document.body.style.fontSize = currentFontSize + 'px';
            document.querySelectorAll('.legend, aside, .dropdown-menu, .fc-button, .legend li, aside label, aside input, .dropdown-toggle, .search-button, .reset-button, .management-button').forEach(function (el) {
                el.style.fontSize = currentFontSize + 'px';
            });
        });

        resetFontSizeButton.addEventListener('click', function () {
            currentFontSize = 16; // Przywrócenie domyślnej wielkości czcionki
            document.body.style.fontSize = currentFontSize + 'px';
            document.querySelectorAll('.legend, aside, .dropdown-menu, .fc-button, .legend li, aside label, aside input, .dropdown-toggle, .search-button, .reset-button, .management-button').forEach(function (el) {
                el.style.fontSize = currentFontSize + 'px';
            });
        });
    });

    function setEyeOption(option) {
        document.documentElement.classList.remove('contrast', 'high-contrast');

        if (option === 'contrast') {
            document.documentElement.classList.add('contrast');
            localStorage.setItem('theme', 'contrast');
            applyTheme('contrast');
        } else if (option === 'high_contrast') {
            document.documentElement.classList.add('high-contrast');
            localStorage.setItem('theme', 'high-contrast');
            applyTheme('high-contrast');
        } else if (option === 'reset') {
            document.documentElement.classList.remove('contrast', 'high-contrast');
            localStorage.removeItem('theme');
            applyTheme('default');
        }

        console.log("Примененные классы:", document.documentElement.classList);
    }

</script>