import React from 'react';
import { render } from 'react-dom';
import axios from 'axios';

import Template from './components/contractor/Template';

render(<Template />, document.getElementById('contractor'));

const baseURL = `${window.location.protocol}//${window.location.host}`;

const bookForm = document.querySelector('.js-book-form');
// const formData = new FormData();
const bookBtn = document.querySelector('.js-book-btn');

bookForm.addEventListener('submit', (e) => {
  e.preventDefault();

  // How to send data properly? select value?
  const date = document.querySelector('#datepicker input').value;
  const firstName = document.querySelector('.js-book-firstname').value;
  const secondName = document.querySelector('.js-book-lastname').value;
  const email = document.querySelector('.js-book-email').value;

  const formData = new FormData(bookForm);

  axios({
    method: 'post',
    url: `${baseURL}/new-reservation`,
    data: formData,
  })
    .then((response) => {
      console.log(response);
    })
    .catch((error) => {
      console.log(error);
    });
});
