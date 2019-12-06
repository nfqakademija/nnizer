import React, { useState, useEffect } from 'react';
import MicroModal from 'micromodal';
import axios from 'axios';
import Hero from './Hero';

const Template = () => {
  const [userData, setUserData] = useState([]);

  const fetchData = () => {
    const contractorName = window.location.href.split('/').pop();
    axios({
      method: 'get',
      baseURL: `${window.location.protocol}//${window.location.host}`,
      url: `/api/profile/${contractorName}/working-hours`,
    }).then((response) => {
      console.log(response.data);
      setUserData(response.data);
    })
      .catch((error) => {
        console.log(error) // TOOD - error handling
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

  useEffect(() => {
    fetchData();
    initModal();
  },
  []);

  return (
    <div>
      <Hero />
    </div>
  );
};

export default Template;
