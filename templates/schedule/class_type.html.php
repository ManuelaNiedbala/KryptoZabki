<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

ob_start(); ?>
<div class="dropdown" role="group" aria-labelledby="forma_zajęć">
    <button class="dropdown-toggle" type="button" id="forma_zajęć" aria-expanded="false">
        Wybierz formę zajęć
    </button>
    <div class="dropdown-menu" aria-hidden="true">
        <label>
            <input type="checkbox" id="select-all" aria-label="Zaznacz wszystkie"/> Zaznacz wszystkie
        </label>
        <label>
            <input type="checkbox" name="forma" value="Laboratorium" /> Laboratorium
        </label>
        <label>
            <input type="checkbox" name="forma" value="Audytorium" /> Audytorium
        </label>
        <label>
            <input type="checkbox" name="forma" value="Lektorat" /> Lektorat
        </label>
        <label>
            <input type="checkbox" name="forma" value="Wykład" /> Wykład
        </label>
        <label>
            <input type="checkbox" name="forma" value="Projekt" /> Projekt
        </label>
        <label>
            <input type="checkbox" name="forma" value="Egzamin" /> Egzamin
        </label>
        <label>
            <input type="checkbox" name="forma" value="Seminarium" /> Seminarium
        </label>
        <label>
            <input type="checkbox" name="forma" value="Konsultacje" /> Konsultacje
        </label>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zaznaczania wszystkich checkboxów
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="forma"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        const individualCheckboxes = document.querySelectorAll('input[name="forma"]');
        individualCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(individualCheckboxes).every(chk => chk.checked);
                document.getElementById('select-all').checked = allChecked;
            });
        });
    });
</script>