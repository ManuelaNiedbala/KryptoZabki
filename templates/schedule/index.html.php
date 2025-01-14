<?php

/** @var \App\Model\Schedule[] $schedules */
/** @var \App\Service\Router $router */

$title = 'Schedule List';
$bodyClass = 'index';

ob_start(); ?>
<body <?= isset($bodyClass) ? "class='$bodyClass'" : '' ?>>
<navbar>
    <?php include __DIR__ . '/accessibility.html.php'; ?>
</navbar>
<header>
    <div id="logo">
        <img src="/public/assets/images/logo-zut.svg" alt="Logo ZUT">
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
            <input type="text" id="faculty" name="faculty" placeholder="Podaj wydział">

            <label for="room">Sala</label>
            <input type="text" id="room" name="room" placeholder="Podaj salę">

            <label for="class_type">Forma zajęć</label>
            <?php include __DIR__ . '/class_type.html.php'; ?>
            <div class="buttons">
                <button type="submit" class="search-button" aria-label="Szukaj">Szukaj</button>
            </div>
            <div class="buttons">
                <button type="reset" class="reset-button" aria-label="Wyczyść filtry">Wyczyść filtry</button>
            </div>
            <?php include __DIR__ . '/management.html.php'; ?>
        </form>
    </aside>
    <section id="schedule">
         <?php include __DIR__ . '/calendar.html.php'; ?>
    </section>
</main_content>
<!--<legend>
    <h2>Legenda</h2>
    <ul>
        <li><span class="event-1"></span> Wydarzenie 1</li>
        <li><span class="event-2"></span> Wydarzenie 2</li>
    </ul>
</legend>
-->
</body>
</html>


<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';