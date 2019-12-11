import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { ToastContainer } from 'react-toastify';

import Loader from './Loader';
import Hero from './Hero';
import Content from './Content';

import { showAlert } from '../../Utils/NotificationUtils';

const Template = () => {
  const [userData, setUserData] = useState();
  const [isFetched, setFetched] = useState(false);

  const fetchData = () => {
    const contractorName = window.location.href.split('/').pop();
    axios({
      method: 'get',
      baseURL: `${window.location.protocol}//${window.location.host}`,
      url: `/api/profile/${contractorName}/working-hous`,
    }).then((response) => {
      setUserData(response.data);
      setFetched(true);
    })
      .catch((error) => {
        showAlert('Can\'t get provider information. Try again or contact us!', 'error', 4000); // TODO translation
      });
  };

  const loadContent = () => {
    if (isFetched) {
      return (
        <>
          <Hero
            coverPath={userData.coverPhoto ? userData.coverPhoto.filename : null}
            title={userData.title}
            address={userData.address}
            reviews={userData.reviews}
          />
          <Content
            userData={userData}
          />
        </>
      );
    }
    return <Loader />;
  };

  useEffect(() => {
    fetchData();
  },
  []);

  return (
    <section className="contractor">
      <ToastContainer />
      {loadContent()}
    </section>
  );
};

export default Template;
