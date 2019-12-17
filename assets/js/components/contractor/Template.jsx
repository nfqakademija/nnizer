import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { ToastContainer } from 'react-toastify';

import Loader from './Loader';
import Hero from './Hero';
import Content from './Content';

import { showAlert } from '../../Utils/NotificationUtils';
import getTranslation from "../../Utils/TranslationService";

const Template = () => {
  const [userData, setUserData] = useState();
  const [isFetched, setFetched] = useState(false);

  const CloseButton = (closeToast) => (
    <i className="icon-cross notification__close" onClick={() => closeToast} />
  );

  const fetchData = () => {
    const contractorName = window.location.href.split('/').pop();
    axios({
      method: 'get',
      baseURL: `${window.location.protocol}//${window.location.host}`,
      url: `/api/profile/${contractorName}`,
    }).then((response) => {
      setUserData(response.data);
      setFetched(true);
    })
      .catch((error) => {
        showAlert(getTranslation('contractor.error.provider'), 'error', 4000);
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
      <ToastContainer closeButton={<CloseButton />} />
      {loadContent()}
    </section>
  );
};

export default Template;

