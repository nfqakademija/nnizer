import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';

const toggleBtn = document.querySelector('.js-toggler');
const closeBtn = document.querySelector('.js-sidenav-close');
const panel = document.querySelector('.js-panel');
const sidenav = document.querySelector('.js-sidenav');
const overlay = document.querySelector('.js-overlay');

const toggleScrolling = (isOpened) => {
  if (isOpened) {
    disableBodyScroll(sidenav);
  } else {
    enableBodyScroll(sidenav);
  }
};

const toggleSidebar = () => {
  sidenav.classList.toggle('-open');
  panel.classList.toggle('-nav-open');
  toggleScrolling(sidenav.classList.contains('-open'));
};



export default function initListeners() {
  const elements = [toggleBtn, closeBtn, overlay];
  elements.forEach((el) => el.addEventListener('click', toggleSidebar));
}
