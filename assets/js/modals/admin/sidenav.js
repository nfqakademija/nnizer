const toggleBtn = document.querySelector('.js-toggler');
const closeBtn = document.querySelector('.js-sidenav-close');
const panel = document.querySelector('.js-panel');
const menu = document.querySelector('.js-sidenav');
const overlay = document.querySelector('.js-overlay');

function toggleSidebar() {
  menu.classList.toggle('-open');
  panel.classList.toggle('-nav-open');
}

export default function initListeners() {
  const elements = [toggleBtn, closeBtn, overlay];
  elements.forEach((el) => el.addEventListener('click', toggleSidebar));
}
