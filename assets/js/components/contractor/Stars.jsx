import React from 'react';
import PropTypes from 'prop-types';
import Rater from 'react-rater';
import { getTranslation } from '../../TranslationService';

const Stars = (props) => {
  const { reviews } = props;

  const getRating = () => {
    if (reviews.length === 0) {
      return 0;
    }
    let starsCount = 0;
    reviews.forEach((review) => {
      starsCount += review.stars;
    });
    return (starsCount / reviews.length).toPrecision(2);
  };

  const score = getRating();

  return (
    <div className="rating">
      <span className="rating__score">{score}</span>
      <div className="rating__box">
        <Rater total={5} rating={parseFloat(score, 0)} interactive={false} />
        <span className="rating__count">
          { reviews.length === 1
            ? '1 ' + getTranslation('contractor.reviews.singular')
            : reviews.length >= 10 && reviews.length <= 19
              ? `${reviews.length} ` + getTranslation('contractor.reviews.plural1')
              : `${reviews.length} ` + getTranslation('contractor.reviews.plural2')}
        </span>
      </div>
    </div>
  );
};

Stars.propTypes = {
  reviews: PropTypes.arrayOf(PropTypes.shape({})),
};

Stars.defaultProps = {
  reviews: [],
};

export default Stars;
