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
    showAlert(msg, '', false);
  }
};

renderAlertContainer();
displayBackendAlert();
