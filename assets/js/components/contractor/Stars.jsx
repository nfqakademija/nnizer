import React from 'react';
import PropTypes from 'prop-types';
import Rater from 'react-rater';

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
          { reviews.length === 1 ? '1 review' : `${reviews.length} reviews`}
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
