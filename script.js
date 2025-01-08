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

function getWeekRange(date) {
    const startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() - date.getDay() + 1);
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    return {
        start: startOfWeek,
        end: endOfWeek
    };
}

function formatWeekRange(range) {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const start = range.start.toLocaleDateString('pl-PL', options);
    const end = range.end.toLocaleDateString('pl-PL', options);
    return `${start} - ${end}`;
}

document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.querySelector('#calendar');
    let currentDate = new Date();
    let isWeekView = false;

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

        if (isWeekView) {
            const weekRange = getWeekRange(currentDate);
            dateElement.textContent = formatWeekRange(weekRange);
            dayHeader.textContent = `Tydzień ${weekRange.start.getWeek()}`;
        } else {
            dateElement.textContent = formatDate(currentDate);
            dayHeader.textContent = getDayName(currentDate);
        }

        renderEventsForDate(currentDate);
    }

    // Obsługa zmiany daty
    document.getElementById('prev-day').addEventListener('click', () => {
        if (isWeekView) {
            currentDate.setDate(currentDate.getDate() - 7);
        } else {
            currentDate.setDate(currentDate.getDate() - 1);
        }
        updateDateDisplay();
    });

    document.getElementById('next-day').addEventListener('click', () => {
        if (isWeekView) {
            currentDate.setDate(currentDate.getDate() + 7);
        } else {
            currentDate.setDate(currentDate.getDate() + 1);
        }
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

    // Funkcja tworząca nagłówki dni tygodnia
    function createWeekHeaders(startDate) {
        const daysOfWeek = ['Godzina'];
        const dayNames = ['Niedz.', 'Pon.', 'Wt.', 'Śr.', 'Czw.', 'Pt.', 'Sob.'];
        for (let i = 0; i < 7; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            const dayName = dayNames[date.getDay()];
            const formattedDate = `${dayName} ${date.getDate().toString().padStart(2, '0')}.${(date.getMonth() + 1).toString().padStart(2, '0')}`;
            daysOfWeek.push(formattedDate);
        }

        const headerRow = document.createElement('div');
        headerRow.classList.add('header-row');

        daysOfWeek.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.classList.add('header', 'day-header');
            dayHeader.textContent = day;
            headerRow.appendChild(dayHeader);
        });

        return headerRow;
    }

    // Obsługa zmiany widoku
    document.getElementById('week-view').addEventListener('click', () => {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const startDate = new Date(currentDate);
        startDate.setDate(currentDate.getDate() - currentDate.getDay() + 1);

        calendar.appendChild(createWeekHeaders(startDate));

        for (let hour = 7; hour <= 20; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            timeSlot.textContent = `${hour}:00`;
            calendar.appendChild(timeSlot);

            for (let day = 0; day < 7; day++) {
                const eventSlot = document.createElement('div');
                eventSlot.classList.add('event-slot');
                eventSlot.dataset.startHour = hour;
                eventSlot.dataset.endHour = hour + 1;

                const date = new Date(startDate);
                date.setDate(startDate.getDate() + day);
                eventSlot.dataset.date = date.toISOString().split('T')[0];

                calendar.appendChild(eventSlot);
            }
        }

        calendar.classList.add('week-view');
        isWeekView = true;
        updateDateDisplay();
        renderEventsForDate(currentDate); 
    });

    document.getElementById('day-view').addEventListener('click', () => {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';
    
        const timeHeader = document.createElement('div');
        timeHeader.classList.add('header', 'time-header');
        timeHeader.textContent = 'Godzina';
        calendar.appendChild(timeHeader);
    
        const dayHeader = document.createElement('div');
        dayHeader.classList.add('header', 'day-header');
        dayHeader.textContent = getDayName(currentDate); 
        calendar.appendChild(dayHeader);
    
        for (let hour = 7; hour <= 20; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            timeSlot.textContent = `${hour}:00`;
            calendar.appendChild(timeSlot);
    
            const eventSlot = document.createElement('div');
            eventSlot.classList.add('event-slot');
            eventSlot.dataset.startHour = hour;
            eventSlot.dataset.endHour = hour + 1;
            eventSlot.dataset.date = currentDate.toISOString().split('T')[0]; 
            calendar.appendChild(eventSlot);
        }
    
        calendar.classList.remove('week-view');
        isWeekView = false;
        updateDateDisplay(); 
        renderEventsForDate(currentDate); 
    });
    
    // Pierwsze wyświetlenie
    updateDateDisplay();
});
