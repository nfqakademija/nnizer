import React from 'react';
import PropTypes from 'prop-types';
import { getTranslation } from '../../TranslationService';

const Reviews = (props) => {
  const { key } = props;
  // TODO /api/contractor/{key}/get-reviews/
  return (
    <div className="panel__content admin-container">
      <h2>{getTranslation('crm.reviews')}</h2>
    </div>
  );
};

export default Reviews;
