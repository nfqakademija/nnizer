import React, { useState, useEffect, useRef } from 'react';
import { MemoryRouter as Router, Switch, Route } from 'react-router-dom';
import axios from 'axios';
import { ToastContainer } from 'react-toastify';

import Loader from '../contractor/Loader';
import Sidenav from './Sidenav';
import Header from './Header';
import Reservations from './Reservations';
import Reviews from './Reviews';
import Management from './Management';
import Statistics from './Statistics';
import Settings from './Settings';
import Help from './Help';

const Panel = () => {
  const [isNavOpen, toggleNav] = useState(false);
  const [data, setData] = useState({
    users: [],
    isFetched: false,
  });
  const [reservations, setReservations] = useState([]);

  const panel = document.querySelector('#admin');
  const baseURL = `${window.location.protocol}//${window.location.host}`;
  const { key, username } = panel.dataset;

  const CloseButton = (closeToast) => (
    <i className="icon-cross notification__close" onClick={() => closeToast} />
  );

  const fetchData = () => {
    axios({
      method: 'get',
      baseURL,
      url: `/api/profile/${username}/working-hours/`,
    })
      .then((response) => {
        setData({
          users: response.data,
          isFetched: true,
        });
      })
      .catch((error) => {
        console.log(error); // TODO handle error display in UI
      });

    axios({
      method: 'get',
      baseURL,
      url: `/api/contractor/${key}/get-clients/`,
    })
      .then((response) => {
        setReservations(response.data);
      })
      .catch((error) => {
        console.log(error); // TODO handle error display in UI
      });
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <Router>
      {!data.isFetched ? (
        <Loader />
      ) : (
        <div id="panel-view">
          <ToastContainer closeButton={<CloseButton />} />
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
            <Header
              isOpen={isNavOpen}
              toggleNav={toggleNav}
              avatar={
                data.isFetched
                  ? `${baseURL}/uploads/profile/${data.users.profilePhoto.filename}`
                  : ''
              }
              name={
                data.isFetched
                  ? `${data.users.firstname} ${data.users.lastname}`
                  : ''
              }
            />
            <Switch>
              <Route
                path="/contractor/reservations"
                component={() => (
                  <Reservations
                    userKey={key}
                    reservations={reservations}
                    fetchData={fetchData}
                  />
                )}
              />
              <Route path="/contractor/manage" component={Management} />
              <Route path="/contractor/settings" component={Settings} />
              <Route path="/contractor/reviews" component={Reviews} />
              <Route path="/contractor/statistics" component={Statistics} />
              <Route path="/contractor/settings" component={Settings} />
              <Route path="/contractor/help" component={Help} />
              <Route
                path="*"
                component={() => (
                  <Reservations
                    userKey={key}
                    reservations={reservations}
                    fetchData={fetchData}
                  />
                )}
              />
            </Switch>
          </div>
        </div>
      )}
    </Router>
  );
};

export default Panel;
