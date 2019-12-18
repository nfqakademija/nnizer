import React from 'react';
import PropTypes from 'prop-types';
import uuidv4 from 'uuid/v4';

import Filter from './Filter';
import getTranslation from '../../../Utils/TranslationService';


const ServiceFilters = (props) => {
  const { servicesList, selectedService, setSelectedService } = props;

  const getServices = () => servicesList.map((service) => (
    <Filter
      key={uuidv4()}
      name={service.name}
      selectedService={selectedService}
      setSelectedService={setSelectedService}
    />
  ));

  return (
    <>
      <p>
        {getTranslation('services.select_filters')}
        :
      </p>
      <div className="filters">
        {
          servicesList.length === 0
          ? <div className="loader -static"><div></div><div></div><div></div><div></div></div>
          : getServices()
        }
      </div>
    </>
  );
};

ServiceFilters.propTypes = {
  servicesList: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
  selectedService: PropTypes.string.isRequired,
  setSelectedService: PropTypes.func.isRequired,
};

export default ServiceFilters;
