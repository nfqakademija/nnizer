import React from 'react';
import PropTypes from 'prop-types';
import Rater from 'react-rater';

const Stars = (props) => {
  const { reviews } = props;
  console.log(reviews);

  return (
    <div className="rating">
      <span className="rating__score">4.9</span>
      <div className="rating__box">
        <Rater total={5} rating={3.5} interactive={false} />
        <span className="rating__count">21 reviews</span>
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
