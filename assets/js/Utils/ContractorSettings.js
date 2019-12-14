const daysForm = document.querySelector('.js-days-form');
const checkboxes = daysForm.querySelectorAll('.js-checkbox');
const daysFields = daysForm.querySelectorAll('.js-day-field input');

checkboxes.forEach((cb) => {
  cb.addEventListener('change', () => {
    const field = document.getElementsByClassName(cb.dataset.trigger)[0];
    field.classList.toggle('hidden');
  });
});

daysFields.forEach((field) => {
  if (field.value === '-1') {
    field.value = '';
  } else {
    const day = field.id.split('_')[2].toLowerCase();
    const checkbox = daysForm.querySelector(`.js-${day}-checkbox`);
    checkbox.checked = true;
    field.parentNode.classList.remove('hidden');
  }
});
