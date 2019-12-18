import React from 'react';
import PropTypes from 'prop-types';

import getTranslation from '../../../Utils/TranslationService';

const Filter = (props) => {
  const { name, selectedService, setSelectedService } = props;
  const isSelected = selectedService === name;

  return (
    <button
      type="button"
      className={`filters__btn ${isSelected && '-active'}`}
      onClick={() => setSelectedService(name)}
    >
      <i className="icon-cross" />
      { getTranslation(`services.${name.toLowerCase()}`) }
    </button>
  );
};

Filter.propTypes = {
  name: PropTypes.string.isRequired,
  selectedService: PropTypes.string.isRequired,
  setSelectedService: PropTypes.func.isRequired,
};

export default Filter;
