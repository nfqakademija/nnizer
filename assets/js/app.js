import React from 'react';
import { render } from 'react-dom';
import { Alert, showAlert } from './Utils/NotificationUtils';

require('../css/app.scss');

const renderAlertContainer = () => {
  const alertContainer = document.querySelector('#backend-alert-container');
  render(
    <Alert />,
    alertContainer,
  );
};

const displayBackendAlert = () => {
  const backendAlert = document.querySelector('.js-backend-alert');
  if (document.body.contains(backendAlert)) {
    const msg = backendAlert.innerHTML;
    showAlert(msg, '', 5000);
  }
};

const toggleMobileNav = () => {
  const toggler = document.querySelector('.js-header-toggler');
  const nav = document.querySelector('.js-header-nav');
  toggler.addEventListener('click', () => nav.classList.toggle('-active'));
};

renderAlertContainer();
displayBackendAlert();
toggleMobileNav();
