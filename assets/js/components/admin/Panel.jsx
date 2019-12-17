import React, { useState, useEffect, useRef } from 'react';
import { MemoryRouter as Router, Switch, Route } from 'react-router-dom';
import axios from 'axios';
import { ToastContainer } from 'react-toastify';

import Loader from '../contractor/Loader';
import Sidenav from './Sidenav';
import Header from './Header';
import Reservations from './reservations/Reservations';
import Reviews from './reviews/Reviews';
import Settings from './settings/Settings';

import { showAlert } from '../../Utils/NotificationUtils';
import getTranslation from "../../Utils/TranslationService";

const Panel = () => {
  const [isNavOpen, toggleNav] = useState(false);
  const [data, setData] = useState({
    users: [],
    isFetched: false,
  });
  const [reservations, setReservations] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');

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
      url: `/api/profile/${username}`,
    })
      .then((response) => {
        setData({
          users: response.data,
          isFetched: true,
        });
      })
      .catch((error) => {
        showAlert(getTranslation('crm.error.profile'), 'error', 4000);
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
        showAlert(getTranslation('crm.error.reservations'), 'error', 4000);
      });
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <Router>
      <ToastContainer closeButton={<CloseButton />} />
      {!data.isFetched ? (
        <Loader />
      ) : (
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
            <Header
              isOpen={isNavOpen}
              toggleNav={toggleNav}
              setSearchTerm={setSearchTerm}
              avatar={
                data.isFetched
                  ? `${baseURL}/uploads/profile/${data.users.profilePhoto.filename}`
                  : ''
              }
              name={
                data.isFetched
                  ? data.users.firstname
                    ? `${data.users.firstname} ${data.users.lastname}`
                    : data.users.username
                  : ''
              }
            />
            <Switch>
              <Route
                path="/contractor/reservations"
                component={() => (
                  <Reservations
                    userKey={key}
                    userName={data.users.username}
                    reservations={reservations}
                    searchTerm={searchTerm}
                    fetchData={fetchData}
                  />
                )}
              />
              <Route path="/contractor/reviews" component={() => (<Reviews reviews={data.isFetched && data.users.reviews} />)} />
              <Route path="/contractor/settings" component={() => (<Settings userData={data.isFetched && data.users} />)} />
              <Route
                path="*"
                component={() => (
                  <Reservations
                    userKey={key}
                    userName={data.users.username}
                    reservations={reservations}
                    searchTerm={searchTerm}
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
