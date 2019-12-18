import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import { showAlert } from '../../../Utils/NotificationUtils';
import getTranslation from '../../../Utils/TranslationService';

import ServiceCard from './ServiceCard';

const ServicesList = (props) => {
  const { selectedService, baseURL } = props;
  const [allServices, setAllServices] = useState([]);
  const [isFetching, setFetching] = useState(true);


  const getSelectedServices = () => {
    setFetching(true);
    axios({
      method: 'get',
      baseURL,
      url: `/public-api/services/${selectedService}`,
    }).then((response) => {
      setAllServices(response.data);
      setFetching(false);
      console.log(response.data);
    })
      .catch((error) => {
        showAlert(getTranslation('services.error'), 'error', 4000);
      });
  };

  useEffect(() => {
    console.log(selectedService);
    getSelectedServices();
  },
  [selectedService]);

  return (
    allServices.length === 0 || isFetching
      ? <div className="loader -static"><div></div><div></div><div></div><div></div></div>
      : (
        <>
          <h2 className="services-title -home">
            { getTranslation(`services.${selectedService.toLowerCase()}`) }
          </h2>
          <div className="services__cards">
            <div className="row">
              {
                allServices.map((service) => (
                  <ServiceCard service={service} selectedService={selectedService} />
                ))
              }
            </div>
          </div>
        </>
      )
  );
};

ServicesList.propTypes = {
  selectedService: PropTypes.string.isRequired,
  baseURL: PropTypes.string.isRequired,
};

export default ServicesList;
