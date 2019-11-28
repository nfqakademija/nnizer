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
import Reviews from './Reviews'
import Management from './Management';
import Statistics from './Statistics';
import Settings from './Settings';
import Help from './Help';

const Panel = () => {
  const [isNavOpen, toggleNav] = useState(false);
  const [reservations, setReservations] = useState([]);

  const fetchData = () => {
    const panel = document.querySelector('#admin');
    const { key } = panel.dataset;

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
            
            <Route path="/contractor/reservations" component={() => <Reservations reservations={reservations} />} />
            <Route path="/contractor/manage" component={Management} />
            <Route path="/contractor/settings" component={Settings} />
            <Route path="/contractor/reviews" component={Reviews} />
            <Route path="/contractor/statistics" component={Statistics} />
            <Route path="/contractor/settings" component={Settings} />
            <Route path="/contractor/help" component={Help} />
            <Route path="*" component={() => <Reservations reservations={reservations} />} />
          </Switch>
        </div>
      </div>
    </Router>
  );
};

export default Panel;
