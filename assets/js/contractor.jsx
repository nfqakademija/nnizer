import React from 'react';
import { render } from 'react-dom';
import MicroModal from 'micromodal';
import Template from './components/contractor/Template';

MicroModal.init({
  disableScroll: true,
  disableFocus: false,
  awaitOpenAnimation: true,
  awaitCloseAnimation: true,
});


render(
  <Template />,
  document.getElementById('contractor'),
);
