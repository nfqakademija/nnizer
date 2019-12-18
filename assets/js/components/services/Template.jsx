import React, { useState, useEffect } from 'react';
import axios from 'axios';

import getTranslation from '../../Utils/TranslationService';
import { Alert, showAlert } from '../../Utils/NotificationUtils';

import ServicesFilters from './filters/ServicesFilters';
import ServicesList from './list/ServicesList';

const Template = () => {
  const [isFetched, setFetched] = useState(false);
  const [servicesList, setServicesList] = useState([]);
  const [selectedService, setSelectedService] = useState('Hairdressing');

  const baseURL = `${window.location.protocol}//${window.location.host}`;

  const fetchData = () => {
    axios({
      method: 'get',
      baseURL,
      url: '/public-api/services',
    }).then((response) => {
      setServicesList(response.data);
      setFetched(true);
    })
      .catch((error) => {
        showAlert(getTranslation('services.error'), 'error', 4000);
      });
  };

  useEffect(() => {
    fetchData();
  },
  []);

  return (
    <section className="services">
      <Alert />
      <div className="container -top-spacing">
        <ServicesFilters servicesList={servicesList} selectedService={selectedService} setSelectedService={setSelectedService} />
        <ServicesList selectedService={selectedService} baseURL={baseURL} />
      </div>
    </section>
  );
};

export default Template;
