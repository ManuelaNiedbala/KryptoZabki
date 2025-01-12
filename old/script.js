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

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            {
                title: 'Matematyka',
                start: '2025-01-08T08:30:00',
                end: '2025-01-08T10:00:00',
                color: '#0015FF'
            },
            {
                title: 'Fizyka',
                start: '2025-01-08T10:00:00',
                end: '2025-01-08T12:00:00',
                color: '#1EFF00'
            },
            {
                title: 'Chemia',
                start: '2025-01-09T13:00:00',
                end: '2025-01-09T14:30:00',
                color: '#FFFF00'
            }
        ]
    });

    calendar.render();
});
