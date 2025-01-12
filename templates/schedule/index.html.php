<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

$title = 'Schedule List';
$bodyClass = 'index';

ob_start(); ?>
<body <?= isset($bodyClass) ? "class='$bodyClass'" : '' ?>>
<navbar>
    <div id="accesibility_settings">
        <details>
            <summary>eye</summary>
            <div id="eye-options" class="dropdown-menu">
                <label><input type="radio" name="eye-option" value="eye1"> eye1</label>
                <label><input type="radio" name="eye-option" value="eye2"> eye2</label>
                <label><input type="radio" name="eye-option" value="eye3"> eye3</label>
            </div>
        </details>
        <button>A</button>
        <button>A+</button>
        <button>A++</button>
    </div>
</navbar>
<header>
    <div id="logo">
        <img src="../../public/assets/images/logo-zut.svg" alt="Logo ZUT">
    </div>
    <h1>Plan ZUT 2025</h1>
</header>
<main_content>
    <aside>
        <form>
            <label for="lecturer">Wykładowca</label>
            <input type="text" id="lecturer" name="lecturer" placeholder="Podaj nazwisko">

            <label for="studentID">Numer albumu</label>
            <input type="text" id="studentID" name="studentID" placeholder="Podaj numer albumu">

            <label for="subject">Przedmiot</label>
            <input type="text" id="subject" name="subject" placeholder="Podaj przedmiot">

            <label for="group">Grupa</label>
            <input type="text" id="group" name="group" placeholder="Podaj grupę">

            <label for="wydzial">Wydział</label>
            <input type="text" id="wydzial" name="wydzial" placeholder="Podaj wydział">

            <label for="room">Sala</label>
            <input type="text" id="room" name="room" placeholder="Podaj salę">

            <label for="forma_zajec">Forma zajęć</label>
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
            <div class="buttons">
                <button type="submit" class="search-button" aria-label="Szukaj">Szukaj</button>
            </div>
            <div class="buttons">
                <button type="reset" class="reset-button" aria-label="Wyczyść filtry">Wyczyść filtry</button>
            </div>
        </form>
    </aside>
    <section id="schedule">
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
        <script>

            document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
            });

        </script>
        <div id="calendar"></div>
    </section>
</main_content>
<legend>
    <h2>Legenda</h2>
    <ul>
        <li><span class="event-1"></span> Wydarzenie 1</li>
        <li><span class="event-2"></span> Wydarzenie 2</li>
    </ul>
</legend>
<footer>
    <p>KryptoŻabki 2025 </p>
</footer>
</body>
</html>


<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';