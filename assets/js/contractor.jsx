import React from 'react';
import { render } from 'react-dom';
import axios from 'axios';
import MicroModal from 'micromodal';

import { showAlert, updateAlert } from './Utils/NotificationUtils';
import Template from './components/contractor/Template';

const baseURL = `${window.location.protocol}//${window.location.host}`;
const bookForm = document.querySelector('.js-book-form');
const bookLoader = document.querySelector('.js-book-loader');

// TODO: translations

const handleFormResponse = (response) => {
  // 200 - form success
  if (response.status === 200) {
    updateAlert('Successfully booked!', 'success', 4000);
    bookForm.reset();
    MicroModal.close('register-modal');
    return;
  }
  // 206 - form validation failed
  if (response.status === 206) {
    const missingField = response.data[0].replace('.', ' ');
    updateAlert(`Booking failed! ${missingField}`, 'error', 4000);
    return;
  }
  // 406 - reservation time is already booked
  if (response.status === 406) {
    updateAlert('Chosen time is not available! Please select other date', 'error', 4000);
    return;
  }

  updateAlert('Something went wrong. Please try again', 'error', 4000);
};

const toggleFormClasses = () => {
  bookForm.classList.toggle('-disable');
  bookLoader.classList.toggle('-hide');
};

const validateBookForm = () => {
  bookForm.addEventListener('submit', (e) => {
    e.preventDefault();

    toggleFormClasses();
    showAlert('Sending booking details...', '', 10000);

    const formData = new FormData(bookForm);
    axios({
      method: 'post',
      url: `${baseURL}/new-reservation`,
      data: formData,
    })
      .then((response) => {
        handleFormResponse(response);
      })
      .catch((error) => {
        handleFormResponse(error.response);
      })
      .finally(() => toggleFormClasses());
  });
};

validateBookForm();

render(<Template />, document.getElementById('contractor'));
