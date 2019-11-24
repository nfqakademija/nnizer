import React, { useState, useEffect } from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  withRouter,
} from 'react-router-dom';
import Sidenav from './Sidenav';
import PanelHeader from './PanelHeader';
import Reservations from './Reservations';
import Settings from './Settings';

const Panel = () => {
  const [isNavOpen, toggleNav] = useState(false);

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
            <Route path="/contractor/reservations" component={Reservations} />
            <Route path="/contractor/settings" component={Settings} />
          </Switch>
        </div>
      </div>
    </Router>
  );
};

export default Panel;
