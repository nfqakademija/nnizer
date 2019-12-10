import React, { useState, useEffect } from 'react';
import {
  MemoryRouter as Router,
  Switch,
  Route,
} from 'react-router-dom';
import axios from 'axios';

import Sidenav from './Sidenav';
import PanelHeader from './PanelHeader';
import Reservations from './Reservations';
import Reviews from './Reviews';
import Settings from './Settings';

const Panel = () => {
  const [isNavOpen, toggleNav] = useState(false);
  const [reservations, setReservations] = useState([]);

  const panel = document.querySelector('#admin');
  const { key } = panel.dataset;

  const fetchData = () => {
    axios.get(`/api/contractor/${key}/get-clients/`)
      .then((response) => {
        setReservations(response.data);
      })
      .catch((error) => {
        console.log('error'); // TODO handle error display in UI
      });
  };

  useEffect(() => {
    fetchData();
  },
  [isNavOpen]);

  return (
    <Router>
      <div id="panel-view">
        <Sidenav isOpen={isNavOpen} toggleNav={toggleNav} />
        <div className={`panel ${isNavOpen ? '-nav-open' : ''}`}>
          <div
            role="button"
            aria-label="close menu"
            tabIndex="0"
            className="overlay js-overlay"
            onClick={() => toggleNav(!isNavOpen)}
            onKeyPress={() => toggleNav(!isNavOpen)}
          />
          <PanelHeader isOpen={isNavOpen} toggleNav={toggleNav} />
          <Switch>
            <Route path="/contractor/reservations" component={() => <Reservations reservations={reservations} userKey={key} fetchData={fetchData} />} />
            <Route path="/contractor/settings" component={Settings} />
            <Route path="/contractor/reviews" component={Reviews} />
            <Route path="/contractor/settings" component={Settings} />
            <Route path="*" component={() => <Reservations reservations={reservations} userKey={key} fetchData={fetchData} />} />
          </Switch>
        </div>
      </div>
    </Router>
  );
};

export default Panel;
