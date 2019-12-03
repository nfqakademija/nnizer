import React from 'react';
import { render } from 'react-dom';
import Panel from './components/admin/Panel';

require('../css/admin.scss');

render(
  <Panel />,
  document.getElementById('admin'),
);
