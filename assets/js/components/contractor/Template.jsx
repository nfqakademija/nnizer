import React, { useState, useEffect } from 'react';
import MicroModal from 'micromodal';
import axios from 'axios';

import Loader from './Loader';
import Hero from './Hero';
import Content from './Content';

const Template = () => {
  const [userData, setUserData] = useState();
  const [isFetched, setFetched] = useState(false);

  const fetchData = () => {
    const contractorName = window.location.href.split('/').pop();
    axios({
      method: 'get',
      baseURL: `${window.location.protocol}//${window.location.host}`,
      url: `/api/profile/${contractorName}/working-hours`,
    }).then((response) => {
      console.log(response.data);
      setUserData(response.data);
      setFetched(true);
    })
      .catch((error) => {
        console.log(error); // TOOD - error handling
      });
  };

  const initModal = () => {
    MicroModal.init({
      disableScroll: true,
      disableFocus: false,
      awaitOpenAnimation: true,
      awaitCloseAnimation: true,
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
    initModal();
  },
  []);

  return (
    <section className="contractor">
      {loadContent()}
    </section>
  );
};

export default Template;
