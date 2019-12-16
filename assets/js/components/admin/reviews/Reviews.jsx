import React from 'react';
import PropTypes from 'prop-types';
import uuidv4 from 'uuid/v4';
import { getTranslation } from '../../../TranslationService';

import Stars from '../../Stars';
import ReviewCard from './ReviewCard';

const Reviews = (props) => {
  const { reviews } = props;

  return (
    <div className="panel__content admin-container">
      <div className="panel__review-title">
        <h2>{getTranslation('crm.reviews')}</h2>
        <Stars reviews={reviews} />
      </div>
      <ul className="admin-reviews">
        {
        reviews.length === 0
          ? `${getTranslation('contractor.reviews.no_reviews')} 😤`
          : reviews.map((review) => (
            <li key={uuidv4()} className="admin-reviews__item">
              <ReviewCard
                name={`${review.reservation.firstname} ${review.reservation.lastname}`}
                message={review.description}
                stars={review.stars}
                date={review.reservation.visitDate}
              />
            </li>
          ))
        }
      </ul>
    </div>
  );
};

Reviews.propTypes = {
  reviews: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
};

export default Reviews;
