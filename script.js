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

const dropdownButton = document.querySelector('.dropdown-toggle');
const dropdownMenu = document.querySelector('.dropdown-menu');

dropdownButton.addEventListener('click', () => {
    const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
    dropdownButton.setAttribute('aria-expanded', !isExpanded);
    dropdownMenu.setAttribute('aria-hidden', isExpanded);
});
