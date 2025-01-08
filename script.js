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

// Obsługa rozwijanego menu
const dropdownButton = document.querySelector('.dropdown-toggle');
const dropdownMenu = document.querySelector('.dropdown-menu');

dropdownButton.addEventListener('click', () => {
    const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
    dropdownButton.setAttribute('aria-expanded', !isExpanded);
    dropdownMenu.setAttribute('aria-hidden', isExpanded);
});

// Formatowanie daty i ustawianie aktualnego dnia
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('pl-PL', options);
}

function getDayName(date) {
    const options = { weekday: 'long' };
    return date.toLocaleDateString('pl-PL', options);
}

document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.querySelector('#calendar');
    let currentDate = new Date();

    // Funkcja czyszcząca kalendarz
    function clearCalendar() {
        const eventSlots = document.querySelectorAll('.event-slot');
        eventSlots.forEach(slot => (slot.innerHTML = ""));
    }

    // Funkcja dodająca wydarzenia do kalendarza
    function addEvent(startTime, endTime, description, type) {
        const startHour = Math.floor(startTime);
        const slot = document.querySelector(`.event-slot[data-start-hour='${startHour}']`);
        if (slot) {
            const eventBlock = document.createElement('div');
            eventBlock.classList.add('event');
            eventBlock.textContent = description;

            eventBlock.style.top = `${(startTime % 1) * 100}%`;
            eventBlock.style.height = `${(endTime - startTime) * 100}%`;
            eventBlock.style.backgroundColor = getEventColor(type);

            slot.appendChild(eventBlock);
        }
    }

    // Funkcja do ustalania kolorów zajęć
    function getEventColor(type) {
        const colors = {
            "Laboratorium": "#1EFF00",
            "Audytorium": "#FFFF00",
            "Lektorat": "#FF8000",
            "Wykład": "#0015FF",
            "Projekt": "#FF00E5",
            "Egzamin": "#FF0000",
            "Seminarium": "#640BFF",
            "Konsultacje": "#00C8FF"
        };
        return colors[type] || "lightgray";
    }

    // Symulacja pobierania wydarzeń
    function fetchEvents() {
        return Promise.resolve([
            { "date": "2025-01-08", "startTime": 8.5, "endTime": 10, "description": "Matematyka", "type": "Wykład" },
            { "date": "2025-01-08", "startTime": 10, "endTime": 12, "description": "Fizyka", "type": "Laboratorium" },
            { "date": "2025-01-09", "startTime": 13, "endTime": 14.5, "description": "Chemia", "type": "Audytorium" }
        ]);
    }

    // Funkcja renderowania wydarzeń dla danej daty
    function renderEventsForDate(date) {
        clearCalendar();

        fetchEvents().then(events => {
            const formattedDate = date.toISOString().split('T')[0];
            const filteredEvents = events.filter(event => event.date === formattedDate);

            filteredEvents.forEach(event => {
                addEvent(event.startTime, event.endTime, event.description, event.type);
            });
        });
    }

    // Aktualizacja wyświetlania daty i wydarzeń
    function updateDateDisplay() {
        const dateElement = document.querySelector('#schedule-date .date');
        const dayHeader = document.querySelector('.day-header');

        dateElement.textContent = formatDate(currentDate);
        dayHeader.textContent = getDayName(currentDate);

        renderEventsForDate(currentDate);
    }

    // Obsługa zmiany daty
    document.getElementById('prev-day').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 1);
        updateDateDisplay();
    });

    document.getElementById('next-day').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 1);
        updateDateDisplay();
    });

    // Inicjalizacja kalendarza
    for (let hour = 7; hour <= 20; hour++) {
        const timeSlot = document.createElement('div');
        timeSlot.classList.add('time-slot');
        timeSlot.textContent = `${hour}:00`;
        calendar.appendChild(timeSlot);

        const eventSlot = document.createElement('div');
        eventSlot.classList.add('event-slot');
        eventSlot.dataset.startHour = hour;
        eventSlot.dataset.endHour = hour + 1;
        calendar.appendChild(eventSlot);
    }

    // Pierwsze wyświetlenie
    updateDateDisplay();
});


